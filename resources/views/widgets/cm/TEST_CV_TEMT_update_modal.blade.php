@includeWhen(!empty($widget['wrapper']), 'backpack::widgets.inc.wrapper_start')
@php
	$actividades = \App\Modules\KPI_CM\Actividades\Repository\CM_Actividad::get();
	$estados = \App\Modules\KPI_CM\Estados\Repository\CM_Estado::get();
	$booleanState = \App\Modules\Shared\BooleanState\Repository\BooleanState::list();
@endphp
<style>
	.cont_fluid__open_modal{
		display: contents !important;
	}
</style>
<div 
	@if (count($widget) > 2)
	    @foreach ($widget as $attribute => $value)
	        @if (is_string($attribute) && $attribute!='content' && $attribute!='type')
	            {{ $attribute }}="{{ $value }}"
	        @endif
	    @endforeach
	@endif
	>

	@if (isset($widget['content']))
		@include('backpack::inc.widgets', [ 'widgets' => $widget['content'] ])
	@endif
	<div class="modal fade" id="modal_update" style="display: none; padding-right: 17px; z-index: 100000;" aria-modal="true">
		<div class="modal-dialog modal-md">
		  <div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Actualizar CCS-CV-TEMT</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form  enctype="multipart/form-data" id="update_form">
					<input type="hidden" name="id" id="u_id">
					<span class="invalid-feedback d-block mb-0" id="u_e_id"></span>
					<div class="row">
						<div class="col-6 form-group">
							<label for="">Encargados</label>
							<input type="text" class="form-control form-control-sm" name="encargados" id="u_encargados">
							<span class="invalid-feedback d-block mb-0" id="u_e_encargados"></span>
						</div>
						<div class="col-6 form-group">
							<label for="">Región</label>
							<input type="text" class="form-control form-control-sm" name="region" id="u_region">
							<span class="invalid-feedback d-block mb-0" id="u_e_region"></span>
						</div>
						<div class="col-4 form-group">
							<label for="">Ubigeo</label>
							<input type="text" class="form-control form-control-sm" name="ubigeo" id="u_ubigeo">
							<span class="invalid-feedback d-block mb-0" id="u_e_ubigeo"></span>
						</div>
						<div class="col-4 form-group">
							<label for="">Departamento</label>
							<input type="text" class="form-control form-control-sm" name="departamento" id="u_departamento">
							<span class="invalid-feedback d-block mb-0" id="u_e_departamento"></span>
						</div>
						<div class="col-4 form-group">
							<label for="">Provincia</label>
							<input type="text" class="form-control form-control-sm" name="provincia" id="u_provincia">
							<span class="invalid-feedback d-block mb-0" id="u_e_provincia"></span>
						</div>
						<div class="col-4 form-group">
							<label for="">Distrito</label>
							<input type="text" class="form-control form-control-sm" name="distrito" id="u_distrito">
							<span class="invalid-feedback d-block mb-0" id="u_e_distrito"></span>
						</div>
						<div class="col-4 form-group">
							<label for="">CCPP</label>
							<input type="text" class="form-control form-control-sm" name="ccpp" id="u_ccpp">
							<span class="invalid-feedback d-block mb-0" id="u_e_ccpp"></span>
						</div>
						<div class="col-4 form-group">
							<label for="">Tecnologia</label>
							<input type="text" class="form-control form-control-sm" name="tecnologia" id="u_tecnologia">
							<span class="invalid-feedback d-block mb-0" id="u_e_tecnologia"></span>
						</div>
						<div class="col-4 form-group">
							<label for="">Incidador</label>
							<input type="text" class="form-control form-control-sm" name="indicador" id="u_indicador">
							<span class="invalid-feedback d-block mb-0" id="u_e_indicador"></span>
						</div>
						<div class="col-4 form-group">
							<label for="">GSM</label>
							<input type="text" class="form-control form-control-sm" name="gsm" id="u_gsm">
							<span class="invalid-feedback d-block mb-0" id="u_e_gsm"></span>
						</div>
						<div class="col-4 form-group">
							<label for="">UMTS</label>
							<input type="text" class="form-control form-control-sm" name="umts" id="u_umts">
							<span class="invalid-feedback d-block mb-0" id="u_e_umts"></span>
						</div>
						<div class="col-12 form-group">
							<label for="">Comentario osiptel</label>
                            <textarea class="form-control form-control-sm" name="comentario_osiptel" id="u_comentario_osiptel" rows="3"></textarea>
							<span class="invalid-feedback d-block mb-0" id="u_e_comentario_osiptel"></span>
						</div>
						<div class="col-4 form-group">
							<label for="">Fecha</label>
							<input type="date" class="form-control form-control-sm" name="fecha" id="u_fecha">
							<span class="invalid-feedback d-block mb-0" id="u_e_fecha"></span>
						</div>
						<div class="col-4 form-group">
							<label for="">Fecha limite</label>
							<input type="date" class="form-control form-control-sm" name="fecha_limite" id="u_fecha_limite">
							<span class="invalid-feedback d-block mb-0" id="u_e_fecha_limite"></span>
						</div>
						<div class="col-4 form-group">
							<label for="">Multa</label>
							<select class="form-control form-control-sm" name="multa" id="u_multa">
								<option value="">Seleccione</option>
								@foreach($booleanState as $row)
									<option value="{{$row->id}}">{{$row->nombre}}</option>
								@endforeach
							</select>
							<span class="invalid-feedback d-block mb-0" id="u_e_multa"></span>
						</div>
						<div class="col-4 form-group">
							<label for="">Grupo multa</label>
							<input type="text" class="form-control form-control-sm" name="grupo_multa" id="u_grupo_multa">
							<span class="invalid-feedback d-block mb-0" id="u_e_grupo_multa"></span>
						</div>
						<div class="col-4 form-group">
							<label for="">Periodo</label>
							<input type="text" class="form-control form-control-sm" name="periodo" id="u_periodo">
							<span class="invalid-feedback d-block mb-0" id="u_e_periodo"></span>
						</div>
						<div class="col-4 form-group">
							<label for="">Actividad</label>
							<select class="form-control form-control-sm" name="actividad" id="u_actividad">
								@foreach($actividades as $row)
									<option value="{{$row->id}}">{{$row->nombre}}</option>
								@endforeach
							</select>
							<span class="invalid-feedback d-block mb-0" id="u_e_actividad"></span>
						</div>
						<div class="col-4 form-group">
							<label for="">Fecha actividad</label>
							<input type="date" class="form-control form-control-sm" name="fecha_actividad" id="u_fecha_actividad">
							<span class="invalid-feedback d-block mb-0" id="u_e_fecha_actividad"></span>
						</div>
						<div class="col-4 form-group">
							<label for="">Estado</label>
							<select class="form-control form-control-sm" name="estado" id="u_estado">
								@foreach($estados as $row)
									<option value="{{$row->id}}">{{$row->nombre}}</option>
								@endforeach
							</select>
							<span class="invalid-feedback d-block mb-0" id="u_e_estado"></span>
						</div>
						<div class="col-4 form-group">
							<label for="">Acta levantada</label>
							<select class="form-control form-control-sm" name="acta_levantada" id="u_acta_levantada">
								<option value="">Seleccione</option>
								@foreach($booleanState as $row)
									<option value="{{$row->id}}">{{$row->nombre}}</option>
								@endforeach
							</select>
							<span class="invalid-feedback d-block mb-0" id="u_e_acta_levantada"></span>
						</div>
						<div class="col-12 form-group">
							<label for="">Comentario RED</label>
                            <textarea class="form-control form-control-sm" name="comentarios_red" id="u_comentarios_red" rows="3"></textarea>
							<span class="invalid-feedback d-block mb-0" id="u_e_comentarios_red"></span>
						</div>
						<div class="col-4 form-group">
							<label for="">Enviado osiptel</label>
							<select class="form-control form-control-sm" name="enviado_osiptel" id="u_enviado_osiptel">
								<option value="">Seleccione</option>
								@foreach($booleanState as $row)
									<option value="{{$row->id}}">{{$row->nombre}}</option>
								@endforeach
							</select>
							<span class="invalid-feedback d-block mb-0" id="u_e_enviado_osiptel"></span>
						</div>
						<div class="col-4 form-group">
							<label for="">Fecha envio osiptel</label>
							<input type="date" class="form-control form-control-sm" name="fecha_env_osiptel" id="u_fecha_env_osiptel">
							<span class="invalid-feedback d-block mb-0" id="u_e_fecha_env_osiptel"></span>
						</div>
						<div class="col-12 form-group">
							<label for="">Comentario RG</label>
                            <textarea class="form-control form-control-sm" name="comentarios_rg" id="u_comentarios_rg" rows="3"></textarea>
							<span class="invalid-feedback d-block mb-0" id="u_e_comentarios_rg"></span>
						</div>
						<div class="col-12 form-group" id="input_file_view">
							<label for="">Acta archivo</label>
							<div class="border border-secondary p-2">
								<strong class="d-inline-block mb-2">File:</strong>
								<a href="#" target="_blank" class="d-inline-block mb-2"></a>
								<br>
								<input type="file" accept=".xlsx" id="acta_archivo" name="acta_archivo" disabled>
								<span class="invalid-feedback d-block mb-0" id="u_e_acta_archivo"></span>
							</div>
						</div>
						<div class="col-4 form-group">
							<label for="">Creado</label>
							<input type="text" class="form-control form-control-sm" name="created_at" id="u_created_at" disabled>
							<span class="invalid-feedback d-block mb-0" id="u_e_created_at"></span>
						</div>
						<div class="col-4 form-group">
							<label for="">Actualizado</label>
							<input type="text" class="form-control form-control-sm" name="updated_at" id="u_updated_at" disabled>
							<span class="invalid-feedback d-block mb-0" id="u_e_updated_at"></span>
						</div>
						<div class="col-12">
							<button class="btn btn-primary" id="btn_update_modal">Actualizar</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script src="{{asset('js/utils.js')}}"></script>
<script src="{{asset('js/form_utils.js')}}"></script>
<script>
	function isActaLevantada(value){
		return value === 'Si' || value === 'SI';
	}

	const inputFileView = new InputFileView('#input_file_view');

	const fields = [
		'id',
		'encargados',
		'region',
		'ubigeo',
		'departamento',
		'provincia',
		'distrito',
		'ccpp',
		'tecnologia',
		'indicador',
		'gsm',
		'umts',
		'comentario_osiptel',
		'fecha',
		'fecha_limite',
		'periodo',
        'multa',
        'grupo_multa',
        'actividad',
        'fecha_actividad',
        'estado',
        'acta_levantada',
        'comentarios_red',
        'enviado_osiptel',
        'fecha_env_osiptel',
        'comentarios_rg',
		// 'acta_archivo'
	];

	function bindEvents(){
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$('#u_acta_levantada').off('change');
		$('#u_acta_levantada').on('change', function(e){
			const value = $(this).val();
			console.log(value);
			if(isActaLevantada(value)){
				inputFileView.disabled(false);
			}else{
				inputFileView.disabled(true);
			}
		});

		function submitUpdate(e){
			e.preventDefault();
			const id = $('#u_id').val();
			const formData = new FormData(document.getElementById('update_form'));

			$('#btn_update_modal').prop('disabled', true);

			fetch(`{{asset('cm-ccs-cv-temt/update')}}`, {
			method: "POST",
			body: formData,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				//'Content-Type': '"multipart/form-data;',
				// 'Authorization': `Bearer ${token}`
			}})
			.then(response => response.json())
			.then(resp => {
				$(".form-control").removeClass("is-invalid");
				$(".invalid-feedback").text("");
				if (!resp.passes) {
					loadErrorsOfFields('u_', fields, resp.errors);
				}else{
					//limpia errores y carga formulario
					loadErrorsOfFields('u_', fields, {});
					openUpdateModal(id);
					crud.table.ajax.reload();
					alert("Actualizado correctamente");
				}
				$("#btn_update_modal").prop('disabled', false);
			}).catch(function(error) {
				alert("Ocurrio un error: "+error);
			});
		}
		$('#btn_update_modal').off('click');
		$('#btn_update_modal').on('click', submitUpdate);
	}
	
	function openUpdateModal(id){
		bindEvents();
		loadErrorsOfFields('u_', fields, {});
		$('#acta_archivo').val('');
		$("#btn_update_modal").prop('disabled', true);
		//
		$('div.container-fluid').removeClass('cont_fluid__open_modal').addClass('cont_fluid__open_modal');
		$('#modal_update').modal('show');
		const data = {
			_token: $('meta[name="csrf-token"]').attr('content')
		};
		$.ajax({
			url : `{{asset('cm-ccs-cv-temt/api')}}/${id}`,
			type : 'GET',
			data: data,
			dataType : 'json',
			success : function(resp) {
				const data = resp.data;
				if(data.fecha !== null){
					data.fecha = data.fecha.substring(0, 10);
				}
				if(data.fecha_limite !== null){
					data.fecha_limite = data.fecha_limite.substring(0, 10);
				}
				if(data.fecha_actividad !== null){
					data.fecha_actividad = data.fecha_actividad.substring(0, 10);
				}
				if(data.fecha_env_osiptel !== null){
					data.fecha_env_osiptel = data.fecha_env_osiptel.substring(0, 10);
				}
				setValFields('u_', data);
				inputFileView.setLabel(data.acta_archivo);
				inputFileView.setFileUrl(data.acta_archivo_url);
				if(isActaLevantada(data.acta_levantada)){
					inputFileView.disabled(false);
				}else{
					inputFileView.disabled(true);
				}
				$("#btn_update_modal").prop('disabled', false);
			},
			error: function (resp) {
				$("#btn_update_modal").prop('disabled', false);
			}
		});
	}



</script>
@includeWhen(!empty($widget['wrapper']), 'backpack::widgets.inc.wrapper_end')