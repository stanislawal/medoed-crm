<div class="modal fade" id="create_article" data-bs-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Добавить новую статью</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('article.store')}}" method="post">

                    @csrf

                    <div class="w-100 row m-0 p-2">
                        <div class="form-group col-12 ">
                            <label for="" class="form-label">Менеджер</label>
                            <select required class="form-select form-select-sm" name="manager_id">
                                <option value="">Не выбрано</option>
                                @foreach ($managers as $manager)
                                    <option value="{{$manager['id']}}" @if($manager['id'] === \App\Helpers\UserHelper::getUserId()) selected @endif>{{$manager['full_name']}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-12 ">
                            <label for="" class="form-label">Назначить авторов</label>
                            <select class="form-control select-2" multiple name="author_id[]">
                                <option value="">Не выбрано</option>
                                @foreach ($authors as $author)
                                    <option value="{{$author['id']}}">{{$author['full_name']}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-12 ">
                            <label for="" class="form-label">Цена автора</label>
                            <div class="selectgroup w-100">
                                <label class="selectgroup-item">
                                    <input type="radio" name="is_fixed_price_author" value="0" class="selectgroup-input"
                                           @if(($item['is_fixed_price_author'] ?? false) == false) checked @endif>
                                    <span class="selectgroup-button">Цена за 1000 с.</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="is_fixed_price_author" value="1" class="selectgroup-input"
                                           @if(($item['is_fixed_price_author'] ?? false) == true) checked @endif>
                                    <span class="selectgroup-button">Цена за 1 шт.</span>
                                </label>
                            </div>
                            <div class="input-group mb-3">
                                <input class="form-control form-control-sm" type="number" step="0.1" min="0.1"
                                       name="price_author">
                                <div class="input-group-append input-group-sm">
                                    <span class="input-group-text" id="basic-addon2">РУБ</span>
                                </div>
                            </div>

                            <div class="d-flex">
                                <span class="badge text-bg-secondary me-2 ms-0 pointer"
                                      onclick="window.setPrice(this, 'price_author')">100</span>
                                <span class="badge text-bg-secondary me-2 ms-0 pointer"
                                      onclick="window.setPrice(this, 'price_author')">150</span>
                                <span class="badge text-bg-secondary me-2 ms-0 pointer"
                                      onclick="window.setPrice(this, 'price_author')">200</span>
                                <span class="badge text-bg-secondary me-2 ms-0 pointer"
                                      onclick="window.setPrice(this, 'price_author')">250</span>
                                <span class="badge text-bg-secondary me-2 ms-0 pointer"
                                      onclick="window.setPrice(this, 'price_author')">300</span>
                                <span class="badge text-bg-secondary me-2 ms-0 pointer"
                                      onclick="window.setPrice(this, 'price_author')">350</span>
                                <span class="badge text-bg-secondary me-2 ms-0 pointer"
                                      onclick="window.setPrice(this, 'price_author')">400</span>
                            </div>
                        </div>

                        <div class="form-group col-12 ">
                            <label for="" class="form-label">Название проекта</label>
                            <select class="form-control border form-control-sm select-2-modal"
                                    title="Пожалуйста, выберите"
                                    name="project_id">
                                <option value="" selected>Не выбрано</option>
                                @foreach( $projects as $item)
                                    <option
                                        value="{{$item['id']}}">{{$item['project_name']}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-12 ">
                            <label for="" class="form-label">Цена заказчика</label>
                            <div class="selectgroup w-100">
                                <label class="selectgroup-item">
                                    <input type="radio" name="is_fixed_price_client" value="0" class="selectgroup-input"
                                           @if(($item['is_fixed_price_client'] ?? false) == false) checked @endif>
                                    <span class="selectgroup-button">Цена за 1000 с.</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="is_fixed_price_client" value="1" class="selectgroup-input"
                                           @if(($item['is_fixed_price_client'] ?? false) == true) checked @endif>
                                    <span class="selectgroup-button">Цена за 1 шт.</span>
                                </label>
                            </div>
                            <div class="input-group mb-3">
                                <input class="form-control form-control-sm" type="number" step="0.1"
                                       min="0.1"
                                       name="price_client">
                                <div class="input-group-append input-group-sm">
                                    <span class="input-group-text" id="basic-addon2">РУБ</span>
                                </div>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Флажок по умолчанию
                                </label>
                            </div>
                        </div>

                        <div class="form-group col-12 ">
                            <label for="" class="form-label">Название статьи</label>
                            <input class="form-control form-control-sm" type="text" name="article" required>
                        </div>

                        <div class="form-group col-12 ">
                            <label for="" class="form-label">Ссылка на текст</label>
                            <input class="form-control form-control-sm" type="text" name="link_text">
                        </div>

                        <div class="form-group col-12 ">
                            <label for="" class="form-label">ЗБП</label>
                            <div class="input-group mb-3">
                                <input class="form-control form-control-sm" type="number" step="0.1"
                                       min="0.1"
                                       name="without_space">
                            </div>
                        </div>


                        <div class="form-group col-12 ">
                            <label for="" class="form-label">Редактор</label>
                            <select class="form-control select-2" multiple name="redactor_id[]">
                                <option value="">Не выбрано</option>
                                @foreach ($authors as $author)
                                    <option value="{{$author['id']}}">{{$author['full_name']}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-12 ">
                            <label for="" class="form-label">Цена редактора</label>
                            <div class="selectgroup w-100">
                                <label class="selectgroup-item">
                                    <input type="radio" name="is_fixed_price_redactor" value="0"
                                           class="selectgroup-input"
                                           @if(($item['is_fixed_price_redactor'] ?? false) == false) checked @endif>
                                    <span class="selectgroup-button">Цена за 1000 с.</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="is_fixed_price_redactor" value="1"
                                           class="selectgroup-input"
                                           @if(($item['is_fixed_price_redactor'] ?? false) == true) checked @endif>
                                    <span class="selectgroup-button">Цена за 1 шт.</span>
                                </label>
                            </div>
                            <div class="input-group mb-3">
                                <input class="form-control form-control-sm" type="number" step="0.1" min="0.1"
                                       name="price_redactor">
                                <div class="input-group-append input-group-sm">
                                    <span class="input-group-text" id="basic-addon2">РУБ</span>
                                </div>
                            </div>

                            <div class="d-flex">
                                <span class="badge text-bg-secondary me-2 ms-0 pointer"
                                      onclick="window.setPrice(this, 'price_redactor')">100</span>
                                <span class="badge text-bg-secondary me-2 ms-0 pointer"
                                      onclick="window.setPrice(this, 'price_redactor')">150</span>
                                <span class="badge text-bg-secondary me-2 ms-0 pointer"
                                      onclick="window.setPrice(this, 'price_redactor')">200</span>
                                <span class="badge text-bg-secondary me-2 ms-0 pointer"
                                      onclick="window.setPrice(this, 'price_redactor')">250</span>
                                <span class="badge text-bg-secondary me-2 ms-0 pointer"
                                      onclick="window.setPrice(this, 'price_redactor')">300</span>
                                <span class="badge text-bg-secondary me-2 ms-0 pointer"
                                      onclick="window.setPrice(this, 'price_redactor')">350</span>
                                <span class="badge text-bg-secondary me-2 ms-0 pointer"
                                      onclick="window.setPrice(this, 'price_redactor')">400</span>
                            </div>
                        </div>

                        <div class="form-group col-12 ">
                            <label for="" class="form-label">Валюта</label>
                            <div class="input-group mb-3">
                                <select class="form-control form-control-sm" name="id_currency">
                                    <option value="1">RUB</option>
                                    @foreach ($currency ?? '' as $item)
                                        <option value="{{$item['id']}}">{{$item['currency']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-12">
                            <button class="btn btn-success btn-sm mr-3 w-auto">Создать</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
