<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));
        $employees = Employee::all();
        
        $attendances = Attendance::whereDate('date', $date)
            ->get()
            ->keyBy('employee_id');

        return view('attendances.index', compact('employees', 'attendances', 'date'));
    }

    public function clockIn(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
        ]);

        $attendance = Attendance::firstOrCreate(
            ['employee_id' => $validated['employee_id'], 'date' => $validated['date']],
            ['clock_in' => Carbon::now()->format('H:i:s')]
        );

        if (!$attendance->wasRecentlyCreated && empty($attendance->clock_in)) {
            $attendance->update(['clock_in' => Carbon::now()->format('H:i:s')]);
        }

        return redirect()->back()->with('success', 'Berhasil Clock In.');
    }

    public function clockOut(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
        ]);

        $attendance = Attendance::where('employee_id', $validated['employee_id'])
            ->where('date', $validated['date'])
            ->first();

        if ($attendance) {
            $attendance->update(['clock_out' => Carbon::now()->format('H:i:s')]);
            return redirect()->back()->with('success', 'Berhasil Clock Out.');
        }

        return redirect()->back()->withErrors(['Karyawan belum Clock In hari ini.']);
    }

    public function history(Request $request)
    {
        $query = Attendance::with('employee')->orderBy('date', 'desc')->orderBy('clock_in', 'desc');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $attendances = $query->paginate(20)->withQueryString();

        return view('attendances.history', compact('attendances'));
    }

    public function export(Request $request)
    {
        $query = Attendance::with('employee')->orderBy('date', 'desc')->orderBy('clock_in', 'desc');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $attendances = $query->get();

        $filename = "riwayat_absensi_" . date('Ymd_His') . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($attendances) {
            $file = fopen('php://output', 'w');
            
            // Kolom header CSV
            fputcsv($file, ['Tanggal', 'Nama Karyawan', 'Jam Masuk', 'Jam Keluar', 'Total Jam Kerja']);

            foreach ($attendances as $row) {
                $hoursWorkedFormatted = '-';
                if ($row->clock_in && $row->clock_out) {
                    $in = Carbon::parse($row->clock_in);
                    $out = Carbon::parse($row->clock_out);
                    $diffInMinutes = $in->diffInMinutes($out);
                    
                    $hours = floor($diffInMinutes / 60);
                    $minutes = $diffInMinutes % 60;
                    
                    if ($hours > 0 && $minutes > 0) {
                        $hoursWorkedFormatted = "{$hours} Jam {$minutes} Menit";
                    } elseif ($hours > 0) {
                        $hoursWorkedFormatted = "{$hours} Jam";
                    } else {
                        $hoursWorkedFormatted = "{$minutes} Menit";
                    }
                }

                fputcsv($file, [
                    $row->date,
                    $row->employee ? $row->employee->name : 'N/A',
                    $row->clock_in ?? '-',
                    $row->clock_out ?? '-',
                    $hoursWorkedFormatted
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
