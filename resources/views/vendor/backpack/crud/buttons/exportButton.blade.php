@if ($crud->hasAccess('update'))
    <div class="dt-buttons btn-group d-xs-block d-sm-inline-block d-md-inline-block d-lg-inline-block mr-2" style="vertical-align: top;">
        <div class="btn-group" style="margin-left:2px">
            <button class="btn btn-secondary buttons-collection buttons-colvis btn-sm" type="button" onclick="window.location.href='{{ asset($crud->route.'/export') }}'+window.location.search">Exportar</button>
        </div>
    </div>
@endif