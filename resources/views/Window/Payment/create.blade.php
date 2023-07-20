<div class="modal fade" id="create_payment" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Создание заявки оплаты</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('payment.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="">Проект</label>
                        <select class="form-select form-select-sm" id="select2insidemodal" name="project_id" required>
                            <option value="0">Не выбрано</option>
                            @foreach($projects as $project)
                                <option value="{{ $project['id'] }}">{{ $project['project_name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Дата оплаты</label>
                        <input class="form-control form-control-sm" type="date" name="date" value="{{ now()->format('Y-m-d') }}" required>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-4 mb-2">
                                <label for="">Сбер Д</label>
                                <input type="number" name="sber_d" class="form-control form-control-sm" min="0" step="0.01" value="0">
                            </div>
                            <div class="col-4 mb-2">
                                <label for="">Сбер К</label>
                                <input type="number" name="sber_k" class="form-control form-control-sm" min="0" step="0.01" value="0">
                            </div>
                            <div class="col-4 mb-2">
                                <label for="">Приват</label>
                                <input type="number" name="privat" class="form-control form-control-sm" min="0" step="0.01" value="0">
                            </div>
                            <div class="col-4 mb-2">
                                <label for="">ЮМ</label>
                                <input type="number" name="um" class="form-control form-control-sm" min="0" step="0.01" value="0">
                            </div>
                            <div class="col-4 mb-2">
                                <label for="">ВМЗ</label>
                                <input type="number" name="wmz" class="form-control form-control-sm" min="0" step="0.01" value="0">
                            </div>
                            <div class="col-4 mb-2">
                                <label for="">Биржи</label>
                                <input type="number" name="birja" class="form-control form-control-sm" min="0" step="0.01" value="0">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">Комментарий</label>
                        <textarea class="form-control form-control-sm" name="comment" id="" cols="30" rows="3"></textarea>
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
