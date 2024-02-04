@foreach($files as $file)
    <div class="item">
        <a href="{{ request()->root() }}/storage/{{ $file['url'] }}" target="_blank" class="flex-1">
            <div class="file">
                <div class="name">
                    <div>
                        <span class="text-gray text-14">Файл:</span> {{ $file['file_name'] }}
                    </div>
                    <div>
                        <span class="text-gray text-14">Комментарий:</span> {!! $file['comment'] ?? '-' !!}
                    </div>
                </div>
                <div class="format">.{{ collect(explode('.', $file['file_name']))->last() }}</div>
            </div>
        </a>
        <div class="delete_file pointer"
             onclick="window.deleteFile({{ $id }}, '{{ route('project_file.delete', ['id' => $file['id']]) }}')">
            <img src="{{ asset('img/svg/delete.svg') }}" width="24" alt="delete file">
        </div>
    </div>
@endforeach
