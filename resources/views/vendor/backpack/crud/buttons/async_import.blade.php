@if ($crud->hasAccess('update'))
    <div class="dt-buttons btn-group d-xs-block d-sm-inline-block d-md-inline-block d-lg-inline-block" style="vertical-align: top;">
        <div class="btn-group" style="margin-left:2px">
            <button id="btnExportPoligons" class="btn btn-secondary buttons-collection buttons-colvis btn-sm" type="button" onclick="window.open('{{asset($crud->route)}}_template/export'+window.location.search)">Plantilla</button>
        </div>
        <div class="btn-group" style="margin-right:2px; vertical-align: top;">
            <form action="{{ url($crud->route.'/import') }}" method="post" enctype="multipart/form-data" id="formLoadTemplate">
                @csrf
                <label class="btn btn-secondary buttons-collection buttons-colvis btn-sm" style="cursor:pointer"> 
                    <input class="btn-importData" type="file" accept=".xlsx" value="Importar" name="fileImport" style="display:none">
                    <span>
                    <i class="la la-upload"></i>
                    Importar
                    </span>
                </label>
            </form>
        </div>
    </div>
@endif

@push('after_scripts')
<script>
    // location.href ="http://www.pagina1.com";
    $("input.btn-importData").change(function () {
        // window.location.search = '?import=true';
		$("#formLoadTemplate").submit();
	});
</script>
@endpush