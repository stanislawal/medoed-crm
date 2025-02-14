<div class="modal fade" id="create_file_report" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Создание файла отчета</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-search-article" class="mb-2">
                    <input type="hidden" name="author_id" value="{{ $authorId }}">
                    <div class="input-group mb-3">
                        <input type="date" name="date_from" class="form-control" placeholder="Дата с" required
                               value="{{ now()->startOfMonth()->format('Y-m-d') }}">
                        <input type="date" name="date_to" class="form-control" placeholder="Дата по" required
                               value="{{ now()->format('Y-m-d') }}">
                        <button class="btn btn-sm btn-primary">Показать</button>
                    </div>
                </form>

                <div class="mb-2">Всего записей: <span id="total-article">0</span></div>

                <form id="form-table-article" action="{{ route('report_author.create_document') }}" method="POST">
                    @csrf
                    <input type="hidden" name="author_id" value="{{ $authorId }}">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <td class="text-center">
                                <div>
                                    <input class="form-check-input ml-0 main-checkbox" type="checkbox" value="">
                                </div>
                            </td>
                            <td class="text-center">ID</td>
                            <td>Статья</td>
                            <td class="text-center">Дата</td>
                        </tr>
                        </thead>
                        <tbody id="tbody">
                        </tbody>
                    </table>
                    <div class="mt-3 d-flex justify-content-end">
                        <button class="btn btn-sm btn-success">Сгенерировать отчет</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
