<?php

namespace App\Models;

use App\Models\Project\Cross\CrossprojectArticle;
use App\Models\Project\Cross\CrossProjectAuthor;
use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'check' //галочка
    ];

    public $timestamps = true;

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
}
