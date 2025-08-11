<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyAccrual extends Model
{
    use HasFactory;

    public $table = 'monthly_accruals';

    protected $fillable = [
        'date',
        'project_id',
        'amount'
    ];

    public $timestamps = true;
}
