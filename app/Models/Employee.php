<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'pps_number',
        'department',
        'job_title',
        'hourly_rate',
        'salary',
        'active',
        'tax_credit',
        'cutoff_point',
    ];

    protected $casts = [
        'active' => 'boolean',
        'hourly_rate' => 'decimal:2',
        'salary' => 'decimal:2',
        'tax_credit' => 'decimal:2',
        'cutoff_point' => 'decimal:2',
    ];

    public function payslips(): HasMany
    {
        return $this->hasMany(Payslip::class);
    }
}
