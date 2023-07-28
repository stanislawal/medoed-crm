<div class="offcanvas offcanvas-end" tabindex="-1" id="notificationContainer" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
        <h4 class="offcanvas-title font-weight-bold" id="offcanvasRightLabel">Уведомления</h4>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body notification-list">
        @include('Render.Notifications.notification_list', ['notifications' => $notifications])
    </div>
    <div class="border-top p-3 d-flex justify-content-between align-items-center text-primary">
        <a href="{{ route('notification.index') }}">Перейти ко всем уведомлениям<i class=" ms-2 fa fa-angle-right"></i></a>
    </div>
</div>
