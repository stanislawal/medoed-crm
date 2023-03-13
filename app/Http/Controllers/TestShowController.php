<?php

namespace App\Http\Controllers;

use App\Models\Client\Client;
use App\Models\Project\Project;
use Illuminate\Http\Request;

class TestShowController extends Controller
{
 public function test(){

    // $clients = Client::on()->with(['socialNetwork'])->get();
    // dd($clients->toArray());

    // $projects = Project::on()->with(['projectAuthor'])->get();
    // dd($projects->toArray());

    $status = Project::on()->with(['projectStatus', 'projectAuthor', 'projectStyle', 'projectMood', 'projectUserCreate'])->get();
    dd($status->toArray());
 }
}
