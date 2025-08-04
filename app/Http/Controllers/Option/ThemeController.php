<?php

namespace App\Http\Controllers\Option;

use Illuminate\Http\Request;
use App\Models\Project\Theme;
use App\Http\Controllers\Controller;

class ThemeController extends Controller
{

    public function index()
    {
        return view('Option.add_option_theme', [
            'theme' => Theme::on()->orderBy('id', "asc")->withExists('projects')->get()
        ]);
    }

    public function store(Request $request)
    {
        $theme = [
            'name' => $request->add_new_status
        ];

        Theme::on()->create($theme);

        return redirect()->back()->with(['message' => 'Тема успешно добавлена']);
    }

    public function destroy($id)
    {
        Theme::on()->where('id', $id)->delete();
        return redirect()->back()->with(['success' => 'Тема успешно удалена']);
    }
}
