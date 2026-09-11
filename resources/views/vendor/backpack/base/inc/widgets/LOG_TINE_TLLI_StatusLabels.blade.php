<div>
@foreach($widget['values'] as $key => $value)
    <div class="border border-secondary d-inline-block p-1" style="border-radius: 5px;">
        {{ $value }}
    </div>
@endforeach
</div>