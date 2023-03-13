<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrossArticleAuthor extends BaseModel
{
    public $table = 'cross_article_authors';

    protected $fillable = [
        'user_id', //id пользователя
        'article_id' //id статьи
    ];

    public $timestamps = false;
}

