@if(session("success") )
    <div class="alert alert-success py-2 text-14">{{ session("success") }}
    </div>
@elseif(session("error"))
    <div class="alert alert-danger py-2 text-14">{{ session("error") }}
    </div>
@endif

<div class="alert-ajax ajax-success" style="display: none"></div>
<div class="alert-ajax ajax-error" style="display: none"></div>
