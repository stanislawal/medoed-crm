@extends('layout.markup')
@section('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endsection
@section('content')
    <div>
        <div>
            @include('Answer.custom_response')
            @include('Answer.validator_response')
        </div>

        <div class="w-100 shadow border rounded text-white p-3 mb-3">
            <form action="{{ route('notification.index') }}" method="get" class="check__field">
                @csrf
                <div class="row">
                    <div class="col-12 col-md-4 col-lg-3">
                        <select name="type" id="" class="form-select form-select-sm">
                            <option value="">Все типы</option>
                            <option
                                @if(request()->type == \App\Constants\NotificationTypeConstants::ASSIGNED_PROJECT) selected
                                @endif
                                value="{{ \App\Constants\NotificationTypeConstants::ASSIGNED_PROJECT }}">Назначен проект
                            </option>
                            <option
                                @if(request()->type == \App\Constants\NotificationTypeConstants::CHANGE_PRICE_PROJECT) selected
                                @endif
                                value="{{ \App\Constants\NotificationTypeConstants::CHANGE_PRICE_PROJECT }}">Изменение
                                цены в проекте
                            </option>
                            <option
                                @if(request()->type == \App\Constants\NotificationTypeConstants::CHANGE_ARTICLE) selected
                                @endif
                                value="{{ \App\Constants\NotificationTypeConstants::CHANGE_ARTICLE }}">Изменения в базе
                                статей
                            </option>
                            <option
                                @if(request()->type == \App\Constants\NotificationTypeConstants::WRITE_TO_CLIENT_WEEK) selected
                                @endif
                                value="{{ \App\Constants\NotificationTypeConstants::WRITE_TO_CLIENT_WEEK }}">Связаться с
                                клиентов (7 дней)
                            </option>
                            <option
                                @if(request()->type == \App\Constants\NotificationTypeConstants::WRITE_TO_CLIENT_MONTH) selected
                                @endif
                                value="{{ \App\Constants\NotificationTypeConstants::WRITE_TO_CLIENT_MONTH }}">Связаться
                                с клиентов (30 дней)
                            </option>

                            <option
                                @if(request()->type == \App\Constants\NotificationTypeConstants::PROJECT_PAYMENT) selected
                                @endif
                                value="{{ \App\Constants\NotificationTypeConstants::PROJECT_PAYMENT }}">Оплата
                            </option>
                        </select>
                    </div>

                    <div class="col-12 col-md-4 col-lg-3 d-flex align-items-center">
                        <select name="is_viewed" class="form-select form-select-sm">
                            <option value="">Все</option>
                            <option @if(request()->is_viewed == '1') selected @endif value="1">Только прочитанные
                            </option>
                            <option @if(request()->is_viewed == '0') selected @endif value="0">Только непрочитанные
                            </option>
                        </select>
                    </div>

                    <div class="col-12 col-md-4 col-lg-3">
                        <button class="btn btn-sm btn-success">Искать</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="w-100">
            <div class="card shadow border bg-white rounded">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex">
                            <h4 class="card-title"><i class="fa fa-bell text-18 pe-2"></i>Уведомления</h4>
                            @if(!empty(request()->type))
                                <a href="{{ route('notification.browse_in_type', ['type' => request()->type ?? '']) }}"
                                   class="btn btn-sm btn-primary ml-3">Прочитать все</a>
                            @endif
                        </div>
                        <div>Всего записей: <strong>{{ $notifications->total() }}</strong></div>
                    </div>
                </div>
                <div class="card-body">

                    <div class="w-100 d-flex justify-content-center mb-3">
                        {{ $notifications->appends(request()->input())->links('vendor.pagination.custom')  }}
                    </div>

                    @forelse($notifications as $item)
                        @switch($item['type'])

                            @case('ASSIGNED_PROJECT')

                                <div class="d-flex notification-item @if(!$item['is_viewed']) not-viewed @endif">
                                    <div class="icon bg-primary">
                                        <i class="fas fa-layer-group text-white"></i>
                                    </div>
                                    <div class="description">
                                        <div class="text-notify">
                                            <span>Вам назначен проект:</span>
                                            <a href="{{ route('project.edit', ['project' => $item['project_id']]) }}"
                                               class="text-primary">{{ $item['projects']['project_name'] }}</a>
                                        </div>
                                        <div class="time">{{ $item['date_time'] }}</div>
                                    </div>
                                    @if(!$item['is_viewed'])
                                        <a href="{{ route('notification.browse', ['id' => $item['id']]) }}">
                                            <div class="browse">
                                                <i class="fas fa-eye" title="Пометить как прочитанное"></i>
                                            </div>
                                        </a>
                                    @endif
                                </div>

                                @break

                            @case('CHANGE_PRICE_PROJECT')

                                <div class="d-flex notification-item @if(!$item['is_viewed']) not-viewed @endif">
                                    <div class="icon bg-warning">
                                        <i class="fas fa-ruble-sign text-white"></i>
                                    </div>
                                    <div class="description">
                                        <div class="text-notify">
                                            <span>Изменение цены заказчика в проекте:</span>
                                            <a href="{{ route('project.edit', ['project' => $item['project_id']]) }}"
                                               class="text-primary">{{ $item['projects']['project_name'] }}</a>
                                        </div>
                                        <div class="time">{{ $item['date_time'] }}</div>
                                    </div>
                                    @if(!$item['is_viewed'])
                                        <a href="{{ route('notification.browse', ['id' => $item['id']]) }}">
                                            <div class="browse">
                                                <i class="fas fa-eye" title="Пометить как прочитанное"></i>
                                            </div>
                                        </a>
                                    @endif
                                </div>

                                @break

                            @case('CHANGE_ARTICLE')

                                <div class="d-flex notification-item @if(!$item['is_viewed']) not-viewed @endif">
                                    <div class="icon bg-warning">
                                        <i class="fas fa-pen text-white"></i>
                                    </div>
                                    <div class="description">
                                        <div class="text-notify">
                                            <span>Изменение в базе статей: <br></span>
                                            <div> {!! $item['message'] !!}</div>
                                            <a href="{{ route('article.index', ['article' => $item['article_id']]) }}"
                                               class="text-primary">{{ $item['articles']['article'] ?? '' }}</a></div>
                                        <div class="time">{{ $item['date_time'] }}</div>
                                    </div>
                                    @if(!$item['is_viewed'])
                                        <a href="{{ route('notification.browse', ['id' => $item['id']]) }}">
                                            <div class="browse">
                                                <i class="fas fa-eye" title="Пометить как прочитанное"></i>
                                            </div>
                                        </a>
                                    @endif
                                </div>

                                @break

                            @case('WRITE_TO_CLIENT_WEEK')

                                <div class="d-flex notification-item @if(!$item['is_viewed']) not-viewed @endif">
                                    <div class="icon bg-success">
                                        <i class="fas fa-users text-white"></i>
                                    </div>
                                    <div class="description">
                                        <div class="text-notify">
                                            <span>Необходимо связаться с заказчиком (прошло 7 дней). Проект:</span>
                                            <a href="{{ route('project.edit', ['project' => $item['project_id']]) }}"
                                               class="text-primary">{{ $item['projects']['project_name'] ?? null }}</a>
                                            <div class="time">{{ $item['date_time'] }}</div>
                                        </div>
                                    </div>
                                    @if(!$item['is_viewed'])
                                        <a href="{{ route('notification.browse', ['id' => $item['id']]) }}">
                                            <div class="browse">
                                                <i class="fas fa-eye" title="Пометить как прочитанное"></i>
                                            </div>
                                        </a>
                                    @endif
                                </div>

                                @break

                            @case('WRITE_TO_CLIENT_MONTH')

                                <div class="d-flex notification-item @if(!$item['is_viewed']) not-viewed @endif">
                                    <div class="icon bg-success">
                                        <i class="fas fa-users text-white"></i>
                                    </div>
                                    <div class="description">
                                        <div class="text-notify">
                                            <span>Необходимо связаться с заказчиком (прошло 30 дней). Проект:</span>
                                            <a href="{{ route('project.edit', ['project' => $item['project_id']]) }}"
                                               class="text-primary">{{ $item['projects']['project_name'] ?? null }}</a>
                                            <div class="time">{{ $item['date_time'] }}</div>
                                        </div>
                                    </div>
                                    @if(!$item['is_viewed'])
                                        <a href="{{ route('notification.browse', ['id' => $item['id']]) }}">
                                            <div class="browse">
                                                <i class="fas fa-eye" title="Пометить как прочитанное"></i>
                                            </div>
                                        </a>
                                    @endif
                                </div>

                                @break

                            @case('PROJECT_PAYMENT')

                                <div class="d-flex notification-item @if(!$item['is_viewed']) not-viewed @endif">
                                    <div class="icon bg-primary">
                                        <i class="fas fa-ruble-sign text-white"></i>
                                    </div>
                                    <div class="description">
                                        <div class="text-notify">
                                            <span>Время оплаты по проекту: </span>
                                            <a href="{{ route('project.edit', ['project' => $item['project_id']]) }}"
                                               class="text-primary">{{ $item['projects']['project_name'] ?? null }}</a>
                                            <div class="time">{{ $item['date_time'] }}</div>
                                        </div>
                                    </div>
                                    @if(!$item['is_viewed'])
                                        <a href="{{ route('notification.browse', ['id' => $item['id']]) }}">
                                            <div class="browse">
                                                <i class="fas fa-eye" title="Пометить как прочитанное"></i>
                                            </div>
                                        </a>
                                    @endif
                                </div>

                                @break

                        @endswitch
                    @empty
                        <div class="text-14 fst-italic text-gray text-center">
                            Нет уведомлений
                        </div>
                    @endforelse

                    <div class="w-100 d-flex justify-content-center mt-3">
                        {{ $notifications->appends(request()->input())->links('vendor.pagination.custom')  }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
@endsection
