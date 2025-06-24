<div class="modal fade" id="create_project_service" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Добавить новую услугу</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('project-service.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Проект <span class="text-danger">*</span></label>
                        <select class="form-select form-select-sm select-2-modal" name="project_id" required>
                            <option value="">Не выбрано</option>
                            @foreach($projects as $item)
                                <option
                                    value="{{ $item->id }}">{{ $item->project_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Отдел <span class="text-danger">*</span></label>
                        <select class="form-select form-select-sm select2-with-color" name="service_type_id" required>
                            <option value="">Не выбрано</option>
                            @foreach($service_type as $item)
                                <option data-color="{{ $item->color }}" value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Услуга <span class="text-danger">*</span></label>
                        <input class="form-control form-control-sm" type="text" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="">Специалисты</label>
                        <select class="form-select form-select-sm select2-with-color" multiple name="specialist_service_id[]">
                            @foreach($specialists as $item)
                                <option data-color="{{ $item->color }}" value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Общая сумма договора <span class="text-danger">*</span></label>
                        <input class="form-control form-control-sm" type="number" name="all_price" required>
                    </div>

                    <div class="form-group">
                        <label for="">Начислено в этом месяце <span class="text-danger">*</span></label>
                        <input class="form-control form-control-sm" type="number" name="accrual_this_month" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Задача <span class="text-danger">*</span></label>
                        <select class="form-select form-select-sm" name="task" required>
                            <option value="">Не выбрано</option>
                            <option>Разовая</option>
                            <option>Сопровождение</option>
                        </select>
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
