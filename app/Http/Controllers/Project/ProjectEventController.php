<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project\ProjectEvent;
use Illuminate\Http\Request;

class ProjectEventController extends Controller
{
    public function store(Request $request)
    {

        $attr = $request->only(['project_id', 'date', 'comment']);
        ProjectEvent::on()->create($attr);
        return redirect()->back();
    }

    public function destroy($id)
    {
        ProjectEvent::on()->where('id', $id)->delete();
        return redirect()->back();
    }
}
