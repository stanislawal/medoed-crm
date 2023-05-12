<?php

namespace App\Models\Payment;

use App\Models\Article;
use App\Models\Project\Project;
use App\Models\StatusPayment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public $table = 'payment';

    protected $fillable = [
        'mark',
        'status_payment_id',
        'date',
        'sber_d',
        'sber_k',
        'privat',
        'um',
        'wmz',
        'birja',
        'number',
        'project_id',
        'article_id',
        'comment',
        'create_user_id'
    ];

    public $timestamps = true;

    public function project()
    {
        return $this->belongsTo(Project::class,'project_id');
    }

    public function article()
    {
        return $this->belongsTo(Article::class,'article_id');
    }

    public function status(){
        return $this->belongsTo(StatusPayment::class,'status_payment_id');
    }
}
