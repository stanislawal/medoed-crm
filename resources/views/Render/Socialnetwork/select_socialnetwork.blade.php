<div class="input-group mb-3 item">
    <select class="form-select form-select-sm" required onchange="window.write_socialnetwork(this)">
        <option value="">Не выбрано</option>
        @foreach ($socialnetworks as $item)
            <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
        @endforeach
    </select>
    <input class="form-control form-control-sm" placeholder="Ник" type="text" required oninput="window.write_socialnetwork(this)">
    <div class="btn btn-sm btn-danger delete" onclick="window.write_socialnetwork(this)">Удалить</div>
</div>
