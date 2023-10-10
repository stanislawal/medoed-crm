@php
    $assignedProject = $notifications->where('type', \App\Constants\NotificationTypeConstants::ASSIGNED_PROJECT)->toArray();
    $changePriceProject = $notifications->where('type', \App\Constants\NotificationTypeConstants::CHANGE_PRICE_PROJECT)->toArray();
    $changeArticle = $notifications->where('type', \App\Constants\NotificationTypeConstants::CHANGE_ARTICLE)->toArray();
    $writeToClientWeek = $notifications->where('type', \App\Constants\NotificationTypeConstants::WRITE_TO_CLIENT_WEEK)->toArray();
    $writeToClientMonth = $notifications->where('type', \App\Constants\NotificationTypeConstants::WRITE_TO_CLIENT_MONTH)->toArray();
    $projectPayment = $notifications->where('type', \App\Constants\NotificationTypeConstants::PROJECT_PAYMENT)->toArray();
@endphp

<div class="accordion accordion-flush" id="accordion-notification">
    <div class="accordion-item">
        <h2 class="accordion-header" id="flush-headingOne">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#ASSIGNED_PROJECT" aria-expanded="false" aria-controls="flush-collapseOne">
                <div class="icon bg-primary">
                    <i class="fas fa-layer-group text-white"></i>
                </div>
                <span class="ps-2">Назначенные проекты: <strong
                        class="text-primary">{{ count($assignedProject) }}</strong></span>
            </button>
        </h2>
        <div id="ASSIGNED_PROJECT" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
             data-bs-parent="#accordion-notification">
            <div class="accordion-body">
                @forelse($assignedProject as $item)
                    <div class="d-flex notification-item">
                        <div class="icon bg-primary">
                            <i class="fas fa-layer-group text-white"></i>
                        </div>
                        <div class="description">
                            <div class="text-notify">
                                <span>Вам назначен проект:</span>
                                <a href="{{ route('project.edit', ['project' => $item['projects']['id']]) }}"
                                   class="text-primary">{{ $item['projects']['project_name'] ?? ''}}</a>
                                <br>
                                <span>Менеджер: <strong>{{ $item['projects']['project_user']['full_name'] ?? '' }}</strong></span>
                                <div class="time">{{ $item['date_time'] }}</div>
                            </div>
                        </div>
                        <div class="browse"
                             onclick="browseNotification(this, '{{ route('notification.browse', ['id' => $item['id']]) }}')">
                            <i class="fas fa-eye" title="Пометить как прочитанное"></i>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-12 fst-italic w-100 p-3 text-gray">
                        Пусто
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="accordion-item">
        <h2 class="accordion-header" id="flush-headingOne">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#CHANGE_PRICE_PROJECT" aria-expanded="false" aria-controls="flush-collapseOne">
                <div class="icon bg-warning">
                    <i class="fas fa-ruble-sign text-white"></i>
                </div>
                <span class="ps-2">Изменение цены в проекте: <strong
                        class="text-primary">{{ count($changePriceProject) }}</strong></span>
            </button>
        </h2>
        <div id="CHANGE_PRICE_PROJECT" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
             data-bs-parent="#accordion-notification">
            <div class="accordion-body">
                @forelse($changePriceProject as $item)
                    <div class="d-flex notification-item">
                        <div class="icon bg-warning">
                            <i class="fas fa-ruble-sign text-white"></i>
                        </div>
                        <div class="description">
                            <div class="text-notify">
                                <span>Изменение цены заказчика в проекте:</span>
                                <a href="{{ route('project.edit', ['project' => $item['project_id']]) }}"
                                   class="text-primary">{{ $item['projects']['project_name'] ?? null }}</a>
                                <br>
                                <span>Менеджер: <strong>{{ $item['projects']['project_user']['full_name'] ?? '' }}</strong></span>
                            </div>
                            <div class="time">{{ $item['date_time'] }}</div>
                        </div>
                        <div class="browse"
                             onclick="browseNotification(this, '{{ route('notification.browse', ['id' => $item['id']]) }}')">
                            <i class="fas fa-eye" title="Пометить как прочитанное"></i>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-12 fst-italic w-100 p-3 text-gray">
                        Пусто
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="accordion-item">
        <h2 class="accordion-header" id="flush-headingOne">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#CHANGE_ARTICLE" aria-expanded="false" aria-controls="flush-collapseOne">
                <div class="icon bg-warning">
                    <i class="fas fa-pen text-white"></i>
                </div>
                <span class="ps-2">Изменения в базе статей: <strong
                        class="text-primary">{{ count($changeArticle) }}</strong></span>
            </button>
        </h2>
        <div id="CHANGE_ARTICLE" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
             data-bs-parent="#accordion-notification">
            <div class="accordion-body">
                @forelse($changeArticle as $item)
                    <div class="d-flex notification-item">
                        <div class="icon bg-warning">
                            <i class="fas fa-pen text-white"></i>
                        </div>
                        <div class="description">
                            <div class="text-notify">
                                <span>Изменение в базе статей: <br></span>
                                <div> {!! $item['message'] ?? '' !!}</div>
                                <a href="{{ route('article.index', ['article' => $item['articles']['article'] ?? 0]) }}"
                                   class="text-primary">{{ $item['articles']['article'] ?? '' }}</a></div>
                            <div class="time">{{ $item['date_time'] }}</div>
                        </div>
                        <div class="browse"
                             onclick="browseNotification(this, '{{ route('notification.browse', ['id' => $item['id']]) }}')">
                            <i class="fas fa-eye" title="Пометить как прочитанное"></i>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-12 fst-italic w-100 p-3 text-gray">
                        Пусто
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="accordion-item">
        <h2 class="accordion-header" id="flush-headingOne">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#WRITE_TO_CLIENT_WEEK" aria-expanded="false" aria-controls="flush-collapseOne">
                <div class="icon bg-success">
                    <i class="fas fa-users text-white"></i>
                </div>
                <span class="ps-2">Прописать заказчику (прошло 7 дней): <strong
                        class="text-primary">{{ count($writeToClientWeek) }}</strong></span>
            </button>
        </h2>
        <div id="WRITE_TO_CLIENT_WEEK" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
             data-bs-parent="#accordion-notification">
            <div class="accordion-body">
                @forelse($writeToClientWeek as $item)
                    <div class="d-flex notification-item">
                        <div class="icon bg-success">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <div class="description">
                            <div class="text-notify">
                                <span>Необходимо связаться с заказчиком<br> (прошло 7 дней).<br> Проект:</span>
                                <a href="{{ route('project.edit', ['project' => $item['project_id']]) }}"
                                   class="text-primary">{{ $item['projects']['project_name'] ?? null }}</a>
                                <br>
                                <span>Менеджер: <strong>{{ $item['projects']['project_user']['full_name'] ?? '' }}</strong></span>
                                <div class="time">{{ $item['date_time'] }}</div>
                            </div>
                        </div>
                        <div class="browse"
                             onclick="browseNotification(this, '{{ route('notification.browse', ['id' => $item['id']]) }}')">
                            <i class="fas fa-eye" title="Пометить как прочитанное"></i>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-12 fst-italic w-100 p-3 text-gray">
                        Пусто
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="accordion-item">
        <h2 class="accordion-header" id="flush-headingOne">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#WRITE_TO_CLIENT_MONTH" aria-expanded="false" aria-controls="flush-collapseOne">
                <div class="icon bg-success">
                    <i class="fas fa-users text-white"></i>
                </div>
                <span class="ps-2">Прописать заказчику (прошло 30 дней): <strong
                        class="text-primary">{{ count($writeToClientMonth) }}</strong></span>
            </button>
        </h2>
        <div id="WRITE_TO_CLIENT_MONTH" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
             data-bs-parent="#accordion-notification">
            <div class="accordion-body">
                @forelse($writeToClientMonth as $item)
                    <div class="d-flex notification-item">
                        <div class="icon bg-success">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <div class="description">
                            <div class="text-notify">
                                <span>Необходимо связаться с заказчиком<br> (прошло 30 дней).<br> Проект:</span>
                                <a href="{{ route('project.edit', ['project' => $item['project_id']]) }}"
                                   class="text-primary">{{ $item['projects']['project_name'] ?? null }}</a>
                                <br>
                                <span>Менеджер: <strong>{{ $item['projects']['project_user']['full_name'] ?? '' }}</strong></span>
                                <div class="time">{{ $item['date_time'] }}</div>
                            </div>
                        </div>
                        <div class="browse"
                             onclick="browseNotification(this, '{{ route('notification.browse', ['id' => $item['id']]) }}')">
                            <i class="fas fa-eye" title="Пометить как прочитанное"></i>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-12 fst-italic w-100 p-3 text-gray">
                        Пусто
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="accordion-item">
        <h2 class="accordion-header" id="flush-headingOne">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#PROJECT_PAYMENT" aria-expanded="false" aria-controls="flush-collapseOne">
                <div class="icon bg-primary">
                    <i class="fas fa-ruble-sign text-white"></i>
                </div>
                <span class="ps-2">Запросить оплату: <strong
                        class="text-primary">{{ count($projectPayment) }}</strong></span>
            </button>
        </h2>
        <div id="PROJECT_PAYMENT" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
             data-bs-parent="#accordion-notification">
            <div class="accordion-body">
                @forelse($projectPayment as $item)
                    <div class="d-flex notification-item">
                        <div class="icon bg-primary">
                            <i class="fas fa-ruble-sign text-white"></i>
                        </div>
                        <div class="description">
                            <div class="text-notify">
                                <span>Время оплаты по проекту: </span>
                                <a href="{{ route('project.edit', ['project' => $item['project_id']]) }}"
                                   class="text-primary">{{ $item['projects']['project_name'] ?? null }}</a>
                                <br>
                                <span>Менеджер: <strong>{{ $item['projects']['project_user']['full_name'] ?? '' }}</strong></span>
                                <div class="time">{{ $item['date_time'] }}</div>
                            </div>
                        </div>
                        <div class="browse"
                             onclick="browseNotification(this, '{{ route('notification.browse', ['id' => $item['id']]) }}')">
                            <i class="fas fa-eye" title="Пометить как прочитанное"></i>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-12 fst-italic w-100 p-3 text-gray">
                        Пусто
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>


