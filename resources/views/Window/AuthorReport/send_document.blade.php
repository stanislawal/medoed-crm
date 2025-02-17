<div class="modal fade" id="send_document" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Отправить отчет автору</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="send_document_form" class="mb-2" action="{{ route('report_author.send_file') }}" method="post">
                    @csrf
                    <input type="hidden" name="document_id">
                    <div class="mb-3">
                        <label class="form-label" for="">Почта автора:</label>
                        <input type="email"
                               required name="email_author"
                               class="form-control form-control-sm"
                               value="{{ $user['email_for_doc'] ?? '' }}"
                        >
                    </div>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-success">Отправить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
