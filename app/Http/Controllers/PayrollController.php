<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Production;
use Carbon\Carbon;

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
            'date', 'rate', 'quantity', 'totalWage', 'totalHours', 'payrollData'
        ));
    }
}
