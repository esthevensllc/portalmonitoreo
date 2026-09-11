@extends(backpack_view('blank'))

@php
    use Backpack\CRUD\app\Library\Widget;

    Widget::add([ 
            'type'    => 'div',
            'class'   => 'row',
            'content' => [
                
                [
                    'type'     => 'view',
                    'view'     => 'widgets.tables',
                ],

                [ 'type' => 'chart', 'controller' => \App\Http\Controllers\Admin\Charts\CVRESUMENChartController::class, 'wrapper' => ['class'=> 'col-md-6'] , 'content' => ['header' => 'RECUENTO DE CCPP %']],

             ]           
        ])->to('after_content');
    
@endphp

@section('content')

<h1 style="text-align:center">Resumen General</h1>

<form>
    <div class="form-row">
        <div class="form-group col-md-6">
        <label for="periodos">Seleccione Procedimiento:</label>
        <select class="form-control" id="selectProcedimiento">
        <option>Todos</option>
            @foreach($arrProcedimiento as $procedimiento)   
                <option @if(app('request')->input('procedimiento') == $procedimiento) selected @endif>{{$procedimiento}}</option>     
            @endforeach
        </select>
        </div>
        <div class="form-group col-md-6">
        <label for="tecnologia">Seleccione Tecnología:</label>
        <select class="form-control" id="selectTec">
            <option @if(app('request')->input('tecno') == 'Todos') selected @endif>Todos</option>
            <option @if(app('request')->input('tecno') == '2G') selected @endif>2G</option>
            <option @if(app('request')->input('tecno') == '3G') selected @endif>3G</option>
        </select>
    </div>
  </div>
</form>

@endsection

@section('after_scripts')
<script>

    $(document).ready(function () {
        $("#selectProcedimiento").change(function () {
            ActualizarChart($("#selectProcedimiento").val(), $("#selectTec").val());
        });
        $("#selectTec").change(function () {
            ActualizarChart($("#selectProcedimiento").val(), $("#selectTec").val());
        });
    });

    function ActualizarChart(proced,tecno){
        window.location.href = 'http://172.17.27.157/portalregulatorio/cv_resumen?procedimiento='+proced+'&tecno='+tecno;
    }

</script>
@endsection