<?php

namespace App\Models\AuthorPayment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorPayment extends Model
{
    use HasFactory;

    public $table = 'payment_author';

    protected $fillable = [
        'author_id',
        'date',
        'amount',
        'comment'
    ];

    protected $casts = [
        'created_at' => 'datetime:d.m.Y H:i',
        'updated_at' => 'datetime:d.m.Y H:i'
    ];
}
