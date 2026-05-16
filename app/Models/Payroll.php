<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'date',
        'employee_id',
        'hours_worked',
        'total_quantity',
        'rate_per_toples',
        'wage',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
