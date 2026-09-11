<div class="widget-content">
	<input type="hidden" name="" id="filter" value="<?=$data['filter']?>" class="">
	<input type="hidden" name="" id="typeGraph" value="<?=$data['typeGraph']?>" class="">
	<input type="hidden" id="nullZero" value="<?=$data['nullZero']?>" class="field-hidden nullZero">
    <style type="text/css">
    	svg g text tspan{
    		color:green;
    	}
		.highcharts-data-table table {
			border-collapse: collapse;
			border-spacing: 0;
			background: white;
			min-width: 100%;
			margin-top: 10px;
		}
		.highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
			border: 1px solid silver;
			padding: 0.5em;
		}
		.highcharts-data-table tr:nth-child(even), .highcharts-data-table thead tr {
			background: #f8f8f8;
		}
		.highcharts-data-table tr:hover {
			background: #eff;
		}
		.highcharts-data-table caption {
			border-bottom: none;
			font-size: 1.1em;
			font-weight: bold;
		} 
    </style>
	<h3><?=$data['tituloAdded']?></h3>
	<div>
		<div id="graphFilterContent<?=$data['selectID']?>">
			<div>
			</div>
		</div>
		<div id="graphContent<?=$data['selectID']?>"></div>
	</div>
	<script type="text/javascript">
		// var dataGroupRange = $("#dataGroupRange").val();
		var dataGroupRange = '<?=$data['dataGroupRange']?>';
		var dataFilt = 1000*60*60;//Hora
		var dateTimeLabelFormats = {
			day: "%Y-%m-%d %H:%M",
			month: "%Y-%m-%d %H:%M",
			year: "%Y-%m-%d %H:%M"
		};
		if([5695,'5695'].includes(dataGroupRange)){//Minutos
			dataFilt = 1000*60*15;
		}else if([6049,'6049'].includes(dataGroupRange)){//Segundo
			dataFilt = 1000;
		}else if([2571,'2571'].includes(dataGroupRange)){//Dias
			dataFilt = 1000*60*60*24;
			dateTimeLabelFormats = {
				day: "%Y-%m-%d",
				month: "%Y-%m-%d",
				year: "%Y-%m-%d"
			};
		}
		if(!['null',''].includes('<?=$data['timeInterval']?>')){
			dataFilt = 1000*parseInt('<?=$data['timeInterval']?>');
		}
		var dateLast = new Date();
		var serieData<?=$data['selectID']?> = [];
		var dateRangeini = $("#idatarange").val().split("-")[0].trim().split("/");
		var dateRangeFin = $("#idatarange").val().split("-")[1].trim().split("/");
		var dateMin = new Date(dateRangeini[2],dateRangeini[1]-1,dateRangeini[0]);
		var dateMin2 = new Date(dateRangeini[2],dateRangeini[1]-1,dateRangeini[0]);
		var dateFin = new Date(dateRangeFin[2],dateRangeFin[1]-1,dateRangeFin[0],23,59,59);
		if(dateFin>dateLast){
			dateFin=dateLast;
		}
		var typeGraph = $("#typeGraph").val();
		var params = {itemTop:$("#itemTop").val(),secSepID:'<?=$data['secSepID']?>',keyMenu:'<?=$data['keyMenu']?>',filter:'<?=$data['filter']?>',selectID:'<?=$data['selectID']?>',idatarange:'<?=$data['idatarange']?>',group:'<?=$data['group']?>',groupVwGraph:'<?=$data['groupVwGraph']?>'};
		var serieDate = [];

		$.ajax({
			url: "<?=asset('api/graph')?>",
			method: 'GET',
			data: params,
			dataType: 'json',
			beforeSend: function () {
				$("#spinnerGraph").css("display","block");
			},
			success: function (result) {
				var response = result;
				var data = response.dataval;
				var dataName = response.itemStrList;
				var totalSeries = [];
				var totalSeriesColor = [];
				var titleLineY = "";
				var unitLineY = "";
				var titleGraph = "";
				var unitgraph = "";
				// console.log("--------------downloadUrl 2---------------");
				// console.log(data);
				var nullZero = parseInt($("#nullZero").val());
				$.each(data,function (nameItem,dataVal) {
					serieData<?=$data['selectID']?> = [];
					titleLineY=dataVal.detailGraph.title_y;
					unitLineY=dataVal.detailGraph.unit_y;
					titleGraph=dataVal.detailGraph.titleGraph;
					unitgraph=dataVal.detailGraph.unitgraph;
					if(titleGraph=="")
						titleGraph='<?=$data['graphName']?>';
					if(typeGraph=='372'){
						var colorLine="";
						switch(nameItem){
							case 'LIMA':colorLine = "#dc3545";break;
							case 'CENTRO':colorLine = "#4400ff";break;
							case 'NORTE':colorLine = "#009d18";break;
							case 'SUR':colorLine = "#8017b0";break;
						}
						if(colorLine!='')
							totalSeriesColor.push({color:colorLine});
					}
					let datePrev = dateMin;
					serieDate = [];
					$.each(dataVal.dataList,function (x,y) {
						let dateItem = new Date(y.RESULTTIME);
						let dateDiff= 0;
						let diff = (dateItem.getTime() - datePrev.getTime())/(parseInt(dataFilt));
						// diff /=(60*60);
						dateDiff = Math.abs(Math.round(diff));
						
						if(datePrev.getTime() === dateMin.getTime() && dateDiff>1){
							// cuando es la primera fecha se incluye tambien en caso de nulos
							dateDiff = dateDiff+1;
						}

						if(dateDiff>1){
							for(var i=1;i<dateDiff;i++){
								// datePrev.setTime(datePrev.getTime()+1*parseInt(dataFilt));
								// serieDate.push(dateToString(datePrev));
								if(nullZero==1)
									serieData<?=$data['selectID']?>.push(0);
								else
									serieData<?=$data['selectID']?>.push(null);
							}
						}
						if(y.LINEY == null){
							if(nullZero==1)
								serieData<?=$data['selectID']?>.push(0);
							else
								serieData<?=$data['selectID']?>.push(null);
						}
						else if (typeof y.LINEY === 'string'){
							serieData<?=$data['selectID']?>.push(parseFloat(y.LINEY.replace(',','.')));
						}else{
							serieData<?=$data['selectID']?>.push(y.LINEY);
						}
						serieDate.push(dateToString(dateItem));
						dateLast = dateItem;
						datePrev = dateItem;
					});
					var difTImesGraph = Math.abs(Math.round((dateFin.getTime() - dateLast.getTime())/(dataFilt)))-2;
					console.log("difTImesGraph:"+difTImesGraph);
					if(difTImesGraph>0){
						for(var i=0;i<difTImesGraph;i++){
							if(nullZero==1)
								serieData<?=$data['selectID']?>.push(0);
							else
								serieData<?=$data['selectID']?>.push(null);
							serieDate.push(dateToString(dateLast));
						}
					}
					totalSeries.push({
						name:nameItem,
						<?php if (count(explode("|",$data['filter']))>1&&in_array($data['typeGraph'], array("372",372))): ?>
						color:colorLine,
						<?php endif ?>
						data:serieData<?=$data['selectID']?>
					});
				});
				var dateMinUTC =  Date.UTC(dateMin2.getFullYear(), dateMin2.getMonth(), dateMin2.getDate(),
					dateMin2.getHours(), dateMin2.getMinutes(), dateMin2.getSeconds());
				var descriptionGraph = "Del "+dateMin2.getDate()+"/"+((dateMin2.getMonth())+1)+"/"+dateMin2.getFullYear();
				descriptionGraph+=" Hasta "+dateFin.getDate()+"/"+((dateFin.getMonth())+1)+"/"+dateFin.getFullYear();
				
				Highcharts.chart({
					chart:{
						renderTo:'graphFilterContent<?=$data['selectID']?>',
						type: 'line',
						zoomType: 'xy',

					},
					title: {
							text: titleGraph,
					},
					exporting:{
						buttons:{
							contextButton:{
								menuItems:['viewFullscreen',
								//'printChart',
								'separator',
								'downloadJPEG',
								'separator',
								'downloadCSV',
								// 'downloadXLS',
								'viewData'
								]
							}
						},
						csv:{itemDelimiter:",",decimalPoint:"."},
						fallbackToExportServer:false,
						filename:'<?=$data['graphName']?>',
					},
					subtitle: {
						text: descriptionGraph
					},
					zoomType : 'xy',

					yAxis: {
						title: {
							text: titleLineY//'%'
						},
						labels:{
							formatter:function () {
								if(this.isLast&&unitLineY!=''){
									return unitLineY;
								}else{
									return this.value;
								}
							}
						},
						resize: {
							enabled: true
						}
					},
					xAxis: {
						<?php if (false): ?>
							categories:serieDate,
						<?php else: ?>
							type: 'datetime',
							accessibility: {
								rangeDescription: 'Range: 2010 to 2017'
							},
							// label:{
							// 	format: '{value:%Y-%m-%d %H:%M}'
							// }
							dateTimeLabelFormats: dateTimeLabelFormats,
						<?php endif ?>
						labels: {
							rotation: 270
						}
					},
					tooltip:{
						headerFormat: "<span style=\"font-size: 10px\">{point.key}</span><br/>",
						pointFormat: "<span style=\"color:{point.color}\">●</span> {series.name}: <b>{point.y} "+unitgraph+"</b><br/>"
						// headerFormat:'<small>{point.key}</small>',
						// pointFormat: "<span style=\"font-size: 10px\">{point.key}</span><br/>"+
						// "<span style=\"color:{point.color}\">●</span> {series.name}: <b>{point.y}"+'{unitgraph}'+"</b>"
						// formatter: function(tooltip){
						// 	var tooltipFormat=tooltip.defaultFormatter.call(this,tooltip);
						// 	tooltipFormat[1] = tooltipFormat[1]+""
						// 	return tooltip.defaultFormatter.call(this,tooltip);
						// 	// return "<span style=\"font-size: 10px\">{this.}point.key+"</span><br/>"+"<span style=\"color:"+this.point.color+"\">●</span> "+this.series.name+": <b>"+this.point.y+"</b><br/><b>"+this.series.name+"</b><br/>"+this.x+": "+this.y+" "+unitgraph;
						// }
					},
					legend: {
						// layout: 'vertical',
						// align: 'right',
						// verticalAlign: 'middle'
					},

					plotOptions: {
						series: {
							label: {
								connectorAllowed: true
							},
							// data: serieDate
							pointStart: dateMinUTC,
							pointInterval: (dataFilt)
						}
					},
					series: totalSeries,

					responsive: {
						rules: [{
							condition: {
								maxWidth: 500
							},
							chartOptions: {
								legend: {
									layout: 'horizontal',
									align: 'center',
									verticalAlign: 'bottom'
								}
							}
						}]
					},

				});
				Highcharts.setOptions({
					lang: {
						months: [
							'Enero', 'Febrero', 'Marzo', 'Abril',
							'Mayo', 'Junio', 'Julio', 'Agosto',
							'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'
						],
						shortMonths: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Set','Oct','Nov','Dic'],
						weekdays: [
							'Domingo', 'Lunes', 'Martes', 'Miercoles',
							'Jueves', 'Viernes', 'Sabado'
						]
					}
				});
				$("#spinnerGraph").css("display","none");
			},
			complete: function () {
				// $("#spinnerGraph").css("display","none");
			}
		});
	</script>
</div>