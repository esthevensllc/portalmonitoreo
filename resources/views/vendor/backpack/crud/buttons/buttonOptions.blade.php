@if ($crud->hasAccess('update'))
    @if (!isset($crud->buttonOptions))
        <div class="dt-buttons btn-group d-xs-block d-sm-inline-block d-md-inline-block d-lg-inline-block mr-2" style="vertical-align: top;">
            <div class="btn-group" style="margin-left:2px">
                <button class="btn btn-secondary buttons-collection buttons-colvis btn-sm" type="button" onclick="window.location.href='{{ asset($crud->route.'/export') }}'+window.location.search">Exportar</button>
            </div>               
        </div>
    @endif
    @if (isset($crud->buttonOptions))
        @if (array_key_exists("title", $crud->buttonOptions))
        <h5>{{ $crud->buttonOptions['title']['h5'] }}</h5>
        @endif
        <div class="dt-buttons btn-group d-xs-block d-sm-inline-block d-md-inline-block d-lg-inline-block mr-2" style="vertical-align: top;">
            @if (array_key_exists("export", $crud->buttonOptions))
            <div class="btn-group" style="margin-left:2px">
                <button class="btn btn-secondary buttons-collection buttons-colvis btn-sm" type="button" onclick="window.location.href='{{ asset($crud->route.'/export') }}'+window.location.search">Exportar</button>
            </div>
            @endif
            @if (array_key_exists("plantilla", $crud->buttonOptions))
            <div class="btn-group" style="margin-left:2px">
                <button class="btn btn-secondary buttons-collection buttons-colvis btn-sm" type="button" onclick="window.location.href='{{ asset($crud->route.'/export') }}'+window.location.search">Plantilla</button>
            </div>
            @endif
        </div>
            @if (array_key_exists("asyncImport", $crud->buttonOptions))
                <div class="btn-group" style="margin-right:2px; vertical-align: top;">
                    <form action="{{ $crud->buttonOptions['asyncImport']['url'] }}" method="post" enctype="multipart/form-data" id="formLoadTemplate">
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
            @endif
    @endif
@endif

@push('after_scripts')
@if (isset($crud->buttonOptions))
<script>
    @if (array_key_exists("asyncImport", $crud->buttonOptions))
    $("#formLoadTemplate").on("submit", function(e){
        e.preventDefault();
        console.log("submit");

        let data = new FormData(e.target);

        $("#spinnerData").show();

        fetch($(e.target).attr('action'), {
            method: 'POST',
            body: data,
        })
        .then(async(response) => {
            if(!response.ok){
                let _json = await response.json();
                throw new Error(_json.message);
            }
            return response;
        })
        .then(response => response.json())
        .then(response => {
            $("#spinnerData").hide();
            $('#crudTable').DataTable().ajax.reload();
            if(response.passes){
                alert(response.message);
            }else{
                alert(response.message);
            }
        })
        .catch(error => {
            $("#spinnerData").hide();
            alert(error);
        });
    });

    $("input.btn-importData").change(function () {
        // window.location.search = '?import=true';
		$("#formLoadTemplate").submit();
        $(this).val("");
	});
    @endif
</script>
@endif
@endpush