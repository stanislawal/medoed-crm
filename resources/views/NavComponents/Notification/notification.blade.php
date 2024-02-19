<div class="offcanvas offcanvas-end" tabindex="-1" id="notificationContainer" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header pb-1">
        <h4 class="offcanvas-title font-weight-bold" id="offcanvasRightLabel">Уведомления</h4>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="py-2 px-3">
        <a href="{{ route('notification.browse_all') }}" class="btn btn-sm btn-primary">Прочитать все</a>
    </div>
    <div class="offcanvas-body notification-list pt-1 ">
        @include('Render.Notifications.notification_list', ['notifications' => $notifications])
    </div>
    <div class="border-top p-3 d-flex justify-content-between align-items-center text-primary">
        <a href="{{ route('notification.index') }}">Перейти ко всем уведомлениям<i class=" ms-2 fa fa-angle-right"></i></a>
    </div>
</div>
