<div>
    <div id="date-picker-example" class="md-form md-outline input-with-post-icon datepicker form-select reporteCapacidad" inline="true">
        <div class="border border-secondary d-inline-block p-1" style="border-radius: 5px;">
            <label>Elegir dia:</label>
            <input type="text" id="fechaDia" name="fechaDia"
            placeholder="2021-10-05"
            >    </div>
        <div class="border border-secondary d-inline-block p-1" style="border-radius: 5px;">
        <a  id="reporteCapacidad" type="button" class="btn btn-danger disabled" href="" target="_blank">EXPORTAR POR DIA</a>
        </div>
    </div>

</div>

@push('after_scripts')
<script type="text/javascript">
    jQuery(document).ready(function($) {
        console.log('Hola XD');
        $('#fechaDia').keyup(function () {
            $('.btn').removeClass("disabled");
            var fechaDia = $('#fechaDia').val();

            var fechaDiaFormat = formato(fechaDia);
            console.log(fechaDiaFormat);

            if($('#reporteCapacidad').text() == 'EXPORTAR POR DIA'){                 
                $('#reporteCapacidad').attr('href', `${window.location.origin}/portalregulatorio/reporte_capacidad_template/export?fechaDia=${fechaDia}`);
            }
        });
    });
    function formato(texto){
        return texto.replace(/^(\d{4})-(\d{2})-(\d{2})$/g,'$3/$2/$1');
    }
</script>
@endpush