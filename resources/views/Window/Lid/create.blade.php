<div class="modal fade" id="create_lid" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Добавить нового лида</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('lid.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="">Рекламная компания</label>
                        <select name="advertising_company" required class="form-select form-select-sm">
                            <option value="">Не выбрано</option>
                            @foreach($advertisingCompany as $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Дата поступления</label>
                        <input class="form-control form-control-sm" type="date" name="date_receipt" value="{{ now()->format('Y-m-d') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="">Ресурс</label>
                        <select name="resource_id" required class="form-select form-select-sm">
                            <option value="">Не выбрано</option>
                            @foreach($resources as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Имя/Ссылка</label>
                        <input class="form-control form-control-sm" type="text" name="name_link" required>
                    </div>

                    <div class="form-group">
                        <label for="">Статус</label>
                        <select name="lid_status_id" required class="form-select form-select-sm select2-with-color">
                            <option value="">Не выбрано</option>
                            @foreach($lidStatuses as $item)
                                <option value="{{ $item->id }}" data-color="{{ $item->color ?? '' }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Состояние</label>
                        <textarea class="form-control form-control-sm" name="state" cols="30" rows="3"></textarea>
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
