@foreach($userActive as $item)
    <div class="user py-2 px-3">
        <a href="{{ route('user.edit', ['user' => $item['user']['id']]) }}" target="_blank" class="d-flex align-items-center">
            <img src="{{ asset('img/user.png') }}" class="d-block" width="40" alt="">
            <div class="ms-3 flex-grow-1">
                <div class="name">{{ $item['user']['full_name'] }}</div>
                <div class="role @if(collect($item['user']['roles'])->first()['name'] == 'Администратор') text-danger @endif" >{{ collect($item['user']['roles'])->first()['name'] }}</div>
            </div>
            <div class=" mx-4">
                <i class="fas fa-chevron-circle-right text-20"></i>
            </div>
        </a>
    </div>
@endforeach
