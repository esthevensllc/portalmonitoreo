@if ($crud->hasAccess('update'))
    <div class="dt-buttons btn-group d-xs-block d-sm-inline-block d-md-inline-block d-lg-inline-block" style="vertical-align: top;">
        <div class="btn-group" style="margin-left:2px">
            <button class="btn btn-secondary buttons-collection buttons-colvis btn-sm" type="button" onclick="window.location.href='{{ asset('acceso_4g_movil_provision/export') }}'">Exportar</button>
        </div>
    </div>
@endif