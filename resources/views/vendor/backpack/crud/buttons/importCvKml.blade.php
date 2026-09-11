@if ($crud->hasAccess('update'))
    <form action="{{ url($crud->route.'/'.$entry->getKey().'/subirKml') }}" method="post" enctype="multipart/form-data" id="formLoadTemplate" style="display: inline-grid;">
        @csrf
        <label class="btn btn-secondary buttons-collection buttons-colvis btn-sm" style="cursor:pointer"> 
            <input class="btn-importData" type="file" value="Importar" name="fileImport" style="display:none">
            <span>
            <i class="la la-upload"></i>
            Subir KML
            </span>
        </label>
    </form>
@endif

@push('after_scripts')
<script>
    $("input.btn-importData").change(function () {
		$("#formLoadTemplate").submit();
	});
</script>
@endpush