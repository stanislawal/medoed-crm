<?php

namespace App\Models\Lid;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lid extends Model
{
    use HasFactory;

    public $table = 'lids';

    protected $fillable = [
        'advertising_company', // рекламная компания
        'date_receipt', // дата поступленния лида
        'resource_id', // ресурс
        'name_link', // имя/ссылка
        'location_dialogue_id', // место ведения диалога
        'link_lid', // ссылка на лида
        'service_id', // услуга
        'call_up_id', // созвон
        'result_call', // итоги созвона, если он прошел
        'date_time_call_up', // дата и время созвона
        'audit_id', // аудит
        'specialist_task_id', // задача специалиста
        'transfer_date', // дата передачи
        'date_acceptance', // дата принятия
        'ready_date', // дата готовности
        'specialist_user_id', // специалист
        'interesting', // интересный
        'date_write_lid', // дата прописки лиду
        'write_lid', // прописать
        'lid_status_id', // статус
        'state', // состояние
        'lid_specialist_status_id', // статус специалиста
        'state_specialist', // // состояние специалиста
        'link_to_site', // ссылка на сайт
        'region', // регион
        'price', // цена
        'business_are', // сфера бизнеса
        'create_user_id' // кто создал
    ];

    public $timestamps = true;

    public function resource()
    {
        return $this->belongsTo(Resource::class, 'resource_id');
    }

    public function locationDialogue()
    {
        return $this->belongsTo(LocationDialogue::class, 'location_dialogue_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function callUp()
    {
        return $this->belongsTo(CallUp::class, 'call_up_id');
    }

    public function audit()
    {
        return $this->belongsTo(Audit::class, 'audit_id');
    }

    public function specialistTask()
    {
        return $this->belongsTo(SpecialistTask::class, 'specialist_task_id');
    }

    public function specialistUser()
    {
        return $this->belongsTo(User::class, 'specialist_user_id');
    }

    public function lidStatus()
    {
        return $this->belongsTo(LidStatus::class, 'lid_status_id');
    }

    public function createUser()
    {
        return $this->belongsTo(User::class, 'create_user_id');
    }

    public function lidSpecialistStatus()
    {
        return $this->belongsTo(LidSpecialistStatus::class, 'lid_specialist_status_id');
    }
}
