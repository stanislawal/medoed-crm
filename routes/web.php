<?php

use App\Helpers\UserHelper;
use App\Http\Controllers\Article\ArticleController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Option\StatusPaymentController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Rate\RateController;
use App\Http\Controllers\Report\ReportAuthorController;
use App\Http\Controllers\Report\ReportClientController;
use App\Http\Controllers\User\UserController;
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

Route::middleware('auth')->group(function () {

    # Главная страница
    Route::get('/home', function () {
        return view('home');
    })->name('home');

    # Проекты (projects)
    Route::resource('project', ProjectController::class)->except('show');
    Route::post('project/partial-update/{id}', [ProjectController::class, 'partialUpdate'])->name('project.partial_update');
    Route::get('project-destroy/{project}', [ProjectController::class, 'destroy'])->name('project.destroy');
    Route::get('project/delete-checkboxes', [ProjectController::class, 'deleteCheckbox'])->name('project.delete_checkboxes');

    # Статьи (articles)
    Route::resource('article', ArticleController::class);
    Route::get('article-destroy/{article}', [ArticleController::class, 'destroy'])->name('article.destroy');


    # Заказчики (clients)
    Route::resource('client', ClientController::class)->except('show');
    Route::get('client-destroy/{client}', [ClientController::class, 'destroy'])->name('client.destroy');
    Route::get('socialnetwork/get-select', [SocialNetworkController::class, 'getSelect'])->name('socialnetwork.get_select');

    # only admin
    Route::middleware('role:Администратор')->group(function () {
        # Пользователи (users)
        Route::resource('user', UserController::class)->except('destroy');
        Route::get('user-destroy/{user}', [UserController::class, 'destroy'])->name('user.destroy');

        // Добавление статуса для проекта
        Route::resource('add_option_status', StatusController::class);
        Route::get('add_option_status-destroy/{status}', [StatusController::class, 'destroy'])->name('add_option_status.destroy');

        // Добавить статус оплаты проекта
        Route::resource('status_payment', StatusPaymentController::class)->only(['index', 'store']);
        Route::get('/status_payment-destroy/{id}', [StatusPaymentController::class, 'destroy'])->name('status_payment.destroy');

        Route::resource('add_option_theme', ThemeController::class);
        Route::get('add_option_theme-destroy/{theme}', [ThemeController::class, 'destroy'])->name('add_option_theme.destroy');

        Route::resource('add_option_style', StyleController::class);
        Route::get('add_option_style-destroy/{style}', [StyleController::class, 'destroy'])->name('add_option_style.destroy');

        Route::resource('add_option_socialnetwork', SocialNetworkController::class);
        Route::get('add_option_socialnetwork-destroy/{socialnetwork}', [SocialNetworkController::class, 'destroy'])->name('add_option_socialnetwork.destroy');
        # Заказчики (clients)

        #----------------------------------------ОТЧЕТЫ----------------------------------------
        Route::resource('report_client', ReportClientController::class );
        Route::resource('report_author', ReportAuthorController::class );
        Route::get('report_client_project/{project}', [ReportClientController::class, 'show'])->name('client_project.show');

        #----------------------------------------ОТЧЕТЫ----------------------------------------


        #----------------------------------------ВАЛЮТА----------------------------------------
        Route::prefix('rate')->group(function (){
            Route::get('/', [RateController::class, 'index'])->name('rate.index');
            Route::post('/update', [RateController::class, 'update'])->name('rate.update');
        });
        #----------------------------------------ВАЛЮТА----------------------------------------
    });

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

    Route::post('user-active', [UserController::class, 'userActive']);
});
