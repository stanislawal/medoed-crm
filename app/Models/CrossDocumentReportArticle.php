<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrossDocumentReportArticle extends Model
{
    use HasFactory;

    public $table = 'cross_document_report_articles';

    public $fillable = [
        'document_report_id',
        'article_id',
    ];

    public $timestamps = false;
}
