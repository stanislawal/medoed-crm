<div class="modal fade" id="create_event" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Добавить новое событие</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('project-event.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="project_id" value="{{ $project_id }}">

                    <div class="form-group">
                        <label for="">Дата</label>
                        <input class="form-control form-control-sm" type="date" name="date" value="{{ now()->format('Y-m-d') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="">Описанеи события</label>
                        <textarea class="form-control form-control-sm" required name="comment" cols="30" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-sm btn-success">Создать</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
