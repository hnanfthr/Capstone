<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Production;
use App\Models\Payroll;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));
        $rate = $request->input('rate', 15000); // Default Rp 15.000 per toples
        
        // Coba cari total kuantitas toples hari ini secara otomatis jika tidak ada input manual
        $autoQuantity = \App\Models\Production::whereDate('production_date', $date)->sum('quantity');
        $quantity = $request->input('quantity', $autoQuantity);

        // Ambil data absensi hari ini yang punya jam masuk dan keluar
        $attendances = Attendance::with('employee')
            ->whereDate('date', $date)
            ->whereNotNull('clock_in')
            ->whereNotNull('clock_out')
            ->get();

        $totalHours = 0;
        $payrollData = [];

        foreach ($attendances as $att) {
            $in = Carbon::parse($att->clock_in);
            $out = Carbon::parse($att->clock_out);
            
            // Hitung selisih jam kerja dalam format desimal (misal 11:30 jadi 11.5)
            $diffInMinutes = $in->diffInMinutes($out);
            $hoursWorked = $diffInMinutes / 60;

            $totalHours += $hoursWorked;

            $payrollData[] = [
                'employee' => $att->employee->name,
                'clock_in' => $att->clock_in,
                'clock_out' => $att->clock_out,
                'hours_worked' => $hoursWorked,
            ];
        }

        // Hitung total upah borongan
        $totalWage = $quantity * $rate;

        // Hitung upah masing-masing secara proporsional
        foreach ($payrollData as &$data) {
            if ($totalHours > 0) {
                $data['wage'] = ($totalWage / $totalHours) * $data['hours_worked'];
            } else {
                $data['wage'] = 0;
            }
        }

        return view('payroll.index', compact(
            'date', 'rate', 'quantity', 'totalWage', 'totalHours', 'payrollData', 'attendances'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'rate' => 'required|numeric',
            'quantity' => 'required|numeric',
            'employees' => 'required|array',
            'employees.*' => 'exists:employees,id',
            'hours' => 'required|array',
            'wages' => 'required|array',
        ]);

        $date = $request->date;
        $rate = $request->rate;
        $quantity = $request->quantity;

        // Cek apakah tanggal ini sudah pernah digaji
        $existing = Payroll::whereDate('date', $date)->exists();
        if ($existing) {
            return redirect()->back()->with('error', 'Data penggajian untuk tanggal tersebut sudah pernah disimpan!');
        }

        foreach ($request->employees as $index => $employeeId) {
            Payroll::create([
                'date' => $date,
                'employee_id' => $employeeId,
                'hours_worked' => $request->hours[$index] ?? 0,
                'total_quantity' => $quantity,
                'rate_per_toples' => $rate,
                'wage' => $request->wages[$index] ?? 0,
            ]);
        }

        return redirect()->route('payroll.history')->with('success', 'Data penggajian berhasil disimpan ke riwayat.');
    }

    public function history(Request $request)
    {
        $query = Payroll::with('employee')->latest('date');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $payrolls = $query->paginate(20)->withQueryString();

        return view('payroll.history', compact('payrolls'));
    }

    public function export(Request $request)
    {
        $query = Payroll::with('employee')->orderBy('date', 'desc');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $payrolls = $query->get();

        $filename = "riwayat_gaji_" . date('Ymd_His') . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($payrolls) {
            $file = fopen('php://output', 'w');
            
            // Kolom header CSV
            fputcsv($file, ['Tanggal', 'Nama Karyawan', 'Jam Kerja', 'Total Toples Harian', 'Rate Per Toples', 'Upah Diterima']);

            foreach ($payrolls as $row) {
                fputcsv($file, [
                    $row->date,
                    $row->employee ? $row->employee->name : 'N/A',
                    $row->hours_worked,
                    $row->total_quantity,
                    $row->rate_per_toples,
                    $row->wage
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
