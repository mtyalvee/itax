<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxLiability extends Model
{
    use HasFactory;

    protected $fillable = [
        'tax_period',
        'paye',
        'usc',
        'prsi_employee',
        'prsi_employer',
        'total_liability',
    ];

    protected $casts = [
        'paye' => 'decimal:2',
        'usc' => 'decimal:2',
        'prsi_employee' => 'decimal:2',
        'prsi_employer' => 'decimal:2',
        'total_liability' => 'decimal:2',
    ];
}
