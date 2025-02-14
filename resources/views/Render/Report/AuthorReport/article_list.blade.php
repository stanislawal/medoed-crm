@foreach($list as $item)
    <tr data-id="{{ $item['id'] }}" @if($item['inDocument']->isNotEmpty()) style="background-color: #31ce3666;" @endif>
        <td class="text-center">
            <div>
                <input class="form-check-input ml-0" name="article_ids[]" type="checkbox" value="{{ $item['id'] }}">
            </div>
        </td>
        <td class="text-center">{{ $item['id'] }}</td>
        <td>
            {{ $item['article'] }}
            @if($item['inDocument']->isNotEmpty())
                <i class="fas fa-file-pdf text-dark ms-2"
                   style="cursor: pointer"
                   title="{{ $item['inDocument']->pluck('file_name')->implode(', ') }}"
                ></i>
            @endif
        </td>
        <td class="text-center">{{ \Carbon\Carbon::parse($item['created_at'])->format('d.m.Y') }}</td>
    </tr>
@endforeach
