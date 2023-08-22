@extends('layout.markup')
@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection
@section('content')

    <div class="container">

        <div class="col-12 mb-3">
            @include('Answer.custom_response')
            @include('Answer.validator_response')
        </div>

        <h1 class="mb-3 text-center">Форма редактирования проекта</h1>
        <div class="accordion mb-3" id="socialNetworkLink">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button p-2 text-12 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#clients" aria-expanded="false">
                        <strong>Заказчики ({{ count($projectInfo['project_clients']) }})</strong>
                    </button>
                </h2>

                <div id="clients" class="accordion-collapse collapse" style="">
                    <div class="accordion-body">
                        <form class="d-none"></form>
                        @foreach($projectInfo['project_clients'] as $key => $item)
                            <form action="{{ route('client.update', ['client' => $item['id']]) }}" method="POST">
                                @if($key > 0)<hr class="w-100 bg-primary">@endif
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="form-group col-12 col-md-6 col-lg-4 mb-3">
                                        <label class="form-label">Контактное лицо</label>
                                        <input class="form-control form-control-sm" type="text" name="name" value="{{ $item['name'] }}" />
                                    </div>

                                    <div class="form-group col-12 col-md-6 col-lg-4 mb-3">
                                        <label class="form-label">Сфера деятельности</label>
                                        <input class="form-control form-control-sm" type="text" name="scope_work" value="{{ $item['scope_work'] }}" />
                                    </div>

                                    <div class="form-group col-12 col-md-6 col-lg-4 mb-3">
                                        <label class="form-label">Название компании</label>
                                        <input class="form-control form-control-sm" type="text" name="company_name" value="{{ $item['company_name'] }}" />
                                    </div>

                                    <div class="form-group col-12 col-md-6 col-lg-4 mb-3">
                                        <label class="form-label">Сайт</label>
                                        <input class="form-control form-control-sm" type="text" name="site" value="{{ $item['site'] }}" />
                                    </div>

                                    <div class="form-group col-12 col-md-6 col-lg-4 mb-3">
                                        <label class="form-label">Контактная информация</label>
                                        <input class="form-control form-control-sm" type="text" name="contact_info" value="{{ $item['contact_info'] }}" />
                                    </div>

                                    <div class="form-group col-12 col-md-6 col-lg-4 mb-3">
                                        <label class="form-label">Портрет и общая хар-ка</label>
                                        <input class="form-control form-control-sm" type="text" name="characteristic" value="{{ $item['characteristic'] }}" />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 col-md-8 section_socialwork mb-3">
                                        <div class="mb-2">
                                            <label class="form-label">Место ведения диалога</label>
                                            <div class="btn btn-sm btn-primary py-0 px-1 add">Добавить</div>
                                            <input type="hidden" data-id="{{ $key }}" name="socialnetwork_info" value="{{ $item['json'] }}" class="socialnetwork_info">
                                        </div>
                                        <div class="items_socialwork" data-id="{{ $key }}">
                                            @foreach($item['social_network'] as $socialNetworkClientItem)
                                                <div class="input-group mb-3 item">
                                                    <select class="form-select form-select-sm" required onchange="window.write_socialnetwork(this)">
                                                        <option value="">Не выбрано</option>
                                                        @foreach ($socialNetwork as $item)
                                                            <option @if($socialNetworkClientItem['id'] == $item['id']) selected @endif value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                    <>
                                                    <input placeholder="Ник" class="form-control form-control-sm" type="text" value="{{ $socialNetworkClientItem['pivot']['description'] }}" required oninput="window.write_socialnetwork(this)">
                                                    <div class="btn btn-sm btn-danger delete" onclick="window.write_socialnetwork(this)">Удалить</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-sm btn-success">Сохранить</button>
                                </div>
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button p-2 text-12 collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Событие (0)
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div>
                            <form action="">
                                <label for=""></label>
                                <input>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

            </div>
        </div>
        <form action="{{route('project.update', ['project' => $projectInfo['id']])}}" method="POST"
              class="mb-5" data-form-name="edit__project">
            @csrf
            @method('PUT')

            <dic class="d-flex justify-content-end mb-3">
                <div class="btn btn-danger btn-sm mr-3 w-auto" style="display: none;" data-role="cancel"
                     onclick="onEdit('edit__project', true)">Отмена
                </div>
                <button class="btn btn-success btn-sm mr-3 w-auto" style="display: none;">Обновить</button>
                <div class="btn btn-primary btn-sm w-auto" data-role="edit"
                     onclick="onEdit('edit__project', false)">Редактировать
                </div>
            </dic>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Менеджер</label>
                <div class="col-sm-9">
                    <select class="form-select form-select-sm" name="manager_id" disabled>
                        <option value="">Не выбрано</option>
                        @foreach ($managers as $manager)
                            <option @if($manager['id'] == $projectInfo['manager_id']) selected
                                    @endif value="{{$manager['id']}}">{{$manager['full_name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Тема</label>
                <div class="col-sm-9">
                    <select class="form-select form-select-sm" name="theme_id" disabled
                            value="{{ $projectInfo['theme_id'] ?? '' }}">
                        @foreach ($themes as $theme)
                            <option value="{{$theme['id']}}"
                                    @if($theme['id'] == $projectInfo['theme_id'])
                                        selected
                                @endif
                            >{{$theme['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Название проекта</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="project_name" disabled
                           value="{{ $projectInfo['project_name'] ?? '' }}">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Приоритетность</label>
                <div class="col-sm-9">
                    <select class="form-control form-control-sm" title="Пожалуйста, выберите"
                            name="style_id"
                            disabled>
                        @foreach ($style as $item)
                            <option value="{{$item['id']}}"
                                    @if($item['id'] == $projectInfo['style_id']) selected @endif
                            >{{$item['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
{{--            <div class="row mb-3">--}}
{{--                <label class="col-sm-3 col-form-label">Начальный объём проекта</label>--}}
{{--                <div class="col-sm-9">--}}
{{--                    <input type="text" class="form-control form-control-sm" name="total_symbols"--}}
{{--                           disabled value="{{ $projectInfo['total_symbols'] ?? '' }}">--}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Тип задачи</label>
                <div class="col-sm-9">
                    <input type="text" disabled value="{{ $projectInfo['type_task'] ?? '' }}" class="form-control form-control-sm" name="type_task">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Дополнительная информация</label>
                <div class="col-sm-9">
                    <textarea type="text" disabled rows="6" style="resize: both;" class="form-control form-control-sm"  name="dop_info">{{ $projectInfo['dop_info'] ?? '' }}</textarea>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Дата поступления тз</label>
                <div class="col-sm-9">
                    <input type="date" class="form-control form-control-sm" name="start_date_project"
                           disabled value="{{ $projectInfo['start_date_project'] ?? '' }}">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Комментарий</label>
                <div class="col-sm-9">
                    <textarea type="text" rows="4" class="form-control form-control-sm" name="comment"
                              placeholder="Укажите комментарий к проекту"
                              disabled>{{ $projectInfo['comment'] ?? '' }}</textarea>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Сфера бизнеса</label>
                <div class="col-sm-9">
                    <textarea type="text" disabled
                              class="form-control form-control-sm" name="business_area">{{ $projectInfo['business_area'] }}</textarea>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Ссылка на сайт</label>
                <div class="col-sm-9">
                    <input disabled type="text" value="{{ $projectInfo['link_site'] }}"
                           class="form-control form-control-sm" name="link_site">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Перспектива проекта</label>
                <div class="col-sm-9">
                    <textarea disabled type="text"
                              class="form-control form-control-sm" name="project_perspective">{{ $projectInfo['project_perspective'] }}</textarea>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Назначить авторов</label>
                <div class="col-sm-9">
                    <select class="form-control form-control-sm select-2" multiple name="author_id[]"
                            disabled>
                        @foreach ($authors as $author)
                            <option
                                @if(in_array($author['id'], collect($projectInfo['project_author'])->pluck('id')->toArray()))
                                    selected
                                @endif value="{{$author['id']}}">{{$author['full_name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Цена автора</label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input class="form-control form-control-sm" step="0.01" type="number"
                               name="price_author"
                               disabled value="{{ $projectInfo['price_author'] ?? '' }}">
                        <div class="input-group-append input-group-sm">
                            <span class="input-group-text" id="basic-addon2">РУБ</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Цена заказчика</label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input class="form-control form-control-sm" disabled value="{{ $projectInfo['price_client'] ?? '' }}" type="text"
                               name="price_client">
                        <div class="input-group-append">
                            <span class="input-group-text" id="basic-addon2">РУБ</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Состояние проекта</label>
                <div class="col-sm-9">
                    <select class="form-control form-control-sm" name="status_id" disabled>
                        @foreach ($statuses as $status)
                            <option value="{{$status['id']}}"
                                    @if($status['id'] == $projectInfo['status_id'])
                                        selected
                                @endif
                            >{{$status['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr class="bg-primary">

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Цена за 1000 символов</label>
                <div class="col-sm-9">
                    <input type="number" disabled value="{{$projectInfo['price_per']}}"
                           class="form-control form-control-sm" name="price_per">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Как платит</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="pay_info"
                           disabled value="{{ $projectInfo['pay_info'] ?? '' }}">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Сроки оплаты</label>
                <div class="col-sm-9">
                    <input disabled type="text" value="{{$projectInfo['payment_terms']}}"
                           class="form-control form-control-sm" name="payment_terms">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Счёт для оплаты</label>
                <div class="col-sm-9">
                    <input disabled type="text" value="{{$projectInfo['invoice_for_payment']}}"
                           class="form-control form-control-sm" name="invoice_for_payment">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Дата последнего прописывания</label>
                <div class="col-sm-9">
                    <input disabled type="date" value="{{$projectInfo['date_last_change']}}"
                           class="form-control form-control-sm" name="date_last_change">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Дата оплаты</label>
                <div class="col-sm-9">
                    <input disabled type="date" value="{{$projectInfo['date_notification']}}"
                           class="form-control form-control-sm" name="date_notification">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Дни оплаты</label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <div class="w-50">
                            <select class="form-control form-control-sm input-group select-2" multiple name="days[]" disabled>
                                <option value="">Не выбрано</option>
                                @for($i = 1; $i <= 31; $i++)
                                    <option @if(in_array($i, $notifiProject ?? [])) selected @endif  value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="w-50">
                            <select class="form-control form-control-sm select-2" multiple name="weekday[]" disabled>
                                <option value="">Не выбрано</option>
                                @foreach(\App\Helpers\DateHelper::getWeekdayList() as $key => $value)
                                    <option @if(in_array((string)$key, $notifiProject)) selected @endif  value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="bg-primary">

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Заказчики</label>
                <div class="col-sm-9">
                    <select class="form-control form-control-sm select-2" multiple
                            title="Пожалуйста, выберите"
                            name="client_id[]" disabled>
                        <option value="">Не выбрано</option>
                        @foreach ($clients as $client)
                            <option value="{{$client['id']}}"
                                    @if(in_array($client['id'], collect($projectInfo['project_clients'])->pluck('id')->toArray()))
                                        selected
                                @endif
                            >{{$client['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{--            <div class="row mb-3">--}}
            {{--                <label class="col-sm-3 col-form-label">Портрет заказчика</label>--}}
            {{--                <div class="col-sm-9">--}}
            {{--                    <input type="text" class="form-control form-control-sm" required value="{{ $projectInfo['characteristic'] }}" name="characteristic">--}}
            {{--                </div>--}}
            {{--            </div>--}}

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Договор</label>
                <div class="col-sm-9">
                    <select class="form-select form-select-sm select-contract" name="contract"
                            disabled>
                        <option value="1" @if($projectInfo['contract'] == true) selected @endif>Да
                        </option>
                        <option value="0" @if($projectInfo['contract'] == false) selected @endif>Нет
                        </option>
                    </select>
                    <input type="text"
                           @if(!(boolean)$projectInfo['contract']) style="display: none;" @endif
                           class="form-control input-contract mt-2 form-control-sm"
                           placeholder="Вставьте ссылку на договор"
                           disabled
                           value="{{$projectInfo['contract_exist']}}" name="contract_exist">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Настроение</label>
                <div class="col-sm-9">
                    <select class="form-control form-control-sm" name="mood_id" disabled
                            value="{{ $projectInfo['mood_id'] ?? '' }}">
                        @foreach ($moods as $mood)
                            <option value="{{$mood['id']}}">{{$mood['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <dic class="d-flex justify-content-end">
                <div class="btn btn-danger btn-sm mr-3 w-auto" style="display: none;" data-role="cancel"
                     onclick="onEdit('edit__project', true)">Отмена
                </div>
                <button class="btn btn-success btn-sm mr-3 w-auto" style="display: none;">Обновить</button>
                <div class="btn btn-primary btn-sm w-auto" data-role="edit"
                     onclick="onEdit('edit__project', false)">Редактировать
                </div>
            </dic>
        </form>
    </div>

@endsection

@section('custom_js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{asset('js/select2.js')}}"></script>
    <script>
        $('.select-contract').change(function () {
            if ($(this).val() === '0') {
                $('.input-contract').hide();

            } else {
                $('.input-contract').show();
            }
        });

        $('.section_socialwork').on('click', '.delete', function(){
            const id = $(this).parent('.item').parent('.items_socialwork').attr('data-id');
            $(this).parent('div').remove();
            window.save(id);
        })

        $('.section_socialwork .add').click(function(){
            const itemsSocialwork = $(this).parent('div').next('.items_socialwork');

            $.ajax({
                url: '{{ route("socialnetwork.get_select") }}',
                method: 'GET',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            }).done((res) => {
                itemsSocialwork.append(res.html);
            })
        });

        window.write_socialnetwork = function(el){
            const id = $(el).parent('.item').parent('.items_socialwork').attr('data-id');
            window.save(id);
        }

        window.save = function(id) {
            var array = [];

            $('.items_socialwork[data-id="'+id+'"] .item').each(function(i, item){
                array.push({
                    'socialnetrowk_id' : $(this).children('select').val(),
                    'link' : $(this).children('input').val()
                })
            });

            $('.socialnetwork_info[data-id="'+id+'"]').val(JSON.stringify(array));
        };
    </script>
@endsection
