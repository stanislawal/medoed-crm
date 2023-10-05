<?php

namespace App\Models\Project;

use App\Models\Article;
use App\Models\Client\Client;
use App\Models\Payment\Payment;
use App\Models\Project\Cross\CrossprojectArticle;
use App\Models\Project\Cross\CrossProjectAuthor;
use App\Models\Project\Cross\CrossProjectClient;
use App\Models\Status;
use App\Models\StatusPaymentProject;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public $table = 'projects';

    protected $fillable = [
        'created_user_id', //id пользователя, создавшего проект
        'manager_id', //id пользователя
        'theme_id', //id темы проекта
        'project_name', //имя проекта
        'mood_id', //настроение заказчика
        'pay_info', //информация об оплате
        'price_author', //информация об оплате
        'price_client', //информация об оплате
        'pay_method', //метод оплаты
        'start_date_project', // дата начала проекта
        'end_date_project', // дата конца проекта
        'total_symbols', //общее кол-во символов
        'date_last_change', // дата последнего прописывания
        'date_notification', // дата последнего прописывания
        'price_per', //цена за 1000 знаков
        'progress_symbols', //сколько написано символов
        'contract', // договор
        'check', // галочка
        'contract_exist', // договор если да
        'comment', // комментарий к проекту
        'project_status_text', // комментарий к проекту
        'style_id', // id стиля текса
        'status_payment_id', // id статуса оплаты проекта
        'status_id', //id состояния проекта
        'business_area', //сфера бизнесса
        'link_site', //ссылка на сайт
        'invoice_for_payment', //Счет для оплаты
        'project_perspective', //перспектива проекта
        'payment_terms', //Сроки оплаты
        'type_task', //Тип задачи
        'dop_info', //Дополнительная информация
        'duty', // временный долг
        'nds', // подпись о неразглашении
    ];

    public $timestamps = true;

    public function projectEvent()
    {
        return $this->hasMany(ProjectEvent::class, 'project_id');
    }

    public function notifiProject()
    {
        return $this->hasMany(NotifiProject::class, 'project_id');
    }

    public function projectAuthor()
    {
        //Отношение многие ко многим. первый параметр - связь с конечной таблице. второй параметр - название промежуточной таблицы.
        return $this->belongsToMany(User::class, CrossProjectAuthor::class, 'project_id', 'user_id');
    }

    public function projectArticle()
    {
        //Отношение многие ко многим. первый параметр - связь с конечной таблице. второй параметр - название промежуточной таблицы.
        return $this->belongsToMany(Article::class, CrossprojectArticle::class, 'project_id', 'article_id');
    }

    public function projectStatus()
    {
        //Обратное отношение. прямая связь моделей
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function projectStatusPayment()
    {
        //Обратное отношение. прямая связь моделей
        return $this->belongsTo(StatusPaymentProject::class, 'status_payment_id');
    }

    public function payment()
    {
        return $this->hasMany(Payment::class, 'project_id');
    }

    public function projectStyle()
    {
        return $this->belongsTo(Style::class, 'style_id');
    }

    public function projectMood()
    {
        return $this->belongsTo(Mood::class, 'mood_id');
    }

    public function projectUserCreate()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function projectTheme()
    {
        return $this->belongsTo(Theme::class, 'theme_id');
    }

    /*
     * СВЯЗЬ ПРОЕКТА С МЕНЕДЖЕРОМ
     */
    public function projectUser()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function projectClients()
    {
        //Отношение многие ко многим. первый параметр - связь с конечной таблице. второй параметр - название промежуточной таблицы.
        return $this->belongsToMany(Client::class, CrossProjectClient::class, 'project_id', 'client_id');
    }

    /**
     * Связь с файлом
     */
    public function files()
    {
        return $this->hasMany(File::class, 'project_id');
    }

}
