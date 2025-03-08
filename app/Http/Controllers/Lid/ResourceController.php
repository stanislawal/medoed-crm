<?php

namespace App\Http\Controllers\Lid;

use App\Http\Controllers\Controller;
use App\Models\Lid\Resource;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = Resource::on();
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('lid.resources.index', [
                'list' => $this->model->get()
            ]
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $attr = $request->validate([
            'name'  => ['required', 'string', 'max:255']
        ]);

        $this->model->create($attr);

        return redirect()->back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->model->find($id)->delete();
        return redirect()->back();
    }
}
