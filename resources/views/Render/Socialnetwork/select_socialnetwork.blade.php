<div class="input-group input-group-sm mb-3 item">
    <input class="form-check-input m-0" type="checkbox"
           style="margin: 8px 12px 0 0!important;"
           name="view"
           onclick="window.write_socialnetwork(this)"
    >
    <select class="form-select form-select-sm" required onchange="window.write_socialnetwork(this)">
        <option value="">Не выбрано</option>
        @foreach ($socialnetworks as $item)
            <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
        @endforeach
    </select>
    <input class="form-control form-control-sm" name="description" placeholder="Ник" type="text" required oninput="window.write_socialnetwork(this)">
    <div class="btn btn-sm btn-danger delete" onclick="window.write_socialnetwork(this)">Удалить</div>
</div>
