<?php
namespace App\Http\Controllers\Admin;

use AMovil\Modules\PsoModules\Domain\PsoModuleRepository;
use AMovil\Shared\Exports\Domain\Writer;
use AMovil\Shared\Exports\Domain\WriterType;
use App\Facads\ODB;
use App\Repository\FijaCoverageTopRepository;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapFijaCoverageController
{
    private $configRepository;
    private $topConfigRepo;
	private $query_by_distribucion = [];
	private $query_cob_otros_by_distribucion = [];
	private $subTableByDistribucion = [];
    private $kpi_config = [
		["ID" => 1, "NAME" => "kpi_ocupacion", "LABEL" => "Penetración %", "GRAPH" => false, "DESC" => "Medida de inserción del servicio en el plano(clientes/HHPP)"],
		[
			"ID" => 2,
			"NAME" => "kpi_velocidad_mbps_avg",
			"LABEL" => "Velocidad Mpbs",
			"EXTRA_GRAPH_KPIS" => ["4"],
			// "DESCRIPTION" => "Descripcion",
			"DESC" => "Promedio de velocidad contratada.",
			"COLOR_RANGE" => [
				['COLOR' => '#e4fa00', 'CONDITION' => '0 <= KPI && KPI <= 50'],
				['COLOR' => '#32d502', 'CONDITION' => '50 < KPI && KPI <= 100'],
				['COLOR' => '#03c475', 'CONDITION' => '100 < KPI && KPI <= 170'],
				['COLOR' => '#00b5b2', 'CONDITION' => '170 < KPI && KPI <= 230'],
				['COLOR' => '#588AC4', 'CONDITION' => '230 < KPI && KPI <= 270'],
				['COLOR' => '#3d00af', 'CONDITION' => '270 < KPI'],
			]
		],
		//["ID" => 3, "NAME" => "kpi_calidad", "LABEL" => "Calidad QoE", "GRAPH" => true],
		[
			"ID" => 4, 
			"NAME" => "kpi_cant_reclamos", 
			"LABEL" => "CAntidad de reclamos.",
			"COLOR_RANGE" => [
				['COLOR' => '#44A344', 'CONDITION' => 'KPI = 0'],
				['COLOR' => '#ffd300', 'CONDITION' => '0 < KPI && KPI <= 5'],
				['COLOR' => '#F3661B', 'CONDITION' => '5 < KPI && KPI <= 10'],
				['COLOR' => '#9F1A1A', 'CONDITION' => '10 < KPI']
			]
		],
		//["ID" => 5, "NAME" => "KPI_DISPONIBILIDAD", "LABEL" => "Disponibilidad", "GRAPH" => false],
		[
			"ID" => 6,
			"NAME" => "kpi_cliente_alta",
			"LABEL" => "Clientes nuevos",
			"GRAPH" => false,
			"DESC" => "# Clientes nuevos en el plano semanalmente.",
			"GRAPH_TITLE" => "{value} - Clientes",
			"GRAPH_KPIS" => ["kpi_cliente_total", 
							 "kpi_cliente_alta", 
							 "kpi_cliente_baja", 
							 "kpi_cliente_suspendido"],
			"COLOR_RANGE" => [
				//['COLOR' => '#F6D745', 'CONDITION' => '0 < KPI && KPI < 50'],
				//['COLOR' => '#9ACE9C', 'CONDITION' => '0 < KPI && KPI <= 5'],
				//['COLOR' => '#72BA74', 'CONDITION' => '0 < KPI && KPI <= 5'],
				//['COLOR' => '#009432', 'CONDITION' => '5 < KPI'],

				/*['COLOR' => '#FF3333', 'CONDITION' => '0 == KPI'],
				['COLOR' => '#b95700', 'CONDITION' => '1 <= KPI && KPI < 2'],
				['COLOR' => '#b98100', 'CONDITION' => '2 <= KPI && KPI < 3'],
				['COLOR' => '#b9b400', 'CONDITION' => '3 <= KPI && KPI < 4'],
				['COLOR' => '#95b900', 'CONDITION' => '4 <= KPI && KPI < 5'],
				['COLOR' => '#5ab900', 'CONDITION' => '5 <= KPI'],*/

				['COLOR' => '#FF0000', 'CONDITION' => '0 == KPI'],
				['COLOR' => '#FFCC00', 'CONDITION' => '1 <= KPI && KPI < 2'],
				['COLOR' => '#99FF33', 'CONDITION' => '2 <= KPI && KPI < 3'],
				['COLOR' => '#66FF33', 'CONDITION' => '3 <= KPI && KPI < 4'],
				['COLOR' => '#33CC33', 'CONDITION' => '4 <= KPI && KPI < 5'],
				['COLOR' => '#006600', 'CONDITION' => '5 <= KPI'],
			]
		],
		[
			"ID" => 7,
			"NAME" => "kpi_cliente_baja",
			"LABEL" => "Clientes Bajas",
			"GRAPH" => false,
			"DESC" => "# Clientes de baja en el plano semanalmente.",
			"GRAPH_TITLE" => "{value} - Clientes",
			"GRAPH_KPIS" => ["kpi_cliente_total", 
							 "kpi_cliente_alta", 
							 "kpi_cliente_baja", 
							 "kpi_cliente_suspendido"],
			"COLOR_RANGE" => [
				/*['COLOR' => '#5ab900', 'CONDITION' => '0 == KPI'],
				['COLOR' => '#95b900', 'CONDITION' => '1 <= KPI && KPI < 2'],
				['COLOR' => '#b9b400', 'CONDITION' => '2 <= KPI && KPI < 3'],
				['COLOR' => '#b98100', 'CONDITION' => '3 <= KPI && KPI < 4'],
				['COLOR' => '#b95700', 'CONDITION' => '4 <= KPI && KPI < 5'],
				['COLOR' => '#FF3333', 'CONDITION' => '5 <= KPI'],*/

				['COLOR' => '#00CC00', 'CONDITION' => '0 == KPI'],
				['COLOR' => '#FFFF66', 'CONDITION' => '1 <= KPI && KPI < 2'],
				['COLOR' => '#FFCC00', 'CONDITION' => '2 <= KPI && KPI < 3'],
				['COLOR' => '#FF9933', 'CONDITION' => '3 <= KPI && KPI < 4'],
				['COLOR' => '#FF3300', 'CONDITION' => '4 <= KPI && KPI < 5'],
				['COLOR' => '#CC0000', 'CONDITION' => '5 <= KPI'],
			]
		],
		[
			"ID" => 8,
			"NAME" => "kpi_cliente_suspendido",
			"LABEL" => "Clientes suspendidos",
			"GRAPH" => false,
			"DESC" => "# Clientes suspendidos en el plano semanalmente.",
			"GRAPH_TITLE" => "{value} - Clientes",
			"GRAPH_KPIS" => ["kpi_cliente_total", 
							 "kpi_cliente_alta", 
							 "kpi_cliente_baja", 
							 "kpi_cliente_suspendido"],
			"COLOR_RANGE" => [
				['COLOR' => '#44A344', 'CONDITION' => 'KPI == 0'],
				['COLOR' => '#ffd300', 'CONDITION' => '0 < KPI && KPI <= 10'],
				['COLOR' => '#F3661B', 'CONDITION' => '10 < KPI && KPI <= 20'],
				['COLOR' => '#EA2027', 'CONDITION' => '20 < KPI'],
			]
		],
		[
			"ID" => 9,
			"NAME" => "kpi_cliente_total",
			"LABEL" => "Clientes total",
			"GRAPH" => false,
			"DESC" => "# Clientes en el plano semanalmente.",
			"GRAPH_TITLE" => "{value} - Clientes",
			//"GRAPH_KPIS" => ["kpi_clientes_nuevos", "kpi_clientes_bajas", "kpi_cliente_total", "kpi_clientes_suspendidos"],
			"GRAPH_KPIS" => ["kpi_cliente_total", 
							 "kpi_cliente_alta", 
							 "kpi_cliente_baja", 
							 "kpi_cliente_suspendido"],
			"COLOR_RANGE" => [
				['COLOR' => '#F6D745', 'CONDITION' => '0 < KPI && KPI < 50'],
				['COLOR' => '#9ACE9C', 'CONDITION' => '50 < KPI && KPI <= 100'],
				['COLOR' => '#72BA74', 'CONDITION' => '100 < KPI && KPI <= 150'],
				['COLOR' => '#009432', 'CONDITION' => '150 < KPI'],
			]
		],
		[
			"ID" => 10,
			"NAME" => "kpi_clientes_velocidad_maxima",
			"LABEL" => "Cantidad velocidad maxima",
			"GRAPH" => false,
			"COLOR_RANGE" => [
				['COLOR' => '#44A344', 'CONDITION' => 'KPI == 0'],
				['COLOR' => '#ffd300', 'CONDITION' => '0 < KPI && KPI <= 10'],
				['COLOR' => '#F3661B', 'CONDITION' => '10 < KPI && KPI <= 20'],
				['COLOR' => '#EA2027', 'CONDITION' => '20 < KPI'],
			]
		],
		[
			"ID" => 11,
			"NAME" => "kpi_clientes_autosaturados",
			"LABEL" => "Cantidad autosaturados",
			"GRAPH" => false,
			"COLOR_RANGE" => [
				['COLOR' => '#44A344', 'CONDITION' => 'KPI == 0'],
				['COLOR' => '#ffd300', 'CONDITION' => '0 < KPI && KPI <= 10'],
				['COLOR' => '#F3661B', 'CONDITION' => '10 < KPI && KPI <= 20'],
				['COLOR' => '#EA2027', 'CONDITION' => '20 < KPI && KPI <= 30'],
				['COLOR' => '#570000', 'CONDITION' => '30 < KPI'],
			]
		],
		[
			"ID" => 12,
			"NAME" => "kpi_churn",
			"LABEL" => "Churn %",
			"GRAPH" => false,
			"DESC" => "Medida de pérdida de clientes en el plano.",
			"COLOR_RANGE" => [
				/*['COLOR' => '#44A344', 'CONDITION' => 'KPI == 0'],
				['COLOR' => '#ffd300', 'CONDITION' => '0 < KPI && KPI <= 1'],
				['COLOR' => '#F3661B', 'CONDITION' => '1 < KPI && KPI <= 2'],
				['COLOR' => '#EA2027', 'CONDITION' => '2 < KPI && KPI <= 3'],
				['COLOR' => '#570000', 'CONDITION' => '3 < KPI && KPI <= 4'],
				['COLOR' => '#570000', 'CONDITION' => '4 < KPI && KPI <= 5'],
				['COLOR' => '#570000', 'CONDITION' => '5 < KPI && KPI <= 6'],
				['COLOR' => '#570000', 'CONDITION' => '6 < KPI && KPI <= 7'],
				['COLOR' => '#570000', 'CONDITION' => '8 < KPI'],*/

				/*['COLOR' => '#580aff', 'CONDITION' => 'KPI == 0'],
				['COLOR' => '#147df5', 'CONDITION' => '0 < KPI && KPI <= 1'],
				['COLOR' => '#0aff99', 'CONDITION' => '1 < KPI && KPI <= 2'],
				['COLOR' => '#a1ff0a', 'CONDITION' => '2 < KPI && KPI <= 3'],
				['COLOR' => '#deff0a', 'CONDITION' => '3 < KPI && KPI <= 4'],
				['COLOR' => '#ffd300', 'CONDITION' => '4 < KPI && KPI <= 5'],
				['COLOR' => '#ff8700', 'CONDITION' => '5 < KPI && KPI <= 6'],
				['COLOR' => '#ff0000', 'CONDITION' => '6 < KPI && KPI <= 7'],
				['COLOR' => '#570000', 'CONDITION' => '8 < KPI'],*/

				['COLOR' => '#006600', 'CONDITION' => 'KPI == 0'],
				['COLOR' => '#99FF33', 'CONDITION' => '0 < KPI && KPI <= 1'],
				['COLOR' => '#FFCC00', 'CONDITION' => '1 < KPI && KPI <= 2'],
				['COLOR' => '#FF6600', 'CONDITION' => '2 < KPI && KPI <= 3'],
				['COLOR' => '#FF0000', 'CONDITION' => '3 < KPI && KPI <= 4'],
				['COLOR' => '#CC0000', 'CONDITION' => '4 < KPI && KPI <= 5'],
				['COLOR' => '#990000', 'CONDITION' => '5 < KPI && KPI <= 6'],
				['COLOR' => '#660000', 'CONDITION' => '6 < KPI && KPI <= 7'],
				['COLOR' => '#330000', 'CONDITION' => '8 < KPI'],
			]
		],
		[
			"ID" => 13,
			"NAME" => "kpi_score",
			"LABEL" => "Score",
			"GRAPH" => false,
			"COLOR_RANGE" => [
				['COLOR' => '#C00000', 'CONDITION' => 'KPI < 70'],
				['COLOR' => '#ED7D31', 'CONDITION' => '70 <= KPI && KPI <= 89'],
				['COLOR' => '#C5E0B4', 'CONDITION' => '90 <= KPI && KPI <= 99'],
				['COLOR' => '#44A344', 'CONDITION' => '100 <= KPI'],
			]
		],
		[
			"ID" => 14,
			"NAME" => "kpi_porcentaje_suspendidos",
			"LABEL" => "Suspendidos %",
			"GRAPH" => false,
			"DESC" => "Porcentaje de usuarios Suspendidos (Suspendidos/total).",
			"COLOR_RANGE" => [
				['COLOR' => '#44A344', 'CONDITION' => 'KPI == 0'],
				['COLOR' => '#a1ff0a', 'CONDITION' => '0 < KPI && KPI <= 3'],
				['COLOR' => '#deff0a', 'CONDITION' => '3 < KPI && KPI <= 5'],
				['COLOR' => '#ffd300', 'CONDITION' => '5 < KPI && KPI <= 10'],
				['COLOR' => '#ff8700', 'CONDITION' => '10 < KPI && KPI <= 20'],
				['COLOR' => '#ff0000', 'CONDITION' => '20 < KPI'],
			]
		],
		[
			"ID" => 15,
			"NAME" => "kpi_cliente_alta_2_meses",
			"LABEL" => "Clientes nuevos 2M",
			"GRAPH" => false,
			"GRAPH_TITLE" => "{value} - Altas/Bajas 2Meses",
			"GRAPH_KPIS" => ["kpi_cliente_alta_2_meses", 
							 "kpi_cliente_baja_2_meses"],
			"COLOR_RANGE" => [
				['COLOR' => '#570000', 'CONDITION' => 'KPI == 0'],
				['COLOR' => '#EA2027', 'CONDITION' => '0 < KPI && KPI <= 5'],
				['COLOR' => '#E15616', 'CONDITION' => '5 < KPI && KPI <= 10'],
				['COLOR' => '#FF9557', 'CONDITION' => '10 < KPI && KPI <= 15'],
				['COLOR' => '#FFB800', 'CONDITION' => '15 < KPI && KPI <= 20'],
				['COLOR' => '#ffd300', 'CONDITION' => '20 < KPI && KPI <= 25'],
				['COLOR' => '#F7F700', 'CONDITION' => '25 < KPI && KPI <= 30'],
				['COLOR' => '#44A344', 'CONDITION' => '30 < KPI'],
			]			
			# "COLOR_RANGE" => [
			# 	['COLOR' => '#EC2B1A', 'CONDITION' => '0 <= KPI && KPI <= 1'],
			# 	['COLOR' => '#FF9557', 'CONDITION' => '1 < KPI && KPI <= 3'],
			# 	['COLOR' => '#FFB800', 'CONDITION' => '3 < KPI && KPI <= 5'],
			# 	['COLOR' => '#ffd300', 'CONDITION' => '5 < KPI && KPI <= 7'],
			# 	['COLOR' => '#F7F700', 'CONDITION' => '7 < KPI && KPI <= 10'],
			# 	['COLOR' => '#06500C', 'CONDITION' => '10 < KPI'],
			# ]
			],
		[
			"ID" => 16,
			"NAME" => "kpi_cliente_baja_2_meses",
			"LABEL" => "Clientes Bajas 2M",
			"GRAPH" => false,
			"GRAPH_TITLE" => "{value} - Altas/Bajas 2Meses",
			"GRAPH_KPIS" => ["kpi_cliente_alta_2_meses", 
							 "kpi_cliente_baja_2_meses"],
			"COLOR_RANGE" => [
				['COLOR' => '#44A344', 'CONDITION' => 'KPI == 0'],
				['COLOR' => '#F7F700', 'CONDITION' => '0 < KPI && KPI <= 5'],
				['COLOR' => '#ffd300', 'CONDITION' => '5 < KPI && KPI <= 10'],
				['COLOR' => '#FFB800', 'CONDITION' => '10 < KPI && KPI <= 15'],
				['COLOR' => '#FF9557', 'CONDITION' => '15 < KPI && KPI <= 20'],
				['COLOR' => '#E15616', 'CONDITION' => '20 < KPI && KPI <= 25'],
				['COLOR' => '#EA2027', 'CONDITION' => '25 < KPI && KPI <= 30'],
				['COLOR' => '#570000', 'CONDITION' => '30 < KPI'],
			]
		],
		[
			"ID" => 17,
			"NAME" => "kpi_mejor_operador",
			"LABEL" => "Mejor Downstream",
			"GRAPH" => false,
			"COLOR_RANGE" => [
				['COLOR' => '#44A344', 'CONDITION' => 'KPI == 1'],
				['COLOR' => '#3CD5A9', 'CONDITION' => '2 <= KPI && KPI < 3'],
				['COLOR' => '#ffd300', 'CONDITION' => '3 <= KPI && KPI < 4'],
				['COLOR' => '#F3661B', 'CONDITION' => '4 <= KPI && KPI < 5'],
				['COLOR' => '#EA2027', 'CONDITION' => '5 <= KPI'],
			]
		],
		[
			"ID" => 18,
			"NAME" => "kpi_mejor_latencia",
			"LABEL" => "Mejor Latencia",
			"GRAPH" => false,
			"COLOR_RANGE" => [
				['COLOR' => '#44A344', 'CONDITION' => 'KPI == 1'],
				['COLOR' => '#3CD5A9', 'CONDITION' => '2 <= KPI && KPI < 3'],
				['COLOR' => '#ffd300', 'CONDITION' => '3 <= KPI && KPI < 4'],
				['COLOR' => '#F3661B', 'CONDITION' => '4 <= KPI && KPI < 5'],
				['COLOR' => '#EA2027', 'CONDITION' => '5 <= KPI'],
			]
		],
		[
			"ID" => 19,
			"NAME" => "kpi_latencia_ms",
			"LABEL" => "Latencia",
			"GRAPH" => false,
			"COLOR_RANGE" => [
				['COLOR' => '#EA2027', 'CONDITION' => '80 < KPI', "LABEL" => "Latencia-Avg > 80ms"],
				['COLOR' => '#F3661B', 'CONDITION' => '40 < KPI && KPI <= 80', "LABEL" => "40ms < Latencia-Avg <= 80ms"],
				['COLOR' => '#ffd300', 'CONDITION' => '20 < KPI && KPI <= 40', "LABEL" => "20ms < Latencia-Avg <= 40ms"],
				['COLOR' => '#3CD5A9', 'CONDITION' => '5 < KPI && KPI <=20', "LABEL" => "5ms < Latencia-Avg <=20ms"],
				['COLOR' => '#44A344', 'CONDITION' => 'KPI <= 5', "LABEL" => "Latencia-Avg <= 5ms"],
			]
		],
		[
			"ID" => 20,
			"NAME" => "crecimiento_neto",
			"LABEL" => "Crecimiento Neto",
			"GRAPH" => false,
			"COLOR_RANGE" => [
				['COLOR' => '#EC2B1A', 'CONDITION' => '-5 <= KPI && KPI <= -3'],
				['COLOR' => '#FF9557', 'CONDITION' => '-3 < KPI && KPI <= -1'],
				['COLOR' => '#FFB800', 'CONDITION' => '-1 < KPI && KPI <= 1'],
				['COLOR' => '#ecf0f1', 'CONDITION' => '0 == KPI'],
				['COLOR' => '#9ACE9C', 'CONDITION' => '1 < KPI && KPI <= 3'],
				['COLOR' => '#009432', 'CONDITION' => '3 < KPI && KPI <= 5'],
				['COLOR' => '#11ddd0', 'CONDITION' => '5 < KPI'],
			]
		],
		[
			"ID" => 21,
			"NAME" => "kpi_disponibilidad",
			"LABEL" => "Disponibilidad",
			"GRAPH" => false,
			"COLOR_RANGE" => [
				['COLOR' => '#570000', 'CONDITION' => 'KPI < 99'],
				['COLOR' => '#ff0000', 'CONDITION' => '99 <= KPI && KPI < 99.9'],
				['COLOR' => '#ff8700', 'CONDITION' => '99.9 <= KPI && KPI < 100'],
				//['COLOR' => '#ffd300', 'CONDITION' => '99.5 <= KPI && KPI < 100'],
				['COLOR' => '#44A344', 'CONDITION' => '100 <= KPI']
			]
		],
		[
			"ID" => 22,
			"NAME" => "kpi_mktshare", 
			"LABEL" => "Participación Mercado",
			"GRAPH" => false,
			"COLOR_RANGE" => [
				['COLOR' => '#ff0000', 'CONDITION' => 'KPI <= 10'],
				['COLOR' => '#ff8700', 'CONDITION' => '10 < KPI && KPI < 20'],
				['COLOR' => '#ffd300', 'CONDITION' => '20 < KPI && KPI < 30'],
				['COLOR' => '#44A344', 'CONDITION' => '30 < KPI && KPI < 40'],
				['COLOR' => '#3d00af', 'CONDITION' => '40 <= KPI'],
			]
		],
		[
			"ID" => 23,
			"NAME" => "kpi_sots_altas", 
			"LABEL" => "Instalaciones de SOTS",
			"GRAPH" => false,
			"COLOR_RANGE" => [
				['COLOR' => '#ff0000', 'CONDITION' => '0 == KPI'],
				['COLOR' => '#ff8700', 'CONDITION' => '1 == KPI'],
				['COLOR' => '#ffd300', 'CONDITION' => '2 <= KPI && KPI < 4'],
				['COLOR' => '#44A344', 'CONDITION' => '4 <= KPI && KPI < 8'],
				['COLOR' => '#147df5', 'CONDITION' => '8 <= KPI'],
			]
		]
	];
	private $detailFieldsByDistribucion = [
		"1" => [
			"ano" => ["label" => "Año"],
			"semana" => ["label" => "Semana"],
			"departamento" => ["label" => "Departamento"],
			"ubigeo_dpto" => ["label" => "Ubigeo"],
			"kpi_ocupacion" => ["label" => "% Penetración"],
			"kpi_velocidad_mbps_avg" => ["label" => "Velocidad Contratada Mbps"],
			"kpi_clientes_suspendidos" => ["label" => "# Clientes Suspendidos"],
			"kpi_cliente_total" => ["label" => "# Clientes totales"],
			"kpi_cant_autosaturados" => ["label" => "# Autosaturados"],
			"kpi_velocidad_maxima" => ["label" => "# Velocidad máxima"],
			"kpi_hhpp_totales" => ["label" => "# HHPP Totales"],
			"kpi_clientes_nuevos" => ["label" => "# Clientes nuevos"],
			"kpi_clientes_bajas" => ["label" => "# Clientes bajas"],
			"kpi_churn" => ["label" => "% Churn"],
			"kpi_score_den" => ["label" => "% Score"],
			"kpi_porcentaje_suspendidos" => ["label" => "% Suspendidos"],
		],
		"2" => [
			"ano" => ["label" => "Año"],
			"semana" => ["label" => "Semana"],
			"departamento" => ["label" => "Departamento"],
			"provincia" => ["label" => "Provincia"],
			"ubigeo_prov" => ["label" => "Ubigeo"],
			"kpi_ocupacion" => ["label" => "% Penetración"],
			"kpi_velocidad_mbps_avg" => ["label" => "Velocidad Contratada Mbps"],
			"kpi_cliente_suspendido" => ["label" => "# Clientes Suspendidos"],
			"kpi_cliente_total" => ["label" => "# Clientes totales"],
			"kpi_clientes_autosaturados" => ["label" => "# Autosaturados"],
			"kpi_clientes_velocidad_maxima" => ["label" => "# Velocidad máxima"],
			"kpi_hhpp_total" => ["label" => "# HHPP Totales"],
			"kpi_clientes_nuevos" => ["label" => "# Clientes nuevos"],
			"kpi_clientes_bajas" => ["label" => "# Clientes bajas"],
			"kpi_churn" => ["label" => "% Churn"],
			"kpi_score_den" => ["label" => "% Score"],
			"kpi_porcentaje_suspendidos" => ["label" => "% Suspendidos"],
		],
		"3" => [
			"ano" => ["label" => "Año"],
			"semana" => ["label" => "Semana"],
			"departamento" => ["label" => "Departamento"],
			"provincia" => ["label" => "Provincia"],
			"distrito" => ["label" => "Distrito"],
			"ubigeo" => ["label" => "Ubigeo"],
			"penetracion_total" => ["label" => "% Penetración"],
			// "kpi_velocidad_mbps_avg" => ["label" => "Velocidad Contratada Mbps"],
			// "kpi_clientes_suspendidos" => ["label" => "# Clientes Suspendidos"],
			// "kpi_cliente_total" => ["label" => "# Clientes totales"],
			// "kpi_cant_autosaturados" => ["label" => "# Autosaturados"],
			// "kpi_velocidad_maxima" => ["label" => "# Velocidad máxima"],
			// "kpi_hhpp_totales" => ["label" => "# HHPP Totales"],
			// "kpi_clientes_nuevos" => ["label" => "# Clientes nuevos"],
			// "kpi_clientes_bajas" => ["label" => "# Clientes bajas"],
			// "kpi_churn" => ["label" => "% Churn"],
			// "kpi_score_den" => ["label" => "% Score"],
			// "kpi_porcentaje_suspendidos" => ["label" => "% Suspendidos"],
		],
		"4" => [
			"ano" => ["label" => "Año"],
			"semana" => ["label" => "Semana"],
			// "device_name" == ["label" => "CMTS/OLT"],
			"plano" => ["label" => "Plano"],
			"region" => ["label" => "Region"],
			"departamento" => ["label" => "Departamento"],
			"provincia" => ["label" => "Provincia"],
			"distrito" => ["label" => "Distrito"],
			"ubigeo" => ["label" => "Ubigeo"],
			"tecnologia" => ["label" => "Tecnologia"],
			"fecha_liberacion" => ["label" => "Fecha liberacion"],
			//"poblacion" => ["label" => "Población"],
			//"kpi_mktshare" => ["label" => "Participación Mercado"],
			"num_competencia" => ["label" => "# Competencia"],
			//"competencia" => ["label" => "Operadores Competencia"],
			//"num_interacciones" => [
			//	"label" => "# Interacciones",
			//	"modal_info" => ["4" => ["table"]]
			//],
			"kpi_ocupacion" => ["label" => "% Penetración"],
			"kpi_velocidad_mbps_avg" => ["label" => "# Velocidad Contratada Mbps", "modal_info" => ["4" => ["table"]]],
			"kpi_cliente_suspendido" => ["label" => "# Clientes Suspendidos"],
			"kpi_cliente_total" => ["label" => "# Clientes totales"],
			"kpi_cliente_movil" => ["label" => "# Clientes totales moviles"],
			"kpi_clientes_autosaturados" => ["label" => "# Autosaturados"],
			"kpi_clientes_velocidad_maxima" => ["label" => "# Velocidad máxima"],
			"kpi_hhpp_total" => ["label" => "# HHPP Totales"],
			"kpi_cliente_alta" => ["label" => "# Clientes nuevos"],
			"kpi_cliente_baja" => ["label" => "# Clientes bajas"],
			"kpi_churn" => ["label" => "% Churn"],
			"kpi_score" => ["label" => "% Score"],
			"kpi_porcentaje_suspendidos" => ["label" => "% Suspendidos"],
			"kpi_cliente_baja_2_meses" => ["label" => "# Clientes bajas 2 meses"],
			"kpi_cliente_alta_2_meses" => ["label" => "# Clientes nuevos 2 meses"],
			"kpi_mejor_operador" => ["label" => "Ranking velocidad DL"],
			"kpi_mejor_latencia" => ["label" => "Ranking latencia"],
			"kpi_latencia_ms" => ["label" => "Latencia"],
			"kpi_cant_reclamos" => [
				"label" => "# Reclamos",
				"modal_info" => ["4" => ["table"]]
			],
			//"ruc_10" => ["label" => "RUC 10"],
			//"ruc_20" => ["label" => "RUC 20"],
			//"plano_mala_venta" => ["label" => "Planos Mala Venta"],
			"crecimiento_neto" => ["label" => "% Crecimiento Neto"],
			// "kpi_disponibilidad" => ["label" => "Disponibilidad"],
			// "kpi_cli_ruc_10" => ["label" => "Cli Ruc10"],
			// "kpi_cli_ruc_20" => ["label" => "Cli Ruc20"],
			"kpi_sots_altas" => ["label" => "Instalaciones de SOTS"],
			"kpi_sots_bajas" => ["label" => "Bajas de SOTS"],
			"kpi_sots_mantenimiento" => ["label" => "Mantenimiento de SOTS"],
			"numero_fat" => ["label" => "Numero Fats"],
		],
	];
	private $map_filter_by_distribucion;
	private $graphQueryByDistribucion;
	private $tableByDistribucion;

    public function __construct(PsoModuleRepository $repository, FijaCoverageTopRepository $topConfigRepo)
    {
        $this->configRepository = $repository;
		$this->topConfigRepo = $topConfigRepo;

		$this->query_by_distribucion = [
            '1' => "SELECT
				ano, SEMANA, DEPARTAMENTO, RESULTTIME, UBIGEO_DPTO, KPI_OCUPACION, KPI_CLIENTES_SUSPENDIDOS, KPI_CLIENTE_TOTAL, KPI_CANT_AUTOSATURADOS,
				KPI_VELOCIDAD_MAXIMA, KPI_HHPP_TOTALES, KPI_CLIENTES_NUEVOS, KPI_CLIENTES_BAJAS, KPI_VELOCIDAD_MBPS_AVG, round(kpi_churn, 2) kpi_churn, KPI_SCORE_DEN, KPI_PORCENTAJE_SUSPENDIDOS
				FROM FIJA_PLANOS_KPIS_DPTO [str_filters]",
            '2' => "SELECT
				ano, SEMANA, DEPARTAMENTO, PROVINCIA, RESULTTIME, UBIGEO_PROV, KPI_OCUPACION, KPI_CLIENTES_SUSPENDIDOS, KPI_CLIENTE_TOTAL, KPI_CANT_AUTOSATURADOS,
				KPI_VELOCIDAD_MAXIMA, KPI_HHPP_TOTALES, KPI_CLIENTES_NUEVOS, KPI_CLIENTES_BAJAS, KPI_VELOCIDAD_MBPS_AVG, round(kpi_churn, 2) kpi_churn, KPI_SCORE_DEN, KPI_PORCENTAJE_SUSPENDIDOS
				FROM FIJA_PLANOS_KPIS_PROV [str_filters]",
			'3' => "SELECT
				ANO, SEMANA, DEPARTAMENTO, PROVINCIA, DISTRITO,TO_CHAR(FEC_FIN, 'YYYY-MM-DD') AS RESULTTIME, 
				UBIGEO, 
				ROUND(PENETRACION_TOTAL, 2) AS PENETRACION_TOTAL
				from FIJA_PLANOS_INDICADORES_SEMANAL_DISTRITO [str_filters]",
			'4' => "SELECT
				ANO,
				SEMANA,
				TO_CHAR(FEC_FIN, 'YYYY-MM-DD') AS RESULTTIME,
				A.DEVICE_NAME,
				A.PLANO,
				A.REGION,
				A.DEPARTAMENTO,
				A.PROVINCIA,
				A.DISTRITO,
				A.UBIGEO,
				A.TECNOLOGIA,
				TO_CHAR(A.FECHA_LIBERACION, 'DD/MM/YYYY') AS FECHA_LIBERACION,
				KPI_CLIENTE_TOTAL,
				KPI_CLIENTE_BAJA,
				KPI_CLIENTE_ALTA,
				KPI_CLIENTE_SUSPENDIDO,
				ROUND(CASE WHEN KPI_HHPP_TOTAL = 0 THEN 0 ELSE KPI_CLIENTE_TOTAL/KPI_HHPP_TOTAL END, 4) * 100 AS KPI_OCUPACION,
				ROUND(CASE WHEN KPI_CLIENTE_TOTAL = 0 THEN 0 ELSE KPI_VELOCIDAD_DS_ACUMULADO/KPI_CLIENTE_TOTAL END) AS KPI_VELOCIDAD_MBPS_AVG,
                ROUND(CASE WHEN KPI_CLIENTE_TOTAL = 0 THEN 0 ELSE KPI_CLIENTE_SUSPENDIDO/KPI_CLIENTE_TOTAL END, 4)*100 AS KPI_PORCENTAJE_SUSPENDIDOS,
				KPI_CLIENTES_AUTOSATURADOS,
				KPI_CLIENTES_VELOCIDAD_MAXIMA,
				KPI_HHPP_TOTAL,
				KPI_SOTS_ALTAS,
				KPI_SOTS_BAJAS,
				KPI_SOTS_MANTENIMIENTO,
				KPI_CLIENTE_BAJA_2_MESES,
				KPI_CLIENTE_ALTA_2_MESES,
				CRECIMIENTO_NETO,
				round(KPI_CHURN, 2) KPI_CHURN,
				KPI_CANT_RECLAMOS,
				KPI_LATENCIA_MS,
				KPI_MEJOR_LATENCIA,
				KPI_MEJOR_OPERADOR,
				KPI_MEJOR_DOWNLOAD,
				KPI_CLIENTE_MOVIL,
				KPI_SCORE,
				NUMERO_FAT,
                                NUMERO_COMPETENCIA AS NUM_COMPETENCIA,
				RUC_10,
				RUC_20,
				COMPETENCIA
				FROM FIJA_PLANOS_INDICADORES_SEMANAL A [str_filters]",
        ];


		$this->map_filter_by_distribucion = [
			'1' => [],
			'2' => [],
			'3' => [],
			'4' => [
				"ano" => "a.ano",
				"semana" => "a.semana",
				"plano" => "a.plano",
			],
		];

		$this->query_cob_otros_by_distribucion = [
            '1' => "SELECT PSO.UBIGEO_DPTO, PSO.DEPARTAMENTO
				FROM ( {$this->query_by_distribucion['1']} ) PSO
				INNER JOIN PSO_DATA_FIJA_COB_OTROS O
				ON UPPER(O.DEPARTAMENTO) = UPPER(PSO.DEPARTAMENTO)",
            '2' => "SELECT PSO.UBIGEO_PROV, PSO.DEPARTAMENTO, PSO.PROVINCIA
				FROM ( {$this->query_by_distribucion['2']} ) PSO
				INNER JOIN PSO_DATA_FIJA_COB_OTROS O
				ON UPPER(O.DEPARTAMENTO) = UPPER(PSO.DEPARTAMENTO) AND UPPER(O.PROVINCIA) = UPPER(PSO.PROVINCIA)",
            '3' => "SELECT PSO.UBIGEO, PSO.DEPARTAMENTO, PSO.PROVINCIA, PSO.DISTRITO
				FROM ( {$this->query_by_distribucion['3']} ) PSO
				INNER JOIN PSO_DATA_FIJA_COB_OTROS O
				ON UPPER(O.PROVINCIA) = UPPER(PSO.PROVINCIA) AND UPPER(O.DISTRITO) = UPPER(PSO.DISTRITO)",
            '4' => "SELECT PSO.PLANO, PSO.DEPARTAMENTO, PSO.PROVINCIA, PSO.DISTRITO
			FROM ( {$this->query_by_distribucion['4']} ) PSO
			INNER JOIN PSO_DATA_FIJA_COB_OTROS O
			ON UPPER(O.PROVINCIA) = UPPER(PSO.PROVINCIA) AND UPPER(O.DISTRITO) = UPPER(PSO.DISTRITO)",
        ];

		$this->graphQueryByDistribucion = [
			"4" => [
				"kpi_velocidad_mbps_avg" => [
					"sql" => "SELECT ano, SEMANA, FEC_FIN, VELOCIDAD, SUM(NVL(CANTIDAD, 0)) USUARIOS FROM FIJA_PLANOS_VELOCIDADES
					WHERE  PLANO = :plano
					GROUP BY ano, SEMANA, FEC_FIN, VELOCIDAD
					ORDER BY FEC_FIN",
					"seriesConfig" => ["usuarios" => ["label" => "Usuarios"]],
					"result_time" => "fec_fin",
					"title" => "Velocidad Mbps"
				],
				"num_interacciones" => [
					"sql" => "SELECT ano, SEMANA, FEC_FIN, VELOCIDAD, SUM(NVL(CANTIDAD, 0)) USUARIOS FROM FIJA_PLANOS_VELOCIDADES
					WHERE  PLANO = :plano
					GROUP BY ano, SEMANA, FEC_FIN, VELOCIDAD
					ORDER BY FEC_FIN",
					"seriesConfig" => ["usuarios" => ["label" => "Usuarios"]],
					"result_time" => "fec_fin",
					"title" => "Velocidad Mbps"
				],
				"kpi_cant_reclamos" => [
					"sql" => "SELECT ano, SEMANA, FEC_FIN, VELOCIDAD, SUM(NVL(CANTIDAD, 0)) USUARIOS FROM FIJA_PLANOS_VELOCIDADES
					WHERE  PLANO = :plano
					GROUP BY ano, SEMANA, FEC_FIN, VELOCIDAD
					ORDER BY FEC_FIN",
					"seriesConfig" => ["usuarios" => ["label" => "Usuarios"]],
					"result_time" => "fec_fin",
					"title" => "Velocidad Mbps"
				]
			]
		];
		$this->tableByDistribucion = [
			"4" => [
				"kpi_velocidad_mbps_avg" => [
					"sql" => "SELECT PLANO, VELOCIDAD, CANTIDAD FROM FIJA_PLANOS_VELOCIDADES
					WHERE ano = :ano AND SEMANA = :semana AND PLANO = :id ORDER BY 3 DESC",
					"fields" => [
						"velocidad" => ["label" => "Velocidad Contratada"],
						"cantidad" => ["label" => "# Usuarios"],
					],
				],
				"num_interacciones" => [
					"sql" => "SELECT plano, date_trunc('week', fecha_reclamo) semana, tipificacion, sum(cantidad) interacciones,
					max(fecha_reclamo) max_fecha
					from dmred.interact_plano_1day
					where fecha_reclamo >= toDate('2024-04-01') and tipificacion is not null and plano = :id
					group by plano, date_trunc('week', fecha_reclamo), tipificacion
					order by date_trunc('week', fecha_reclamo)",
					"connection" => "ch-dn05",
					"pivot" => ["key" => "tipificacion", "label" => "semana", "value" => "interacciones"],
					"fields" => [
						"tipificacion" => [
							"label" => "TIPO",
							"class" => "interaccion-number",
							"sub_table" => ["plano" => "plano", "tipificacion" => "tipificacion"]
						],
						/*"tipificacion" => ["label" => "TIPO"],
						"interacciones" => [
							"label" => "# INTERACCIONES",
							"class" => "interaccion-number",
							"sub_table" => ["plano" => "plano", "tipificacion" => "tipificacion"]
						],*/
					],
				],
				"kpi_cant_reclamos" => [
					"sql" => "SELECT problema, sum(cantidad_reclamos) reclamos
					from   FIJA_RECLAMOS_TIPIFICACION
					where  semana = :semana
					and ano = :ano
					and    plano = :id
					group by problema
					order by sum(cantidad_reclamos) desc",
					"fields" => [
						"problema" => ["label" => "PROBLEMA"],
						"reclamos" => [
							"label" => "# RECLAMOS"
						],
					],
				],
				"kpi_mejor_operador" => [
					"sql" => "select plano, operador, muestras_totales, ranking_latencia, ranking_velocidad, latencia_ms, velocidad_mbps from (
							SELECT plano, attr_provider_name_common operador, muestras_totales, 
								RANK_MEDIAN_LATENCY_MIN_MS  ranking_latencia, RANK_MEDIAN_DOWNLOAD_MBPS ranking_velocidad,
								round(median_latency_min_ms,2) latencia_ms, round(median_download_mbps,2) velocidad_mbps,  
								rank() over (order by muestras_totales desc) ranking
							FROM FIJA_PLANOS_COMPETENCIA
							WHERE ANO = 2025 AND SEMANA = 48
							--AND UPPER(ATTR_PROVIDER_NAME_COMMON) LIKE '%CLARO%'
							AND UPPER(ATTR_PROVIDER_NAME_COMMON) <> 'CLARO PERU'
							and ranking_mejor_operador is not null
							and plano = :id
							) order by ranking asc",
					"fields" => [
						"plano" => ["label" => "Plano"], 
						"operador" => ["label" => "Operador"],
						"muestras_totales" => ["label" => "Muestras Totales"],
						"ranking_latencia" => ["label" => "Ranking Latencia"],
						"ranking_velocidad" => ["label" => "Ranking Velocidad"],
						"latencia_ms" => ["label" => "Latencia MS"],
						"velocidad_mbps" => ["label" => "Velocidad MBPS"]
					],
				],
				"kpi_mejor_latencia" => [
					"sql" => "select plano, operador, muestras_totales, ranking_latencia, ranking_velocidad, latencia_ms, velocidad_mbps from (
							SELECT plano, attr_provider_name_common operador, muestras_totales, 
								RANK_MEDIAN_LATENCY_MIN_MS  ranking_latencia, RANK_MEDIAN_DOWNLOAD_MBPS ranking_velocidad,
								round(median_latency_min_ms,2) latencia_ms, round(median_download_mbps,2) velocidad_mbps,  
								rank() over (order by muestras_totales desc) ranking
							FROM FIJA_PLANOS_COMPETENCIA
							WHERE ANO = 2025 AND SEMANA = 48
							--AND UPPER(ATTR_PROVIDER_NAME_COMMON) LIKE '%CLARO%'
							AND UPPER(ATTR_PROVIDER_NAME_COMMON) <> 'CLARO PERU'
							and ranking_mejor_operador is not null
							and plano = :id
							) order by ranking asc",
					"fields" => [
						"plano" => ["label" => "Plano"], 
						"operador" => ["label" => "Operador"],
						"muestras_totales" => ["label" => "Muestras Totales"],
						"ranking_latencia" => ["label" => "Ranking Latencia"],
						"ranking_velocidad" => ["label" => "Ranking Velocidad"],
						"latencia_ms" => ["label" => "Latencia MS"],
						"velocidad_mbps" => ["label" => "Velocidad MBPS"]
					],
				],
			]
		];
		$this->subTableByDistribucion = [
			"4" => [
				"tipificacion" => [
					"sql" => "SELECT plano, date_trunc('week', fecha_reclamo) semana, s_reason_3 tipo, sum(cantidad) interacciones
					from dmred.interact_plano_1day
					where fecha_reclamo >= toDate('2024-04-01') [str_filters]
					group by plano, date_trunc('week', fecha_reclamo), s_reason_3
					order by date_trunc('week', fecha_reclamo)",
					"connection" => "ch-dn05",
					"pivot" => ["key" => "tipo", "label" => "semana", "value" => "interacciones"],
					"fields" => [
						"tipo" => ["label" => "TIPO"]
					],
				]
			]
		];
    }

	private function get_query_by_distribucion($distribucion_id){
		return $this->query_by_distribucion[$distribucion_id];
	}

	private function getExtraGraphConfig($distribucion, $kpi_id)
	{
		if(array_key_exists($distribucion, $this->graphQueryByDistribucion)){
			if(array_key_exists($kpi_id, $this->graphQueryByDistribucion[$distribucion])){
				$config = $this->graphQueryByDistribucion[$distribucion][$kpi_id];
				return json_decode(json_encode($config));
			}
		}
		return null;
	}

	private function getTableConfig($distribucion, $kpi_id)
	{
		if(array_key_exists($distribucion, $this->tableByDistribucion)){
			if(array_key_exists($kpi_id, $this->tableByDistribucion[$distribucion])){
				$config = $this->tableByDistribucion[$distribucion][$kpi_id];
				return json_decode(json_encode($config));
			}
		}
		return null;
	}

	private function getSubTableConfig($distribucion, $subField)
	{
		if(array_key_exists($distribucion, $this->subTableByDistribucion)){
			if(array_key_exists($subField, $this->subTableByDistribucion[$distribucion])){
				$config = $this->subTableByDistribucion[$distribucion][$subField];
				return json_decode(json_encode($config));
			}
		}
		return null;
	}

	private function mapFilters($filters){
		$map_operators = [
			'eq' => '=',
			'in' => 'in',
			'cn' => 'like',
			'>=' => '>=',
			'<=' => '<='
		];
		
		$formated_filters = [];
		foreach($filters as $filter){
			$is_valid = true;
			$filter_parts = explode(".", rawurldecode($filter));
			if(!array_key_exists($filter_parts[1], $map_operators)){
				$is_valid = false;
			}
			if($is_valid){
				$formated_filters[] = [
					'field' => $filter_parts[0],
					'operator' => $filter_parts[1],
					//'operator_' => $map_operators[$filter_parts[1]],
					'value' => $filter_parts[2],
				];
			}
		}
		return $formated_filters;
	}

	private function mapFormatedFiltersFields($filters, $distribucion)
	{
		for ($i=0; $i < count($filters); $i++) { 
			$field = $filters[$i]["field"];
			if(array_key_exists($field, $this->map_filter_by_distribucion[$distribucion])){
				$field = $this->map_filter_by_distribucion[$distribucion][$field];
			}
			$filters[$i]["field"] = $field;
		}
		return $filters;
	}

	private function mapFiltersToSql($formated_filters){
		$operator_templates = [
			'eq' => '[field] = [value]',
			'in' => '[field] in ([value])',
			'cn' => "[field] like '%'||[value]||'%'",
			'>=' => '[field] >= [value]',
			'<=' => '[field] <= [value]'
		];

		$str_filters = [];
		$value_binds = [];
		foreach($formated_filters as $filter){
			$str_filters[] = str_replace(["[field]", "[value]"], [$filter['field'], '?'], $operator_templates[$filter['operator']]);
			$value_binds[] = $filter['value'];
		}
		$str_filters = implode(" AND ", $str_filters);
		return ['sql'=> $str_filters, 'binds' => $value_binds];
	}

    public function index($id_tracing)
    {
        
        $tracingID = $id_tracing;
        $menuID = "6748";
        $title = "";
        $id_profile = null;
        $roles = backpack_user()->roles;
        if(count($roles) > 1){
            $id_profile = $roles[0]->perfil;
        }
        $trac_title = $this->configRepository->findTitleByIdTracingAndModuleType($id_tracing, (int) $menuID);
        if($trac_title !== null){
            $title = "{$trac_title->main_module} | {$trac_title->module} | {$trac_title->module_type}";
        }

        $ano = DB::select("select ano, MAX(semana) max_semana from FIJA_PLANOS_INDICADORES_SEMANAL group by ano order by ano desc");

		$planoUrl = "map_" . $tracingID . "_" . $menuID;

        $kpis = $this->kpi_config;
        $kpis = json_decode(json_encode($kpis));
        $kpis = array_merge(array_filter($kpis, function($row) use ($id_profile){
            return ((int) $id_profile === 81 && in_array($row->ID, [1,2,6,7,8,9])) || (int) $id_profile !== 81;
        }), []);

        $dptoList = $this->executeProcedure("BEGIN PSO_OSSPORTAL.SP_GET_UBGDPTO(:resultado); END;");

		$competencias = DB::select("select operador from (
		select operador, sum(muestras_total) muestras from fijaplanoscompetencia
		where upper(operador) not like '%CLARO%'
		group by operador
		) order by muestras desc");

		$detailFieldsByDistribucion = $this->detailFieldsByDistribucion;
        $detailFieldsByDistribucion = json_decode(json_encode($detailFieldsByDistribucion));

        return view("backpack::mapFijaCoverage", compact("tracingID", "menuID", "title", "ano", "planoUrl", "kpis", "dptoList", "competencias", "detailFieldsByDistribucion"));
    }

	public function get_semana_by_ano($ano)
	{
		$response = DB::select("select semana from FIJA_PLANOS_INDICADORES_SEMANAL where ano = ? group by semana order by semana desc", [$ano]);
		return response()->json($response);
	}

	public function get_data_fija(Request $request)
	{
		$response = $this->_get_data_fija([
			"distribucion" => $request->post("distribucion"),
			"filter" => $request->post("filter"),
		]);
		error_log(print_r($request->all(), true));
		return response()->json($response);
	}

	private function _get_data_fija($options = [])
    {
		$distribucion = $options["distribucion"];
		$filters = $options["filter"];
		/*if($this->input->post('distribucion') !== null){
			$distribucion = $this->input->post('distribucion');
			$filters = $this->input->post('filter');
		}else{
			$distribucion = $options['distribucion'];
			$filters = $options['filter'];
		}*/
		$filters = $filters === null ? [] : $filters;

		$formated_filters = $this->mapFilters($filters);
		$formated_filters = $this->mapFormatedFiltersFields($formated_filters, $distribucion);
		$str_filters = $this->mapFiltersToSql($formated_filters);
		if(strlen($str_filters['sql']) > 0){
			$str_filters['sql'] = " WHERE ".$str_filters['sql'];
		}
        
        $query = "";
        if(array_key_exists($distribucion, $this->query_by_distribucion)){
			$query = $this->get_query_by_distribucion($distribucion);
        }else{
            $query = $this->get_query_by_distribucion('4');
        }
		$query = str_replace("[str_filters]", $str_filters['sql'], $query);
		$query = "SELECT * FROM ($query) ORDER BY RESULTTIME";

		$query_binds = $str_filters['binds'];
        $list = DB::select($query, $query_binds);
		// echo json_encode($list);
		return $list;
    }


	public function get_cobertura_otros(Request $request)
    {
        $ano = $request->post('ano');
		$semana = $request->post('semana');
		$distribucion = $request->post('distribucion');
		$cobertura_otro = $request->post('cobertura_otro');

		$filters = ["ano.eq.{$ano}", "semana.eq.{$semana}"];
		$formated_filters = $this->mapFilters($filters);
		$str_filters = $this->mapFiltersToSql($formated_filters);
		if(strlen($str_filters['sql']) > 0){
			$str_filters['sql'] = " WHERE ".$str_filters['sql'];
		}

        if(array_key_exists($distribucion, $this->query_cob_otros_by_distribucion)){
			$query = $this->query_cob_otros_by_distribucion[$distribucion];
			$query = str_replace("[str_filters]", $str_filters['sql'], $query);
			$query = $query." WHERE grupo = ?";
			$query = str_replace("ano = ? AND semana = ?", "a.ano = ? AND a.semana = ?", $query);
			$query_binds = array_merge($str_filters['binds'], [$cobertura_otro]);

			$list = DB::select(DB::raw($query), $query_binds);
			return response()->json(['data' => $list, 'query' => $query, 'binds' => $query_binds]);
		};
		return response()->json(['data' => []]);
    }

	public function get_cobertura_otros_geojson(Request $request)
	{
		$distribucion = $request->post('distribucion');
		$cobertura_otro = $request->get('cobertura_otro');
		$query = "SELECT NOMBRE, GEOMETRY FROM PSO_DATA_FIJA_COB_OTROS WHERE GRUPO=? AND GEOMETRY IS NOT NULL";
		$list = DB::select(DB::raw($query), [$cobertura_otro]);
		$geojson = ["type" => "FeatureCollection", "features" => []];
		foreach($list as $row){
			$geojson['features'][] = [
				"type" => "Feature",
				"properties" => ["nombre" => $row->nombre],
				"geometry" => json_decode($row->geometry),
			];
		}
		//var_dump($geojson);
		//header("Content-Type: application/json");
		return response()->json($geojson);
	}

	public function get_data_fija_by_name(Request $request, $distribucion_id, $kpi_id)
	{
		$ano = $request->post('ano');
		$semana = $request->post('semana');
		//$name = rawurldecode($name);
		$filters = [];
		if(!in_array($ano, ['0', null])){
			$filters[] = "ano.eq.{$ano}";
		}
		if(!in_array($semana, ['0', null])){
			$filters[] = "semana.eq.{$semana}";
		}
		
		$filters = $request->post('filter') !== null ? array_merge($filters, $request->post('filter')) : $filters;

		$formated_filters = $this->mapFilters($filters);
		$formated_filters = $this->mapFormatedFiltersFields($formated_filters, $distribucion_id);
		$str_filters = $this->mapFiltersToSql($formated_filters);
		if(strlen($str_filters['sql']) > 0){
			$str_filters['sql'] = " WHERE ".$str_filters['sql'];
		}

		$query = "";
        if(array_key_exists($distribucion_id, $this->query_by_distribucion)){
            $query = $this->get_query_by_distribucion($distribucion_id);
        }else{
            $query = $this->get_query_by_distribucion('4');
        }
		if(array_key_exists($distribucion_id, $this->detailFieldsByDistribucion)){
			$str_fields = [];
			foreach($this->detailFieldsByDistribucion[$distribucion_id] as $name => $row){
				$str_fields[] = $name;
			}
			$str_fields = implode(", ", $str_fields);
			$query = "SELECT {$str_fields} FROM ($query)";
		}
		$query = str_replace("[str_filters]", $str_filters['sql'], $query);

		$query_binds = $str_filters['binds'];

		if($distribucion_id == 4){
			
			$list = $this->executeProcedure("BEGIN PSO_OSSPORTAL.SP_GET_PLANO_VALID_OVERLAP(:P_ano,:P_SEMANA,:P_PLANO,:resultado); END;",[
				"P_ano" => ["value" => $query_binds[0], "type" => ODB::INTEGER],
				"P_SEMANA" => ["value" => $query_binds[1], "type" => ODB::INTEGER],
				"P_PLANO" => ["value" => $query_binds[2], "type" => ODB::CHAR],
				"resultado" => ["type" => ODB::CURSOR],
			]);

			foreach($list as $index => $row){
				$list[$index] = array_change_key_case(array_map('strtolower', $list[$index]), CASE_LOWER);
				$row = $list[$index];
				foreach($row as $field => $value){
					if(is_numeric($value) && str_contains($field, "kpi_")){
						$list[$index][$field] = (float) $row[$field];
					}
				}
			}

		}else{

			$list = DB::select(DB::raw($query), $query_binds);

			foreach($list as $index => $row){
				foreach($row as $field => $value){
					$field = strtolower($field);
					if(is_numeric($value) && str_contains($field, "kpi_")){
						$list[$index]->{$field} = (float) $row->{$field};					
					}
				}
			}
		}
		
		return response()->json($list);
	}

	public function exportDataFija(Request $request)
	{
		$options = [
			'distribucion' => $request->input('distribucion'),
			'filter' => $request->input('filter')
		];
		if(!is_array($options["filter"])){
			$kpis_id = [];
		}
		$kpis_id = $request->post('kpis_id');
		if(!is_array($kpis_id)){
			$kpis_id = [];
		}

		$str_fields = [];
		foreach($this->detailFieldsByDistribucion[$options['distribucion']] as $name => $row){
			$str_fields[] = $name;
		}
		$str_fields = implode(", ", $str_fields);
		// $str_fields = $this->fields_by_distribucion[$options['distribucion']];
		$str_fields = str_replace(["\t","\n", "\r", " "], ["","","",""], strtolower($str_fields));
		$fields = explode(",", $str_fields);
		$data = $this->_get_data_fija($options);
		

		$headers = [];

		$all_kpi_names = [];
		$kpi_names = [];
		$kpi_config_by_name = [];
		foreach($this->kpi_config as $row){
			$all_kpi_names[] = $row['NAME'];
			if(in_array($row['ID'], $kpis_id)){
				$kpi_names[] = $row['NAME'];
			}
			$kpi_config_by_name[$row['NAME']] = $row;
		}

		foreach ($fields as $value) {
			$label = $value;
			if(array_key_exists($value, $kpi_config_by_name)){
				$label = $kpi_config_by_name[$value]["LABEL"];
			}
			$headers[$value] = ["label" => $label];
		}

		$export = app(\AMovil\Shared\Exports\Domain\ExportService::class);
		$export->loadData($headers, $data);
		$content = $export->getWriter(WriterType::XLSX)->getOutput();
		$fileName = 'cobertura_fija_'.(new DateTime())->format('Ymd').'.xlsx';
		return response($content, 200, [
			"Content-Type" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
			"Content-Disposition" => 'attachment;filename="'.$fileName.'"'
		]);
	}

	public function getDataUbgProvByDptoCode($dptoCode)
	{
		$listDpto = $this->executeProcedure("BEGIN PSO_OSSPORTAL.SP_GET_UBGPROVBYDPTOCODE(:dptoCode, :resultado); END;", [
			"dptoCode" => ["value" => $dptoCode, "type" => ODB::CHAR],
			"resultado" => ["type" => ODB::CURSOR],
		]);
		echo json_encode($listDpto);
	}

	public function getDataUbgDistByProvCode($provCode)
	{
		$listProv = $this->executeProcedure("BEGIN PSO_OSSPORTAL.SP_GET_UBGDISTBYPROVCODE(:provcode, :resultado); END;", [
			"provcode" => ["value" => $provCode, "type" => ODB::CHAR],
			"resultado" => ["type" => ODB::CURSOR],
		]);
		echo json_encode($listProv);
	}

	public function getExtraGraphData(Request $resquest)
	{
		$distribucion = $resquest->get("distribucion");
		$kpi_id = $resquest->get("kpi_id");
		$plano = $resquest->get("plano");
		try {
			$config = $this->getExtraGraphConfig($distribucion, $kpi_id);
			if($config === null){
				throw new \Exception("Error en la configuracion de la grafica");
			}
			$data = DB::select(DB::raw($config->sql), ["plano" => $plano]);
			$response = [
				"seriesConfig" => $config->seriesConfig,
				"result_time" => $config->result_time,
				"title" => $config->title,
				"data" => $data,
			];
			return response()->json($response);
		} catch (\Throwable $th) {
			throw $th;
			return response()->json([
				"message" => $th->getMessage()
			], 500);
		}
	}

	public function getTableData(Request $resquest)
	{
		$distribucion = $resquest->get("distribucion");
		$kpi_id = $resquest->get("kpi_id");
		$id = $resquest->get("id");
		$ano = $resquest->get("ano");
		$semana = $resquest->get("semana");

		try {
			$config = $this->getTableConfig($distribucion, $kpi_id);
			if($config === null){
				throw new \Exception("Error en la configuracion de la tabla");
			}
			$params = ["id" => $id];
			if(str_contains($config->sql, ":ano")){
				$params["ano"] = $ano;
			}
			if(str_contains($config->sql, ":semana")){
				$params["semana"] = $semana;
			}
			$connection = property_exists($config, "connection") ? $config->connection : null;
			if($connection !== null){
				$sql = $config->sql;
				foreach($params as $key => $value){
					$sql = str_replace(":{$key}", "'{$value}'", $sql);
				}
				$data = DB::connection($connection)->select(DB::raw($sql));
			}else{
				$data = DB::connection($connection)->select(DB::raw($config->sql), $params);
			}
			$fields = $config->fields;
			if(property_exists($config, "pivot")){
				$data = json_decode(json_encode($data), false);
				$mappedData = [];
				foreach($data as $row){
					$key = $row->{$config->pivot->key};
					$label = $row->{$config->pivot->label};
					$value = $row->{$config->pivot->value};
					if(array_key_exists($key, $mappedData)){
						$mappedData[$key][$label] = $value;
						$fields->{$label} = ["label" => $label];
					}else{
						$mappedData[$key] = [$config->pivot->key => $key, "plano" => $row->plano];
					}
				}
				$data = [];
				foreach($mappedData as $row){
					$data[] = $row;
				}

			}
			$response = [
				"fields" => $config->fields,
				"data" => $data,
			];
			return response()->json($response);
		} catch (\Throwable $th) {
			throw $th;
			return response()->json([
				"message" => $th->getMessage()
			], 500);
		}
	}

	public function getSubTableData(Request $request)
	{
		$distribucion = $request->get("distribucion");
		$kpi_id = $request->get("subField");
		$filters = $request->get('filter', []);
		if(!is_array($filters)){
			$filters = [$filters];
		}
		$formated_filters = $this->mapFilters($filters);
		$str_filters = $this->mapFiltersToSql($formated_filters);

		try {
			$config = $this->getSubTableConfig($distribucion, $kpi_id);
			if($config === null){
				throw new \Exception("Error en la configuracion de la tabla");
			}
			if(strlen($str_filters['sql']) > 0){
				if(str_contains($config->sql, "where")){
					$str_filters['sql'] = " and ".$str_filters['sql'];
				}else{
					$str_filters['sql'] = " where ".$str_filters['sql'];
				}
			}
			$query = str_replace("[str_filters]", $str_filters['sql'], $config->sql);
			$query_binds = $str_filters['binds'];

			$connection = property_exists($config, "connection") ? $config->connection : null;
			if($connection !== null){
				foreach($query_binds as $value){
					$query = preg_replace('/'.preg_quote("?", '/').'/', "'{$value}'", $query, 1);
				}
				// dd($query);
				$data = DB::connection($connection)->select(DB::raw($query));
			}else{
				$data = DB::select(DB::raw($query), $query_binds);
			}

			$fields = $config->fields;
			if(property_exists($config, "pivot")){
				$data = json_decode(json_encode($data), false);
				$mappedData = [];
				foreach($data as $row){
					$key = $row->{$config->pivot->key};
					$label = $row->{$config->pivot->label};
					$value = $row->{$config->pivot->value};
					if(array_key_exists($key, $mappedData)){
						$mappedData[$key][$label] = $value;
						$fields->{$label} = ["label" => $label];
					}else{
						$mappedData[$key] = [$config->pivot->key => $key, "plano" => $row->plano];
					}
				}
				$data = [];
				foreach($mappedData as $row){
					$data[] = $row;
				}

			}

			$response = [
				"fields" => $config->fields,
				"data" => $data,
			];
			return response()->json($response);
		} catch (\Throwable $th) {
			throw $th;
			return response()->json([
				"message" => $th->getMessage()
			], 500);
		}
	}

	public function getVerticalesLiberados()
	{
		$query = "SELECT nodo, direccion, EDIFICIO, LATITUD, LONGITUD  FROM fija_maestro_planos_verticales";
		$result = DB::select($query);
		return response()->json($result);
	}

	// public function getTopTable($distribucionId, int $kpiId, Request $request){
	// 	$kpiConfig = null;
	// 	foreach($this->kpi_config as $row){
	// 		if((int) $row["ID"] === $kpiId){
	// 			$kpiConfig = $row;
	// 			break;
	// 		}
	// 	}
	// 	if($kpiConfig === null){
	// 		return response()->json(["message" => "La configuración no existe"], 404);
	// 	}

	// 	$config = $this->topConfigRepo->getConfigBy($distribucionId, $kpiId);

	// 	$filters = $request->get('filter', []);
	// 	if(!is_array($filters)){
	// 		$filters = [$filters];
	// 	}
	// 	$formated_filters = $this->mapFilters($filters);
	// 	$str_filters = $this->mapFiltersToSql($formated_filters);

	// 	$defaultConfig = [
	// 		"sql" => "SELECT plano, departamento, provincia, distrito, round({$kpiConfig['NAME']}) {$kpiConfig['NAME']} FROM FIJA_PLANOS_KPIS
	// 		[str_filters]
	// 		ORDER BY {$kpiConfig['NAME']} DESC
	// 		FETCH FIRST '10' ROWS ONLY",
	// 		"fields" => [
	// 			"plano" => ["label" => "PLANO", "class" => "search"],
	// 			"departamento" => ["label" => "Departamento"],
	// 			"provincia" => ["label" => "Provincia"],
	// 			"distrito" => ["label" => "Distrito"],
	// 			$kpiConfig['NAME'] => ["label" => $kpiConfig['LABEL']],
	// 		]
	// 	];

	// 	$query = $config === null ? $defaultConfig["sql"] : $config;
	// 	$query = str_replace("[str_filters]", " where ".$str_filters["sql"], $query);

	// 	$result = DB::select($query, $str_filters['binds']);
	// 	return response()->json([
	// 		"data" => $result,
	// 		"fields" => $defaultConfig["fields"]
	// 	]);
	// }	}

	public function getTopSitesTethering(){
		$data = DB::select("select * from base_sites_top_tethering_vf");
		return response()->json($data);
	}

	public function getUbigeos(){
		$data = DB::select("select
		coddep ubigeo_dpto,
		departamento,
		codprov ubigeo_prov,
		provincia,
		ubigeo,
		distrito,
		a.centroid.SDO_POINT.X longitud, a.centroid.SDO_POINT.Y latitud
		from gis_graph_distritos a
		order by distrito");
		return response()->json($data);
	}

    public function executeProcedure($sql, $params = [])
    {
        $data = DB::transaction(function($conn) use ($sql, $params){
            $pdo = $conn->getPdo();
            $stmt = $pdo->prepare($sql);

			if(count($params) > 0){
				foreach ($params as $name => $row) {
					if(!in_array($name, ["resultado"])){
						$stmt->bindParam(':'.$name, $row["value"], $row["type"]);	
					}else{
						$stmt->bindParam(':resultado', $lista, ODB::CURSOR);
					}
				}
			}else{
				$stmt->bindParam(':resultado', $lista, ODB::CURSOR);
			}


            $stmt->execute();

            oci_execute($lista, OCI_DEFAULT);
            oci_fetch_all($lista, $array, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
            oci_free_cursor($lista);

            return $array;
        });

        return $data;
    }
}
