<div class="d-inline-block ml-3 sel-crud">
    <select class="form-control filterTbl">
    @foreach($widget['values'] as $key => $value)
        <option value="{{$widget['options'][$key]}}" @if($widget['options'][$key] == $widget['selected']) selected @endif data-val="">{{$value}}</option>        
    @endforeach
    </select>
</div>