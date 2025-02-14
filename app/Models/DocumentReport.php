<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentReport extends Model
{
    use HasFactory;

    public $table = 'document_reports';

    public $fillable = [
        'author_id',
        'url',
        'file_name',
        'type',
        'is_send',
        'date_time_send'
    ];

//    public function sroccArticles()
//    {
//        return $this->hasMany(CrossDocumentReportArticle::class, 'document_report_id', 'id');
//    }

    public function sroccArticles()
    {
        return $this->belongsToMany(Article::class, CrossDocumentReportArticle::class, 'document_report_id', 'article_id');
    }
}
