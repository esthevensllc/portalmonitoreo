@if ($crud->hasAccess('update'))
<div class="dt-buttons btn-group d-xs-block d-sm-inline-block d-md-inline-block d-lg-inline-block" style="vertical-align: top;">
        <div class="btn-group" style="margin-left:2px">
            <button id="addPeriodo" class="btn btn-secondary buttons-collection buttons-colvis btn-sm" type="button" onclick="openModal()">Agregar Periodo</button>
        </div>
</div>
@endif

@push('after_scripts')
<script>
    function openModal(){
        $('#addPeriodoModal').modal('show');
    }
    $('#addPeriodoForm').submit(function(e){
        e.preventDefault();
        var periodo = $("#selPeriodoCV").val();
        var _token = $("input[name=_token]").val();
        //if(grupo_motivo!=0 && detalle_motivo!="" && accion_solucion!="" && isValidDate(fecha_solucion)){
            $.ajax({
                url: "/portalregulatorio{{$crud->route}}/addPeriodo",
                type: "POST",
                data:{
                    periodo:periodo,
                    _token:_token
                },
                success:function(response){
                    if(response){
                        $('#addPeriodoModal').modal('hide');
                        new Noty({
                            text: "Periodo agregado correctamente",
                            type: "success"
                        }).show();
                        //toastr.info('El registro fue actualizado correctamente.', 'Actualizar Registro', {timeOut:3000});
                        $('#crudTable').DataTable().ajax.reload();
                    }
                }
            });
        //}
    });
</script>
@endpush