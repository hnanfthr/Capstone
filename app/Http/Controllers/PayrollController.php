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
        $profitShare = $request->input('profit_share', 15); // Default 15%
        
        // Cari total pemasukan dari pesanan yang berstatus 'Selesai' hari ini
        $autoIncome = \App\Models\Order::whereDate('updated_at', $date)
            ->where('status', 'Selesai')
            ->sum('total_price');
            
        $totalIncome = $request->input('total_income', $autoIncome);

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

        // Hitung total bagi hasil yang dialokasikan
        $totalWage = $totalIncome * ($profitShare / 100);

        // Hitung upah masing-masing secara proporsional
        foreach ($payrollData as &$data) {
            if ($totalHours > 0) {
                $data['wage'] = ($totalWage / $totalHours) * $data['hours_worked'];
            } else {
                $data['wage'] = 0;
            }
        }

        return view('payroll.index', compact(
            'date', 'profitShare', 'totalIncome', 'totalWage', 'totalHours', 'payrollData'
        ));
    }
}
