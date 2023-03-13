@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show p-2 text-14">
        @foreach ($errors->all() as $error)
            {{ $error }}<br>
        @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
