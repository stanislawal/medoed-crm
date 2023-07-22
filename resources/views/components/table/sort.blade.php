@php
    $rul = "";
    $class = "";
    if(request()->input('sort') !== $column){
        $url = route($routeName, array_merge(request()->all(), ['sort' => $column, 'direction' => 'asc']));
    }else if(request()->input('sort') == $column && request()->input('direction') == 'asc'){
        $class = "sort-asc";
        $url = route($routeName, array_merge(request()->all(), ['sort' => $column, 'direction' => 'desc']));
    }else{
        $class = "sort-desc";
        $url = route($routeName, collect(request()->all())->except(['sort', 'direction'])->toArray());
    }
@endphp

<a href="{{ $url }}">
    <div class="sort {{ $class }}">
        {{ $title }}
    </div>
</a>
