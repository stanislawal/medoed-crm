@extends('layout.layout')

@section('title')
    @yield('')
@endsection

@section('html')
    <div class="wrapper">
        <div class="main-header">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="blue">
                <a href="{{ route('home') }}" class="logo d-flex align-items-center">
                    <h3 class="text-white m-auto">CRM</h3>
                </a>
                <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse"
                        data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon">
						<i class="icon-menu"></i>
					</span>
                </button>
                <button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
                <div class="nav-toggle">
                    <button class="btn btn-toggle toggle-sidebar">
                        <i class="icon-menu"></i>
                    </button>
                </div>
            </div>
            <!-- End Logo Header -->

            <!-- Navbar Header -->
            <nav class="navbar navbar-header navbar-expand-lg" data-background-color="blue2">
                <div class="container-fluid">
                    <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
                        <a href="{{route('logout')}}">
                            <button class="btn btn-sm btn-warning" style="color:black!important;"><i
                                    class="fas fa-sign-out-alt pr-2"></i>Выход
                            </button>
                        </a>
                    </ul>
                </div>
            </nav>
            <!-- End Navbar -->
        </div>
        <!-- Sidebar -->
        <div class="sidebar sidebar-style-2">
            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    <div class="user">
                        <div class="info">
                            <a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
                                @auth
                                    <span>
                                        {{ auth()->user()->full_name }}
                                        <span class="user-level">{{\App\Helpers\UserHelper::getRoleName()}}</span>
                                    </span>
                                @endauth

                                @guest
                                    <div>
                                        <a class="btn btn-sm btn-primary" href="{{route('login')}}">Войти</a>
                                    </div>
                                @endguest
                            </a>
                        </div>
                    </div>
                    <ul class="nav nav-primary">
                        <li class="nav-section">
							<span class="sidebar-mini-icon">
								<i class="fa fa-ellipsis-h"></i>
							</span>
                        </li>
                        <li class="nav-item">
                            <a data-toggle="collapse" href="#base">
                                <i class="fas fa-layer-group"></i>
                                <p>Проекты</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="base">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="{{ route('project.index') }}">
                                            <span class="sub-item">База проектов</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('project.create') }}">
                                            <span class="sub-item">Добавить проект</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        @role('Администратор')
                        <li class="nav-item">
                            <a data-toggle="collapse" href="#sidebarLayouts">
                                <i class="fas fa-th-list"></i>
                                <p>Заказчики</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="sidebarLayouts">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="{{ route('client.index') }}">
                                            <span class="sub-item">База заказчиков</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('client.create') }}">
                                            <span class="sub-item">Добавить заказчика</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a data-toggle="collapse" href="#user">
                                <i class="fas fa-users"></i>
                                <p>Пользователи</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="user">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="{{route('user.index')}}">
                                            <span class="sub-item">База пользователей</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('user.create')}}">
                                            <span class="sub-item">Добавить пользователя</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a data-toggle="collapse" href="#forms">
                                <i class="fas fa-pen-square"></i>
                                <p>Справочник</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="forms">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="{{route('add_option_status.index')}}">
                                            <span class="sub-item">Состояния</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('add_option_theme.index')}}">
                                            <span class="sub-item">Темы</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('add_option_style.index')}}">
                                            <span class="sub-item">Типы текста</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('add_option_socialnetwork.index')}}">
                                            <span class="sub-item">Соц. сети</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        @endrole

                        @hasanyrole('Администратор|Менеджер')
                        <li class="nav-item">
                            <a data-toggle="collapse" href="#charts">
                                <i class="far fa-chart-bar"></i>
                                <p>Статьи</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="charts">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="{{route('article.index')}}">
                                            <span class="sub-item">База статей</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('article.create')}}">
                                            <span class="sub-item">Добавить статью</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        @endhasanyrole

                        @role('Администратор')
                        <li class="nav-item">
                            <a data-toggle="collapse" href="#result">
                                <i class="fas fa-book-open"></i>
                                <p>Своды</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="result">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="{{route('report_client.index')}}">
                                            <span class="sub-item">Заказчики</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('report_author.index')}}">
                                            <span class="sub-item">Авторы</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a  href="{{route('rate.index')}}">
                                <i class="fas fa-dollar-sign"></i>
                                <p>Курс валют</p>
                            </a>
                        </li>
                        @endrole

                    </ul>
                </div>
            </div>
        </div>
        <!-- End Sidebar -->
        <div class="main-panel">
            <div class="content">
                <div class="page-inner">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
@endsection

