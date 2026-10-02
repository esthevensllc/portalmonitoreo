<div class="d-inline-block ml-3 mb-3 lista-diaria-widget" style="vertical-align: top;">
    <div class="btn-group" role="group" aria-label="Descargar lista diaria">
        <button
            id="lista-diaria-dropdown"
            type="button"
            class="btn btn-primary dropdown-toggle"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
            title="Descarga la información del día anterior">
            <i class="la la-download"></i> Lista diaria
        </button>

        <div class="dropdown-menu" aria-labelledby="lista-diaria-dropdown">
            <a class="dropdown-item" href="{{ route('desemp.lista-diaria', ['format' => 'xlsx']) }}">
                <i class="la la-file-excel-o"></i> Excel (.xlsx)
            </a>
            <a class="dropdown-item" href="{{ route('desemp.lista-diaria', ['format' => 'csv']) }}">
                <i class="la la-file-text-o"></i> CSV (.csv)
            </a>
        </div>
    </div>
</div>
