<style>
    #mygraph{
      font: 7pt arial;
      top: 0px;
      left: 0px;
    }
  </style>

  <script type="text/javascript" src="/portalmonitoreo/public/libs/visjs/dist/vis.js"></script>

  <script type="text/javascript">
    var data = null;
    var graph = null;
    drawVisualization();

    // Called when the Visualization API is loaded.
    function drawVisualization() {
      $.ajax({
        url: "{{ url('acceso_5g_movil_cobertura/21/6251/getListMapDataDet') }}",
        data:{
            cellname: "{{ $data['cellname'] }}",
            tracingID:<?=$data['tracingID']?>
        },
        dataType: 'json',
        type:'GET',
        success:function (resContent) {

        // Create and populate a data table.
        data = new vis.DataSet();
        // create some nice looking data with sin/cos
        var counter = 0;
        // var dtDate = arrayColumn(resContent,"DATENUM");
        // yValueLabel:function(v){return moment(Date.now().addHours(-v)).format("YYYY-MM-DD HH");},
        var dtDate = arrayColumn(resContent,"datenum");
        var dtRow = arrayColumn(resContent,"nivel_prb");
        var dtFld = arrayColumn(resContent,"avg_prb");
        var lngDT = dtRow.length;
        for (var xi = 0; xi < lngDT; xi++) {
            console.log({x:dtRow[xi]*1,y:dtDate[xi]*1,z:dtFld[xi]*1});
            data.add({id:counter++,x:dtRow[xi]*1,y:dtDate[xi]*1,z:dtFld[xi]*1});
        }

        // specify options
        var options = {
          width:  '550px',
          height: '450px',
          style: 'surface',
          showPerspective: true,
          showGrid: true,
          showShadow: false,
          showLegend: true,
          keepAspectRatio: false,
          tooltip:false,
          xLabel:'Nivel PRB',
          yLabel:'',
          yValueLabel:function(v){
            return moment((new 
              Date(Date.now())).addHours(-v)).format("DD/MM/YYYY HH:00");},
          // yValueLabel:function(v){return (Date.now()- (new Date(v)))/36e5;},
          zLabel:'AVG PRB',
          verticalRatio: 0.5,
          cameraPosition:{
            horizontal:-0.4,
            vertical:0.2,
            distance:2.0
          }
        };

        // Instantiate our graph object.
        var container = document.getElementById('mygraph');
        graph = new vis.Graph3d(container, data, options);
        }
      });
    }
  </script>
  
</head>

<div id="mygraph"></div>

<div id="info"></div>