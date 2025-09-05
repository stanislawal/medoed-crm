<?php

use App\Helpers\UserHelper;
use App\Http\Controllers\Article\ArticleController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Lid\LidController;
use App\Http\Controllers\Lid\LidSpecialistStatusController;
use App\Http\Controllers\Lid\LidStatusController;
use App\Http\Controllers\Lid\LocationDialogueController;
use App\Http\Controllers\Lid\ResourceController;
use App\Http\Controllers\Lid\ServiceController;
use App\Http\Controllers\Lid\SpecialistTaskController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Option\StatusPaymentController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\PaymentAuthor\PaymentAuthorController;
use App\Http\Controllers\Project\FilesProjectController;
use App\Http\Controllers\Project\MonthlyAccrualController;
use App\Http\Controllers\Project\ProjectEventController;
use App\Http\Controllers\Rate\RateController;
use App\Http\Controllers\Report\ReportAuthorController;
use App\Http\Controllers\Report\ReportClientController;
use App\Http\Controllers\Report\ReportServiceController;
use App\Http\Controllers\Report\ReportRedactorController;
use App\Http\Controllers\Report\WorkloadController;
use App\Http\Controllers\Service\ProjectServiceController;
use App\Http\Controllers\Service\ServiceTypeController;
use App\Http\Controllers\Service\SpecialistController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Option\StyleController;
use App\Http\Controllers\Option\ThemeController;
use App\Http\Controllers\Option\StatusController;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Controllers\Option\SocialNetworkController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


#Главная страница (пустая)

Route::get('/', function () {

    if (is_null(UserHelper::getUserId())) {
        return redirect()->route('login.index');
    } else {
        return redirect()->route('home');
    }
});

#Авторизация
Route::resource('login', AuthController::class)->only(['index', 'store']);
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'is_work'])->group(function () {

    # Главная страница
    Route::get('/home', function () {
        return view('home');
    })->name('home');

    # Проекты (projects)
    Route::resource('project', ProjectController::class)->except('show');
    Route::post('project/partial-update/{id}', [ProjectController::class, 'partialUpdate'])->name('project.partial_update');
    Route::get('project-destroy/{project}', [ProjectController::class, 'destroy'])->name('project.destroy')->middleware('role:Администратор');
    Route::get('project/delete-checkboxes', [ProjectController::class, 'deleteCheckbox'])->name('project.delete_checkboxes');

    # Статьи (articles)
    Route::get('article/change_ignore/{id}', [ArticleController::class, 'changeIgnoreArticle'])->name('change_ignore_article');
    Route::resource('article', ArticleController::class);
    Route::get('article-destroy/{article}', [ArticleController::class, 'destroy'])->name('article.destroy');


    # Заказчики (clients)
    Route::resource('client', ClientController::class)->except('show');
    Route::get('client-destroy/{client}', [ClientController::class, 'destroy'])->name('client.destroy');
    Route::get('socialnetwork/get-select', [SocialNetworkController::class, 'getSelect'])->name('socialnetwork.get_select');

    # only admin
    Route::middleware('role:Администратор')->group(function () {
        # Пользователи (users)
        Route::post('/user/partial-update/{id}', [UserController::class, 'partialUpdate'])->name('user.partial_update');
        Route::resource('user', UserController::class)->except('destroy');

        // Добавление статуса для проекта
        Route::resource('add_option_status', StatusController::class);
        Route::get('add_option_status-destroy/{status}', [StatusController::class, 'destroy'])->name('add_option_status.destroy');
        Route::get('add_option_status-destroy/{id}', [StatusController::class, 'update'])->name('add_option_status.update');

        // Добавить статус оплаты проекта
        Route::resource('status_payment', StatusPaymentController::class)->only(['index', 'store']);
        Route::get('/status_payment-destroy/{id}', [StatusPaymentController::class, 'destroy'])->name('status_payment.destroy');

        Route::resource('add_option_theme', ThemeController::class);
        Route::get('add_option_theme-destroy/{theme}', [ThemeController::class, 'destroy'])->name('add_option_theme.destroy');

        Route::resource('add_option_style', StyleController::class);
        Route::get('add_option_style-destroy/{style}', [StyleController::class, 'destroy'])->name('add_option_style.destroy');

        Route::resource('add_option_socialnetwork', SocialNetworkController::class);
        Route::get('add_option_socialnetwork-destroy/{socialnetwork}', [SocialNetworkController::class, 'destroy'])->name('add_option_socialnetwork.destroy');

        Route::prefix('book')->group(function () {
            Route::resource('resource', ResourceController::class)->only(['index', 'store', 'destroy']);
            Route::resource('location-dialogue', LocationDialogueController::class)->only(['index', 'store', 'destroy']);
            Route::resource('service', ServiceController::class)->only(['index', 'store', 'destroy']);
            Route::resource('specialist-task', SpecialistTaskController::class)->only(['index', 'store', 'destroy']);
            Route::resource('lid-status', LidStatusController::class)->only(['index', 'store', 'destroy']);
            Route::resource('lid-status', LidStatusController::class)->only(['index', 'store', 'destroy']);
            Route::resource('lid-specialist-status', LidSpecialistStatusController::class)->only(['index', 'store', 'destroy']);
            Route::resource('specialist', SpecialistController::class)->only(['index', 'store', 'destroy']);
            Route::resource('service-type', ServiceTypeController::class)->only(['index', 'store', 'destroy']);
        });


        #----------------------------------------ВАЛЮТА----------------------------------------
        Route::prefix('rate')->group(function () {
            Route::get('/', [RateController::class, 'index'])->name('rate.index');
            Route::post('/update', [RateController::class, 'update'])->name('rate.update');
        });
        #----------------------------------------ВАЛЮТА----------------------------------------
    });
    #----------------------------------------ОТЧЕТЫ----------------------------------------
    Route::get('report_author/get-article-list', [ReportAuthorController::class, 'getArticleList'])->name('report_author.get_article_list');
    Route::post('report_author/create-document', [ReportAuthorController::class, 'createDocument'])->name('report_author.create_document');
    Route::delete('report_author/delete_document/{id}', [ReportAuthorController::class, 'delete'])->name('report_author.delete_document');
    Route::post('report_author/send_file', [ReportAuthorController::class, 'sendFile'])->name('report_author.send_file');

    Route::resource('report_client', ReportClientController::class);
    Route::resource('report_author', ReportAuthorController::class);
    Route::resource('report_redactor', ReportRedactorController::class);
    Route::get('report_workload', [WorkloadController::class, 'index'])->name('report_workload');
    Route::get('report_client_project/{project}', [ReportClientController::class, 'show'])->name('client_project.show');

    Route::get('report-service', [ReportServiceController:: class, 'index'])->name('report_service.index');
    Route::get('report-service/{project_id}', [ReportServiceController::class, 'show'])->name('report_service.show');
    #----------------------------------------ОТЧЕТЫ----------------------------------------

    #----------------------------------------Файлы----------------------------------------
    Route::post('project/file/upload', [FilesProjectController::class, 'saveFile'])->name('project_file.upload');
    Route::post('project/file/delete/{id}', [FilesProjectController::class, 'deleteFile'])->name('project_file.delete');
    #----------------------------------------Файлы----------------------------------------

    #----------------------------------------ОПЛАТА----------------------------------------
    Route::prefix('payment')->group(function () {
        // страница создания оплаты, и список (для менеджера)
        Route::get('/create', [PaymentController::class, 'create'])->name('payment.create');
        // возвращает html select статей указанного проекта
        Route::get('/select-article/{id}', [PaymentController::class, 'selectArticle'])->name('payment.select_article');
        // создание оплаты
        Route::post('/store', [PaymentController::class, 'store'])->name('payment.store');
        // обновление оплаты
        Route::post('/update/{id}', [PaymentController::class, 'update'])->name('payment.update');
        // страница модерации заявок на оплату (для администратора)
        Route::get('moderation', [PaymentController::class, 'moderation'])->name('payment.moderation')->middleware('role:Администратор');
        // удаление заявки только для админа
        Route::get('/delete/{id}', [PaymentController::class, 'delete'])->name('payment.delete')->middleware('role:Администратор');
    });
    #----------------------------------------ОПЛАТА----------------------------------------

    #----------------------------------------ОПЛАТА АВТОРУ----------------------------------------
    Route::middleware('role:Администратор')->prefix('payment-author')->group(function () {
        // создать оплату
        Route::post('/create', [PaymentAuthorController::class, 'create'])->name('author_payment.create');
        // редактировать оплату
        Route::post('/update/{id}', [PaymentAuthorController::class, 'update'])->name('author_payment.update');
        // удалить оплату
        Route::get('/delete/{id}', [PaymentAuthorController::class, 'delete'])->name('author_payment.delete');
    });
    #----------------------------------------ОПЛАТА АВТОРУ----------------------------------------

    #----------------------------------------УВЕДОМЛЕНИЯ----------------------------------------
    Route::prefix('notification')->group(function () {
        Route::get('browse/all', [NotificationController::class, 'browseAll'])->name('notification.browse_all');
        Route::get('browse/{id}', [NotificationController::class, 'browse'])->name('notification.browse');
        Route::get('browse/all/{type}', [NotificationController::class, 'browseInType'])->name('notification.browse_in_type');
        Route::get('get-html', [NotificationController::class, 'getHtml'])->name('notification.get_html');
        Route::get('list', [NotificationController::class, 'index'])->name('notification.index');
    });
    #----------------------------------------УВЕДОМЛЕНИЯ----------------------------------------

    #----------------------------------------СОБЫТИЕ ДЛЯ ПРОЕКТА----------------------------------------
    Route::resource('project-event', ProjectEventController::class)->only(['store', 'destroy']);
    #----------------------------------------СОБЫТИЕ ДЛЯ ПРОЕКТА----------------------------------------

    #----------------------------------------ПОЛЬЗОВАТЕЛИ ОНЛАЙН----------------------------------------
    Route::post('user-active', [UserController::class, 'userActive']);
    #----------------------------------------ПОЛЬЗОВАТЕЛИ ОНЛАЙН----------------------------------------

    #-----------------------------------ЭКСПОРТ В ЭКСЕЛЬ----------------------------------------
    Route::get('report/client/export', [ReportClientController::class, 'exportAll'])->name('report.client_all');
    Route::get('report/client/export/item/{id}', [ReportClientController::class, 'exportItem'])
        ->name('report.client_item');
    #-----------------------------------ЭКСПОРТ В ЭКСЕЛЬ----------------------------------------

    #-----------------------------------ЛИДЫ----------------------------------------
    Route::middleware('role:Администратор|Реклама')->group(function () {
        Route::post('lid/partial-update/{id}', [LidController::class, 'ajaxUpdate'])->name('lid.partial_update');
        Route::get('lid/get-by-id-html', [LidController::class, 'getByIdHtml'])->name('lid.get_by_id_html');
        Route::resource('lid', LidController::class)->only(['index', 'store', 'update', 'destroy']);
    });
    #-----------------------------------ЛИДЫ----------------------------------------

    #-----------------------------------УСЛУГИ----------------------------------------
    Route::middleware('role:Администратор|Менеджер')->group(function () {
        Route::resource('project-service', ProjectServiceController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::post('monthly-accrual', [MonthlyAccrualController::class, 'updateOrCreate'])->name('monthly_accrual.update');
    });
    #-----------------------------------УСЛУГИ----------------------------------------
});
