<?php

use App\Helpers\UserHelper;
use App\Http\Controllers\Article\ArticleController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Rate\RateController;
use App\Http\Controllers\Report\ReportAuthorController;
use App\Http\Controllers\Report\ReportClientController;
use App\Http\Controllers\Report\ReportController;
use App\Http\Controllers\Tables\TableProject;
use App\Http\Controllers\User\UserController;
use PhpOption\Option;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestShowController;
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

    # Статьи (articles)
    Route::resource('article', ArticleController::class);
    Route::get('article-destroy/{article}', [ArticleController::class, 'destroy'])->name('article.destroy');

    # only admin
    Route::middleware('role:Администратор')->group(function () {
        # Пользователи (users)
        Route::resource('user', UserController::class)->except('destroy');
        Route::get('user-destroy/{user}', [UserController::class, 'destroy'])->name('user.destroy');

        # Добавление пунктов в select
        Route::resource('add_option_status', StatusController::class);
        Route::get('add_option_status-destroy/{status}', [StatusController::class, 'destroy'])->name('add_option_status.destroy');

        Route::resource('add_option_theme', ThemeController::class);
        Route::get('add_option_theme-destroy/{theme}', [ThemeController::class, 'destroy'])->name('add_option_theme.destroy');

        Route::resource('add_option_style', StyleController::class);
        Route::get('add_option_style-destroy/{style}', [StyleController::class, 'destroy'])->name('add_option_style.destroy');

        Route::resource('add_option_socialnetwork', SocialNetworkController::class);
        Route::get('add_option_socialnetwork-destroy/{socialnetwork}', [SocialNetworkController::class, 'destroy'])->name('add_option_socialnetwork.destroy');
        # Заказчики (clients)
        Route::resource('client', ClientController::class)->except('show');
        Route::get('client-destroy/{client}', [ClientController::class, 'destroy'])->name('client.destroy');

        # Отчеты (reports)
        Route::resource('report_client', ReportClientController::class );
        Route::resource('report_author', ReportAuthorController::class );

        #Валюта (currency)
        Route::prefix('rate')->group(function (){
            Route::get('/', [RateController::class, 'index'])->name('rate.index');
            Route::post('/update', [RateController::class, 'update'])->name('rate.update');
        });

    });
});


