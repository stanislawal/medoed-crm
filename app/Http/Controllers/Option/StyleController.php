<?php

namespace App\Http\Controllers\Option;

use Illuminate\Http\Request;
use App\Models\Project\Style;
use App\Http\Controllers\Controller;


class StyleController extends Controller
{

    public function index()
    {
        return view('Option.add_option_style', [
            'style' => Style::on()->withExists('projects')->orderBy('id', "asc")->get()
        ]);
    }

    public function store(Request $request)
    {
        $style = [
            'name' => $request->add_new_style
        ];

        Style::on()->create($style);

        return redirect()->back()->with(['message' => 'Тип текста успешно добавлен']);
    }

    public function destroy($id)
    {
        Style::on()->where('id', $id)->delete();
        return redirect()->back()->with(['success' => 'Тип текста успешно удален']);
    }
}
