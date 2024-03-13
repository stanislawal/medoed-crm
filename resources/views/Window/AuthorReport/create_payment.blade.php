<div class="modal fade" id="create_payment" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Создание заявки оплаты</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('author_payment.create') }}" method="POST">
                    @csrf
                    <input type="hidden" name="author_id" value="{{ $authorId }}">
                    <div class="form-group">
                        <label for="">Дата оплаты</label>
                        <input class="form-control form-control-sm" type="date" name="date"
                               value="{{ now()->format('Y-m-d') }}" required>
                    </div>

                    <div class="form-group">
                        <div>
                            <label for="">Сумма оплаты</label>
                            <input type="number" required name="amount" onkeyup="setAmountFormat(this)" class="form-control form-control-sm" step="0.01"
                                   value="0">
                            <div class="d-flex justify-content-end amount-format text-14 font-weight-bold">0</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">Комментарий</label>
                        <textarea class="form-control form-control-sm" name="comment" id="" cols="30"
                                  rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-sm btn-success">Создать</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
