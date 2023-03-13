<?php

namespace App\Models\Project\Cross;

use Illuminate\Database\Eloquent\Model;

class CrossProjectClient extends Model
{
    public $table = 'cross_project_clients';

    protected $fillable = [
        'project_id', //id проекта
        'client_id' //id заказчика
    ];

    public $timestamps = false;
}
