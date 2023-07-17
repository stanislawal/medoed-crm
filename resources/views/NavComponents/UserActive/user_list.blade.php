@foreach($userActive as $item)
    <div class="user mb-3">
        <a href="#" class="d-flex align-items-center">
            <div class="p-2" style="background-color: #dbdbdb; border-radius: 360px">
                <i class="icon-user text-24 text-dark"></i>
            </div>
            <div class="ms-4">
                <div class="name">{{ $item['user']['full_name'] }}</div>
                <div class="role">{{ collect($item['user']['roles'])->first()['name'] }}</div>
            </div>
        </a>
    </div>
@endforeach
