<?php

namespace App\Models\Client;

use App\Models\BaseModel;
use App\Models\Project\Cross\CrossClientSocialNetwork;
use App\Models\Project\Cross\CrossProjectClient;
use App\Models\Project\File;
use App\Models\Project\Project;

class Client extends BaseModel
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
        'birthday',
        'lpr_contacts',
        'info_work_team',
        'additional_info',
        'source_client_id' // источник поступления
    ];

    public $timestamps = true;

    public function sourceClient()
    {
        // источник поступления
        return $this->belongsTo(SourceClient::class, 'source_client_id');
    }

    public function socialNetwork()
    {
        //Отношение многие ко многим. первый параметр - связь с конечной таблице. второй параметр - название промежуточной таблицы.
        return $this->belongsToMany(SocialNetwork::class, CrossClientSocialNetwork::class)->withPivot('description');
    }

    public function projectClients()
    {
        //Отношение многие ко многим. первый параметр - связь с конечной таблице. второй параметр - название промежуточной таблицы.
        return $this->belongsToMany(Project::class, CrossProjectClient::class, 'client_id', 'project_id');
    }

    /**
     * Связь с файлом
     */
    public function files()
    {
        return $this->hasMany(File::class, 'client_id');
    }
}


