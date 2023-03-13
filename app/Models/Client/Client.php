<?php

namespace App\Models\Client;

use App\Models\Project\Cross\CrossClientSocialNetwork;
use App\Models\Project\Cross\CrossProjectClient;
use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    public $table = 'clients';

    protected $fillable = [
        'name', //имя клиента
        'dialog_location', //место проведения диалога
        'scope_work', //сфера деятельности
        'characteristic', //характеристика клиента
        'company_name', //Название компании
        'site', //сайт компании
        'link_socialnetwork', //ccылка на соцсети
        'contact_info',
        'manager_salary', //ставка менеджера
        'birthday'
    ];

    public $timestamps = true;

    public function socialNetwork(){
        //Отношение многие ко многим. первый параметр - связь с конечной таблице. второй параметр - название промежуточной таблицы.
        return $this->belongsToMany(SocialNetwork::class, CrossClientSocialNetwork::class)->withPivot('description');
    }

    public function projectClients()
    {
        //Отношение многие ко многим. первый параметр - связь с конечной таблице. второй параметр - название промежуточной таблицы.
        return $this->belongsToMany(Project::class, CrossProjectClient::class, 'client_id', 'project_id');
    }
}


