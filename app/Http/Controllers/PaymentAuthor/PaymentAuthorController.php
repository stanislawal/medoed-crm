<?php

namespace App\Http\Controllers\PaymentAuthor;

use App\Http\Controllers\Controller;
use App\Models\AuthorPayment\AuthorPayment;
use Illuminate\Http\Request;

class PaymentAuthorController extends Controller
{
    /*
     * Создать
     */
    public function create(Request $request)
    {
        try {
            $attr = [
                'author_id' => $request->author_id,
                'date'      => $request->date,
                'amount'    => $request->amount,
                'comment'   => $request->comment
            ];

            AuthorPayment::on()->create($attr);

            return redirect()->back()->with(['message' => 'Платежка по автору успешно создана']);

        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    /*
     * Обновить
     */
    public function update(Request $request, $id)
    {
        try {

            $attr = collect($request->all())->only(['date', 'amount', 'comment'])->toArray();

            AuthorPayment::on()->where('id', $id)->update($attr);

            return response()->json([
                'message' => 'Платежка по автору успешно обновлена'
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    /*
     *  Удалить
     */
    public function delete($id)
    {
        try {
            AuthorPayment::on()->where('id', $id)->delete();

            return redirect()->back()->with(['message' => 'Платежка по автору успешно удалена']);

        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
}
