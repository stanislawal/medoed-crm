<?php

namespace App\Models\Client;

use App\Models\Project\Cross\CrossClientSocialNetwork;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialNetwork extends Model
{
    public $table = 'social_networks';

    protected $fillable = [
        'name' //название социальной сети
    ];

    public $timestamps = false;

    public function isUse(){
        return $this->hasMany(CrossClientSocialNetwork::class, 'social_network_id', 'id');
    }
}
