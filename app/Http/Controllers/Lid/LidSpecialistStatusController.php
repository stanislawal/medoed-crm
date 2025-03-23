<?php

namespace App\Http\Controllers\Lid;

use App\Models\Lid\LidSpecialistStatus;
use Illuminate\Http\Request;

class LidSpecialistStatusController
{
    private $model;

    public function __construct()
    {
        $this->model = LidSpecialistStatus::on();
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('lid.status_specialist.index', [
                'list' => $this->model->with('lids')->get()
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
            'name'  => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:255'],
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
