<?php

namespace AMovil\Maps\OoklaMapFija\Services;

use AMovil\Maps\OoklaMapFija\Domain\OoklaMapFijaRepository;
use AMovil\Shared\Application\Response;
use DateTime;

class OoklaMapFijaFinder
{
private $repository;

    public function __construct(OoklaMapFijaRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getByMonthAndOperatorAndKpiname(DateTime $month, string $operator, string $kpiName): Response {
        $kpiNames = [
            'score' => 1,
            'val_download_kbps' => 1,
            'val_upload_kbps' => 1,
            'val_download_latency_iqm_ms' => 1,
            'val_upload_latency_iqm_ms' => 1,
            'num_devices' => 1,
        ];
        if (!array_key_exists($kpiName, $kpiNames)) {
            return new Response(["message" => "El kpi no es válido"]);
        }

        $allowOperator = [
            'Claro' => 1,
            'Bitel' => 1,
            'Entel' => 1,
            'Movistar' => 1,
            'Win' => 1,
            'Claro Fibra' => 1,
            'WOW' => 1,
            'Movistar Fibra' => 1,
            'Mi Fibra' => 1,
        ];
        if (!array_key_exists($operator, $allowOperator)) {
            return new Response(["message" => "El operador {$operator} no es válido"]);
        }

        $data = $this->repository->getByMonthAndOperatorAndKpiname($month, $operator, $kpiName);
        for ($i=0; $i < count($data); $i++) { 
            $row = $data[$i];
            $mappedRow = [
                "operator" => $row->operator,
                "latitude" => $row->latitude,
                "longitude" => $row->longitude,
                $kpiName => $row->{$kpiName},
            ];
            $data[$i] = $mappedRow;
        }
        return new Response([], $data);
    }

    public function getKpiList(): array {
        return [
            ['id' => 'score', 'label' => 'Score'],
            ['id' => 'val_download_kbps', 'label' => 'Download Mbps'],
            ['id' => 'val_upload_kbps', 'label' => 'Upload Mbps'],
            ['id' => 'val_download_latency_iqm_ms', 'label' => 'Download Latency iqm ms'],
            ['id' => 'val_upload_latency_iqm_ms', 'label' => 'Upload Latency iqm ms'],
            ['id' => 'num_devices', 'label' => 'Num Devices'],
        ];
    }

    public function getKpiMonths(){
        return $this->repository->getKpiMonths();
    }

    public function getKpiOperators(){
        return $this->repository->getKpiOperators();
    }

    public function getKpiLastMonths(): array {
        $data = $this->repository->getLastMonths(12);
        for ($i=0; $i < count($data); $i++) {
            $row = $data[$i];
            $mappedRow = [
                $row->mes,
                $row->operator,
                $row->score,
                $row->val_download_kbps,
                $row->val_upload_kbps,
                $row->val_download_latency_iqm_ms,
                $row->val_upload_latency_iqm_ms,
                $row->num_devices,
            ];
            $data[$i] = $mappedRow;
        }
        return [
            "fields" => [
                'mes' => 0,
                'operator' => 1,
                'score' => 2,
                'val_download_kbps' => 3,
                'val_upload_kbps' => 4,
                'val_download_latency_iqm_ms' => 5,
                'val_upload_latency_iqm_ms' => 6,
                'num_devices' => 7,
            ],
            "data" => $data
        ];
    }
}
