<?php

namespace App\Models\Project\Cross;

use Illuminate\Database\Eloquent\Model;

class CrossClientSocialNetwork extends Model
{
public $table = 'cross_client_social_networks';

protected $fillable = [
    'client_id', //id клиента
    'social_network_id', //id социальной сети
    'description' //описание
];

 public $timestamps = false;


}
