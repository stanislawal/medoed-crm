<?php

namespace App\Http\Controllers\Option;

use App\Http\Controllers\Controller;
use App\Models\Client\SocialNetwork;
use Illuminate\Http\Request;

class SocialNetworkController extends Controller
{
    public function index()
    {

        return view('Option.add_option_socialnetwork', [
            'socialnetwork' => SocialNetwork::on()->with(['isUse'])->orderBy('id', "asc")->get()
        ]);
    }

    public function getSelect()
    {
        $socialnetworks = SocialNetwork::on()->get();
        return response()->json([
            'html' => view('Render.Socialnetwork.select_socialnetwork', ['socialnetworks' => $socialnetworks])->render()
        ]);
    }

    public function store(Request $request)
    {
        $socialnetwork = [
            'name' => $request->add_new_socialnetwork
        ];

        SocialNetwork::on()->create($socialnetwork);

        return redirect()->back()->with(['message' => 'Социальная сеть успешно добавлена']);
    }

    public function destroy($id)
    {
        SocialNetwork::on()->where('id', $id)->delete();
        return redirect()->back()->with(['success' => 'Соц.сеть успешно удалена']);
    }
}
