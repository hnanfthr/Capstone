<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;

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
}
