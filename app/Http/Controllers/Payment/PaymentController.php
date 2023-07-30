<?php

namespace App\Http\Controllers\Payment;

use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Payment\Payment;
use App\Models\Project\Project;
use App\Models\StatusPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    /**
     * Страница создания проекта
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $projects = Project::on()
            ->when(UserHelper::isManager(), function ($where) {
                $where->where('manager_id', UserHelper::getUserId());
            })
            ->select(['id', 'project_name'])
            ->get()->toArray();

        $paymentList = Payment::on()
            ->when(UserHelper::isManager(), function ($where) {
                $where->where('create_user_id', UserHelper::getUserId());
            })
            ->with(['project', 'status'])
            ->get()
            ->toArray();

        return view('Payment.create_payment', [
            'projects' => $projects,
            'paymentList' => $paymentList,
            'statuses' => StatusPayment::on()->get()->toArray()
        ]);
    }

    public function selectArticle($id)
    {
        $articles = Article::on()->where('project_id', $id)->get()->toArray();

        return response()->json([
            'result' => true,
            'html' => view('Render.Payment.select_article' ,['articles' => $articles])->render()
        ]);
    }

    /**
     * Создание заявки на оплату
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $params = collect($request->all())
            ->only(['project_id', 'date', 'sber_a', 'sber_d', 'sber_k', 'tinkoff_a', 'privat', 'um', 'wmz', 'birja', 'comment'])
            ->toArray();

        $params['status_payment_id'] = 1;
        $params['create_user_id'] = UserHelper::getUserId();

        Payment::on()->create($params);

        return redirect()->back()->with(['success' => 'Заявка на оплату успешно создана.']);
    }

    /**
     * Страница модерации заявок
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function moderation()
    {
        return view('Payment.moderation_payment', [
            'projects' => Project::on()->select(['id', 'project_name'])->get()->toArray(),
            'paymentList' => Payment::on()->with(['project', 'status'])->get()->toArray(),
            'statuses' => StatusPayment::on()->get()->toArray()
        ]);
    }

    /**
     * Обновляет заявку на оплате
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {

        $params = collect($request->all())
            ->only(['mark', 'back_duty', 'status_payment_id', 'sber_a', 'sber_d', 'sber_k', 'tinkoff_a' , 'privat', 'um',
                'wmz', 'birja', 'project_id', 'comment'])
            ->toArray();

        $hasMark = $this->hasMark($id);

        if (!UserHelper::isAdmin() && $hasMark) {

            return response()->json(['result' => false, 'message' => 'Невозможно обновить заявку']);

        } else {

            Payment::on()->where('id', $id)->update($params);
            return response()->json(['result' => true, 'message' => 'success']);

        }
    }

    /**
     * Удаляет заявку на оплату
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        if (UserHelper::isAdmin() || !$this->hasMark($id)) {

            Payment::on()->where('id', $id)->delete();
            return redirect()->back()->with(['success' => 'Заявка на оплату была успешно удалена.']);

        } else {
            return redirect()->back()->with(['error' => 'Невозможно удалить заявку.']);
        }

    }

    private function hasMark($id)
    {
        return (bool)Payment::on()->find($id)->mark;
    }
}
