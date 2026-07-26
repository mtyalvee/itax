<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payslip extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'gross_pay',
        'paye',
        'usc',
        'prsi',
        'employer_prsi',
        'net_pay',
        'hours_worked',
        'overtime_hours',
        'bonus',
        'period_start',
        'period_end',
        'status',
    ];

    protected $casts = [
        'gross_pay' => 'decimal:2',
        'paye' => 'decimal:2',
        'usc' => 'decimal:2',
        'prsi' => 'decimal:2',
        'employer_prsi' => 'decimal:2',
        'net_pay' => 'decimal:2',
        'hours_worked' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'bonus' => 'decimal:2',
        'period_start' => 'date',
        'period_end' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
