<?php

namespace AMovil\Fija\OperacionFija\Services;

use AMovil\Fija\OperacionFija\Domain\FuenteFijaRepository;
use AMovil\Shared\Application\Response;
use AMovil\Shared\Exports\Domain\ExportService;
use AMovil\Shared\Exports\Domain\WriterType;
use DateTime;
use PhpOffice\PhpSpreadsheet\Style\Border;

class FuenteFijaExporter
{
    private $repo;
    private $exportService;

    public function __construct(FuenteFijaRepository $repo, ExportService $exportService)
    {
        $this->repo = $repo;
        $this->exportService = $exportService;
    }

    public function __invoke(): Response
    {
        $data = $this->repo->get();

        $options = [
            "rowType" => "object",
            "sheetIndex" => 0,
            'title' => "FUENTE FIJA",
            'styles' => [
                'header' => [
                    'font' => ['bold' => true, 'size' => 9],
                    'borders'=> [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => array('rgb'=>'000000')
                        ]
                    ]
                ],
                'body' => [
                    'font' => ['size' => 9]
                ]
            ]
        ];

        $headers = [
            "id" => ["label" => "ID"],
            "plano" => ["label" => "PLANO"],
            "tipo_nodo" => ["label" => "TIPO_NODO"],
            "tipo_fuente" => ["label" => "TIPO_FUENTE"],
            "ubicado_en" => ["label" => "UBICADO_EN"],
            "tiene_baterias" => ["label" => "TIENE_BATERIAS"],
            "anio_fab" => ["label" => "ANIO_FAB"],
            "tipo_respaldo" => ["label" => "TIPO_RESPALDO"],
            "amp" => ["label" => "AMP"],
            "candado" => ["label" => "CANDADO"],
            "barra" => ["label" => "BARRA"],
            "seguro_h" => ["label" => "SEGURO_H"],
            "seguro_baterias" => ["label" => "SEGURO_BATERIAS"],
            "fecha_manto" => ["label" => "FECHA_MANTO"],
            "anio_manto" => ["label" => "ANIO_MANTO"],
            "mes_manto" => ["label" => "MES_MANTO"],
            "referido" => ["label" => "REFERIDO"],
            "region" => ["label" => "REGION"],
            "distrito" => ["label" => "DISTRITO"],
            "segmento_urbano" => ["label" => "SEGMENTO_URBANO"],
            "gestion_campo" => ["label" => "GESTION_CAMPO"],
            "nivel_de_seguridad" => ["label" => "NIVEL_DE_SEGURIDAD"],
            "comentario" => ["label" => "COMENTARIO"],
            "latitud" => ["label" => "LATITUD"],
            "longitud" => ["label" => "LONGITUD"],
        ];

        $this->exportService->loadData($headers, $data, $options);
        $exportContent = $this->exportService->getWriter(WriterType::XLSX)->getOutput();

        $now = new DateTime();

        return new Response([], [
            "filename" => "fuente_fija_".$now->format("Ymd").".xlsx",
            "type" => "xlsx",
            "content" => $exportContent,
        ]);
    }
}
