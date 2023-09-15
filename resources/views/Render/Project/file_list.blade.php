@foreach($files as $file)
    <div class="item">
        <a href="{{ request()->root() }}/storage/{{ $file['url'] }}" target="_blank" class="flex-1">
            <div class="file">
                <div class="name">{{ $file['file_name'] }}</div>
                <div class="format">.{{ collect(explode('.', $file['file_name']))->last() }}</div>
            </div>
        </a>
        <div class="delete_file pointer"
             onclick="window.deleteFile({{ $projectId }}, '{{ route('project_file.delete', ['id' => $file['id']]) }}')">
            <img src="{{ asset('img/svg/delete.svg') }}" width="24" alt="delete file">
        </div>
    </div>
@endforeach
