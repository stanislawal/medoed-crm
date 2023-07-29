<?php

namespace App\Models;

use App\Models\Project\Project;
use App\Models\CrossArticleRedactor;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project\Cross\CrossProjectAuthor;
use App\Models\Project\Cross\CrossprojectArticle;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends BaseModel
{
    public $table = 'articles';

    protected $fillable = [
        'article', //статья
        'manager_id', //id пользователя
        'without_space', //кол-во знаков без пробелов
        'id_currency', //id валюты
        'link_text', //ссылка на текст
        'project_id',
        'price_client', //цена заказчика
        'price_author', //цена автора
        'price_redactor', //цена автора
        'check', //галочка
        'payment_amount', // сумма оплаты
        'payment_date' // дата оплаты
    ];

    public $timestamps = true;

    protected $casts = [
        'created_at' => 'datetime:d.m.Y H:i'
    ];

    //Отношение многие ко многим. первый параметр - связь с конечной таблице. второй параметр - название промежуточной таблицы.
    public function articleManager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    //Отношение многие ко многим. первый параметр - связь с конечной таблице. второй параметр - название промежуточной таблицы.
    public function articleProject()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    //Отношение многие ко многим. первый параметр - связь с конечной таблице. второй параметр - название промежуточной таблицы.
    public function articleCurrency()
    {
        return $this->belongsTo(Currency::class, 'id_currency');
    }

    //Отношение многие ко многим. первый параметр - связь с конечной таблице. второй параметр - название промежуточной таблицы.
    public function articleAuthor()
    {
        return $this->belongsToMany(User::class, CrossArticleAuthor::class, 'article_id', 'user_id');
    }

    public function articleRedactor()
    {
        return $this->belongsToMany(User::class, CrossArticleRedactor::class, 'article_id', 'user_id');
    }

}
