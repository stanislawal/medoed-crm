<form action="{{ route('lid.update', ['lid' => $lid->id]) }}" method="post">
    @csrf
    @method('PATCH')
    <div class="mb-3">
        <label for="" class="form-label">Рекламная компания</label>
        <select class="form-select form-select-sm" name="advertising_company" id="">
            <option value="">Не выбрано</option>
            @foreach($advertisingCompany as $item)
                <option
                    value="{{ $item }}" {{ $lid->advertising_company == $item ? 'selected' : '' }}>{{ $item }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label for="" class="form-label">Дата поступления лида</label>
        <input type="date" class="form-control form-control-sm" name="date_receipt"
               value="{{ $lid->date_receipt ?? '' }}">
    </div>
    <div class="mb-3">
        <label for="" class="form-label">Ресурс</label>
        <select class="form-select form-select-sm" name="resource_id" id="">
            <option value="">Не выбрано</option>
            @foreach($resources as $item)
                <option
                    value="{{ $item->id }}" {{ $lid->resource_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label for="" class="form-label">Имя/Ссылка</label>
        <input type="text" class="form-control form-control-sm" name="name_link"
               value="{{ $lid->name_link ?? '' }}">
    </div>
    <div class="mb-3">
        <label for="" class="form-label">Место ведения диалога</label>
        <select class="form-select form-select-sm" name="location_dialogue_id" id="">
            <option value="">Не выбрано</option>
            @foreach($locationDialogues as $item)
                <option
                    value="{{ $item->id }}" {{ $lid->location_dialogue_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
    `
    <div class="mb-3">
        <label for="" class="form-label">Ссылка на лида</label>
        <input type="text" class="form-control form-control-sm" name="link_lid" value="{{ $lid->link_lid ?? '' }}">
    </div>
    <div class="mb-3">
        <label for="" class="form-label">Услуга</label>
        <select class="form-select form-select-sm" name="service_id" id="">
            <option value="">Не выбрано</option>
            @foreach($services as $item)
                <option
                    value="{{ $item->id }}" {{ $lid->service_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label for="" class="form-label">Созвон</label>
        <select class="form-select form-select-sm" name="call_up_id" id="">
            <option value="">Не выбрано</option>
            @foreach($callUps as $item)
                <option
                    value="{{ $item->id }}" {{ $lid->call_up_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label for="" class="form-label">Дата и время созвона</label>
        <input type="text" class="form-control form-control-sm" name="date_time_call_up"
               value="{{ $lid->date_time_call_up ?? '' }}">
    </div>
    <div class="mb-3">
        <label for="" class="form-label">Аудит</label>
        <select class="form-select form-select-sm" name="audit_id" id="">
            <option value="">Не выбрано</option>
            @foreach($audits as $item)
                <option
                    value="{{ $item->id }}" {{ $lid->audit_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label for="" class="form-label">Задача специалиста</label>
        <select class="form-select form-select-sm" name="specialist_task_id" id="">
            <option value="">Не выбрано</option>
            @foreach($specialistTasks as $item)
                <option
                    value="{{ $item->id }}" {{ $lid->specialist_task_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label for="" class="form-label">Дата передан</label>
        <input type="date" class="form-control form-control-sm" name="transfer_date"
               value="{{ $lid->transfer_date ?? '' }}">
    </div>
    <div class="mb-3">
        <label for="" class="form-label">Дата принят</label>
        <input type="date" class="form-control form-control-sm" name="date_acceptance"
               value="{{ $lid->date_acceptance ?? '' }}">
    </div>
    <div class="mb-3">
        <label for="" class="form-label">Дата сделан</label>
        <input type="date" class="form-control form-control-sm" name="ready_date"
               value="{{ $lid->ready_date ?? '' }}">
    </div>
    <div>
        <label for="" class="form-label">Специалист</label>
        <select class="form-select form-select-sm" name="specialist_user_id" id="">
            <option value="">Не выбрано</option>
            @foreach($specialistUsers as $item)
                <option
                    value="{{ $item->id }}" {{ $lid->specialist_user_id == $item->id ? 'selected' : '' }}>{{ $item->full_name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <div class="form-check">
            <label class="form-check-label">
                <input class="form-check-input" type="checkbox"
                       value="1" name="write_lid" {{ $lid->write_lid ? 'checked' : '' }}>
                <span class="form-check-sign">Прописка</span>
            </label>
        </div>
    </div>
    <div class="mb-3">
        <label for="" class="form-label">Статус</label>
        <select class="form-select form-select-sm select2-with-color" name="lid_status_id" id="">
            <option value="">Не выбрано</option>
            @foreach($lidStatuses as $item)
                <option
                    value="{{ $item->id }}"
                    {{ $lid->lid_status_id == $item->id ? 'selected' : '' }}
                    data-color="{{ $item->color ?? '' }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label for="" class="form-label"></label>
        <textarea class="form-control form-control-sm" required name="state" cols="30"
                  rows="3">{{ $lid->state ?? '' }}</textarea>
    </div>
    <div class="mb-3">
        <label for="" class="form-label">Ссылка на сайт</label>
        <input type="text" class="form-control form-control-sm" name="link_to_site"
               value="{{ $lid->link_to_site ?? '' }}">
    </div>
    <div class="mb-3">
        <label for="" class="form-label">Регион</label>
        <input type="text" class="form-control form-control-sm" name="region" value="{{ $lid->region ?? '' }}">
    </div>
    <div class="mb-3">
        <label for="" class="form-label">Цена</label>
        <input type="number" class="form-control form-control-sm" name="price" value="{{ $lid->price ?? '' }}">
    </div>
    <div class="mb-3">
        <label for="" class="form-label">Сфера бизнеса</label>
        <input type="text" class="form-control form-control-sm" name="business_are"
               value="{{ $lid->business_are ?? '' }}">
    </div>

    <div class="d-flex justify-content-end">
        <button class="btn btn-sm btn-success">Сохранить</button>
    </div>
</form>
