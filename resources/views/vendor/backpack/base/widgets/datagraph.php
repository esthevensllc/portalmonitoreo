<input type="hidden" id="sectID_2" value="<?=$data['sectID']?>" class="field-hidden sectID">
	<?php if ($data['returnType']==-1): ?>

		<link rel="stylesheet" type="text/css" href="http://172.17.27.157/portalmonitoreo/assets/css/jquery-ui.min.css">
		<link rel="stylesheet" type="text/css" href="http://172.17.27.157/portalmonitoreo/assets/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="http://172.17.27.157/portalmonitoreo/assets/css/jquery.dataTables.min.css">
		<!-- btn exportar -->
		<link rel="stylesheet" type="text/css" href="http://172.17.27.157/portalmonitoreo/assets/css/buttons.dataTables.min.css">
		<!-- <link rel="stylesheet" type="text/css" href="http://172.17.27.157/portalmonitoreo/assets/css/bootstrap-multiselect.css"> -->
		<link rel="stylesheet" type="text/css" href="http://172.17.27.157/portalmonitoreo/assets/css/select2.min.css" />
		<link rel="stylesheet" type="text/css" href="http://172.17.27.157/portalmonitoreo/assets/css/menu.css">
		<link rel="stylesheet" type="text/css" href="http://172.17.27.157/portalmonitoreo/assets/css/chosen.css">
		<link rel="stylesheet" type="text/css" href="http://172.17.27.157/portalmonitoreo/assets/css/icons/style.css">
		<link rel="stylesheet" type="text/css" href="http://172.17.27.157/portalmonitoreo/assets/css/daterangepicker.css">
		<link rel="stylesheet" type="text/css" href="http://172.17.27.157/portalmonitoreo/assets/css/bootstrap-tagsinput.css">
		<link rel="stylesheet" type="text/css" href="http://172.17.27.157/portalmonitoreo/assets/css/fontawesome.css">
		<link rel="stylesheet" type="text/css" href="http://172.17.27.157/portalmonitoreo/assets/css/bootstrap-tagsinput.css">
		<link rel="stylesheet" type="text/css" href="http://172.17.27.157/portalmonitoreo/assets/css/style.css">
		<link rel="stylesheet" href="<?=asset('packages/line-awesome/css/line-awesome.min.css').'?v='.config('backpack.base.cachebusting_string') ?>">

	<div class="sectVw sectGraph">
	<?php endif ?>
	<div class="flagFiltGraph btn-danger">
		<span class="vwFilt hideManual">Ver Filtros</span>
		<span class="la la-filter"></span>
	</div>
	<?php if ($data['valMult']==0&&((isset($data['subMenu'])&&$data['subMenu']>0&&$data['subSecID']>0&&strlen($data['itemList'])>0)||
		(isset($data['urlSubmenu'])&&count($data['urlSubmenu'])>0))): ?>
		<div class="flagFiltMenu btn-danger">
			<span class="vwFilt hideManual" style="width:120px;">Ver Niveles</span>
			<span class="la la-list"></span>
		</div>
	<?php endif ?>
	<input type="hidden" id="dataGroupRange" class="dataGroupRange" name="dataGroupRange" 
	value="<?=$data['menuGroupNot']?>">
	<input type="hidden" id="itemTop" class="itemTop" name="itemTop" value="<?=urlencode($data['itemTop'])?>">

	<?php if ($data['valMult']==0&&((isset($data['subMenu'])&&$data['subMenu']>0&&$data['subSecID']>0&&strlen($data['itemList'])>0)||
		(isset($data['urlSubmenu'])&&count($data['urlSubmenu'])>0))): ?>
	<div id="submenugraph">
	<button type="button" class="close btn-hideSubmenuGraph" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
	<?php if(isset($data['urlSubmenu'])&&count($data['urlSubmenu'])>0):
			foreach ($data['urlSubmenu'] as $key => $urlMenu): 
				$returnMenu = (end($data['urlSubmenu'])<>$urlMenu)?'returnMenu':''; ?>
				<span class="<?=$returnMenu?>" data-key="<?=$key?>"><?=isset($urlMenu["menu"])?$urlMenu["menu"]:""?></span> / 
		<?php endforeach;?><br>
	<?php endif ?>
	<div class="d-inline-block mb-2">
		<button class="btn btn-sm btn-danger btn-hideSubmenuGraph"><span class="la la-list"></span></button>
			</div>
	<div class="d-inline-block mb-2">
		<label><input type="checkbox" name="" id="checkAll"> Seleccionar Todo</label>
	</div>

	<?php if (strlen($data['itemList'])>0): ?>
		<?php 
			$data['vwGChek']="";
			if ($data['groupVwGraph']==="true"||$data['groupVwGraph']===true){
				$data['vwGChek']="checked";
			}
		?>
		<div class="mb-2">
			<input type="checkbox" id="groupVwGraph" class="containerSubMenu" <?=$data['vwGChek']?> data-toggle="toggle" data-on="Desagrupar" data-off="Agrupar" data-onstyle="danger" data-offstyle="danger"  data-width="110" data-size="xs" data-submenu="<?=$data['subMenu']?>" data-daterange="<?=$data['idatarange']?>" data-sectSeptID="<?=$data['subSecID']?>" data-filter="<?=$data['filter']?>" data-vw="none">
		</div>
		<p class="funcBtn">
			<button class="btn btn-xs btn-danger btn-funcApplyChanges">Visualizar</button>
			<button class="btn btn-xs btn-danger btn-funcVwGroup"data-menuSel="<?=$data['menuSelID']?>">Ver SubNivel</button>
		</p>
		<p class="funcBtn">
			<input type="text" class="txtFilter">
		</p>
		<div class="listDataGraph ">
		<div class="container">
			<?php $rowList=0;
			foreach ($data['itemStrList'] as $key => $value): $rowList++;?>
			  	<div class="row align-items-center dtGraphSubmenu btn-ckeck" data-filter="<?=base64_encode($key)?>" data-sectSeptID="<?=$data['subSecID']?>"
					data-daterange="<?=$data['idatarange']?>" data-subMenuID="<?=$data['subMenuID']?>" 
					data-menuSel="<?=$data['menuSelID']?>">
					<?php $checkListStr =  (isset($data['itmViewList'])&&in_array($key, $data['itmViewList']))||
					(!isset($data['itmViewList'])&&$rowList<=20)?"checked":"";?>
					<div class="col-sm colFlagFiltView" >
						<div class="custom-control custom-switch">
							<input type="checkbox" id="customSwitch<?=$key?>" class="custom-control-input listShowGraph danger" name="listShowGraph" <?=$checkListStr?> value="<?=base64_encode($key)?>">
							<label class="custom-control-label" for="customSwitch<?=$key?>"></label>
						</div>
					</div>
					<div class="" style="min-width: 116px; text-align:left;">
						<label class="vw-subgraph"><?=$value?></label>
					</div>
					<div class="col-auto">
						<label class="vw-changeGraph btn-xs btn-danger"><i class="la la-arrow-right"></i></label>
					</div>
				</div>
			<?php endforeach ?>
			</div>
	<?php endif ?>
		</div>
	</div>
	<?php endif ?>
    <div id="spinnerGraph" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="cssload-loader-inner">
            <div class="cssload-cssload-loader-line-wrap-wrap">
                <div class="cssload-loader-line-wrap"></div>
            </div>
            <div class="cssload-cssload-loader-line-wrap-wrap">
                <div class="cssload-loader-line-wrap"></div>
            </div>
            <div class="cssload-cssload-loader-line-wrap-wrap">
                <div class="cssload-loader-line-wrap"></div>
            </div>
            <div class="cssload-cssload-loader-line-wrap-wrap">
                <div class="cssload-loader-line-wrap"></div>
            </div>
            <div class="cssload-cssload-loader-line-wrap-wrap">
                <div class="cssload-loader-line-wrap"></div>
            </div>
        </div>
    </div>
	<?php //$view_menu?>
	<?php $viewEdit = false; ?>
	<input type="hidden" id="secSepID" class="field-hidden" value="<?=$data['secSepID']?>">
	<input type="hidden" id="filter" class="field-hidden" value="<?=$data['filter']?>">
	<input type="hidden" id="menuGraphID" class="field-hidden" value="<?=$data['menuID']?>">
	<input type="hidden" id="menuSecID" class="field-hidden" value="{{$data['menuID']}}">
	<input type="hidden" id="menuFatherId" class="field-hidden" value="<?=$data['menuSel']["MID"]?>">
    <div class="container-fluid" style="min-height: 600px;">
        <div class="row flex-xl-nowrap dataGraphContent">
			<div id="menuGrpahOpt" class="menuGrpahOpt">
				<input type="hidden" class="hideManual previousMaxTime" value="<?=($data['previousMaxTime']??'3_Y')?>">
				<button class="btn btn-danger btn-sm btnVerMenu" data-val="1">
					<span class="la la-filter"></span>
				</button>
				<button type="button" class="close btnVerMenu" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<div class="">
					<select class="form-control slc-groupRange">
						<?php foreach ($data['sectType'] as $key => $value): ?>
							<option value="<?=$value['TYPE_NAME']?>" data-id="<?=$value['ID_TYPE']?>">
								<?=$value["DATA"][0]["TYPE_NAME"]??$value["TYPE_NAME"]?>
							</option>
						<?php endforeach ?>
					</select>
				</div>
				<div class="">
					<input type="text" name="" class="form-control idatarange" id="idatarange" 
					value="<?=$data['idatarange']?>">
				</div>
				<ul class="listaMenuGraph ">
					<?=$data['view_menuGraphOpt']?>
				</ul>
			</div>
            <div role="main" class="col-md-9 ml-sm-auto col-lg-12" style="overflow-x: auto; padding-bottom: 30px;">
			<div id="segPage" class=" divtab">
				<div id="divGraph"></div>
			</div>
			</div>
		</div>
	</div>

	<div id="dialogForm">
		<form action="<?=asset('acceso_4g_movil_desempeño/3/16/insertObs')?>">
			
		</form>
	</div>

	<div id="dialogBefore">
		<div align="center"></div>
	</div>

	<?php if ($data['returnType']==-1): ?>
	</div>

	<script type="text/javascript" src="http://172.17.27.157/portalmonitoreo/assets/js/jquery.js"></script>
		<!--<script type="text/javascript" src="http://172.17.27.157/portalmonitoreo/assets/js/jquery-3.4.1.slim.min.js"></script>-->
		<script type="text/javascript" src="http://172.17.27.157/portalmonitoreo/assets/js/popper.min.js"></script>
		<!-- <script type="text/javascript" src="http://172.17.27.157/portalmonitoreo/assets/js/bootstrap.min.js"></script> -->
		<script type="text/javascript" src="http://172.17.27.157/portalmonitoreo/assets/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="http://172.17.27.157/portalmonitoreo/assets/js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="http://172.17.27.157/portalmonitoreo/assets/js/chosen.jquery.js"></script>
		<script type="text/javascript" src="http://172.17.27.157/portalmonitoreo/assets/js/select2.min.js"></script>
		<script type="text/javascript" src="http://172.17.27.157/portalmonitoreo/assets/js/prism.js"></script>
		<script type="text/javascript" src="http://172.17.27.157/portalmonitoreo/assets/js/init.js"></script>
		<script type="text/javascript" src="http://172.17.27.157/portalmonitoreo/assets/js/jquery.dataTables.min.js"></script>
		<!-- btn exportar -->
		<script type="text/javascript" src="http://172.17.27.157/portalmonitoreo/assets/js/dataTables.buttons.min.js"></script>
		<script type="text/javascript" src="http://172.17.27.157/portalmonitoreo/assets/js/buttons.flash.min.js"></script>
		<script type="text/javascript" src="http://172.17.27.157/portalmonitoreo/assets/js/jszip.min.js"></script>
		<script type="text/javascript" src="http://172.17.27.157/portalmonitoreo/assets/js/pdfmake.min.js"></script>
		<script type="text/javascript" src="http://172.17.27.157/portalmonitoreo/assets/js/vfs_fonts.js"></script>
		<script type="text/javascript" src="http://172.17.27.157/portalmonitoreo/assets/js/buttons.html5.min.js"></script>
		<script type="text/javascript" src="http://172.17.27.157/portalmonitoreo/assets/js/buttons.print.min.js"></script>
		<!--<script type="text/javascript" src="http://172.17.27.157/portalmonitoreo/assets/js/bootstrap-multiselect.js"></script>-->
		<!-- <script src="http://172.17.27.157/portalmonitoreo/assets/js/jquery-3.1.1.min.js"></script>  -->
		<script type="text/javascript" src="http://172.17.27.157/portalmonitoreo/assets/js/highcharts.js"></script>
		<script type="text/javascript" src="http://172.17.27.157/portalmonitoreo/assets/js/highcharts-data.js"></script>
		<script type="text/javascript" src="http://172.17.27.157/portalmonitoreo/assets/js/highcharts-exporting.js"></script>
		<script type="text/javascript" src="http://172.17.27.157/portalmonitoreo/assets/js/highcharts-export-data.js"></script>
		<script type="text/javascript" src="http://172.17.27.157/portalmonitoreo/assets/js/highcharts-offline-exporting.js"></script>
		<script type="text/javascript" src="http://172.17.27.157/portalmonitoreo/assets/js/moment.min.js"></script>
		<script type="text/javascript" src="http://172.17.27.157/portalmonitoreo/assets/js/daterangepicker.min.js"></script>
		<script type="text/javascript" src="http://172.17.27.157/portalmonitoreo/assets/js/bootstrap-tagsinput.js"></script>
		<script type="text/javascript" src="http://172.17.27.157/portalmonitoreo/assets/js/tags.js"></script>
		<script>
			const BASE_URL = "<?=asset('')?>";
		</script>
		
		<script type="text/javascript" type="text/javascript" src="<?=asset('packages/backpack/crud/js/list.js')?>"></script>

		<script type="text/javascript">

			jQuery(function($){
				initialMenuGraph();
				initial();
				btnViewMenuEvent();
				$(".flagFiltGraph").css("margin-left","0px");
			});
		</script>
	<?php endif ?>
<script>
	$(function(){ 
		$('#groupVwGraph').bootstrapToggle() 
	});
</script>