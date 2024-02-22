<?php

namespace App\Constants;

class NotificationTypeConstants
{
    public const ASSIGNED_PROJECT = 'ASSIGNED_PROJECT'; // назначен проект
    public const CHANGE_PRICE_PROJECT = 'CHANGE_PRICE_PROJECT'; // изменена цена
    public const CHANGE_ARTICLE = 'CHANGE_ARTICLE'; // отредактирована статья
    public const WRITE_TO_CLIENT_WEEK = 'WRITE_TO_CLIENT_WEEK'; // написать клинету, прошла неделя
    public const WRITE_TO_CLIENT_MONTH = 'WRITE_TO_CLIENT_MONTH'; // написать клиенту прошел месяц
    public const PROJECT_PAYMENT = 'PROJECT_PAYMENT'; // уведомление об плате по проекту
    public const DATE_CONTACT_WITH_CLIENT = 'DATE_CONTACT_WITH_CLIENT'; // Дата связи с клиентом
}
