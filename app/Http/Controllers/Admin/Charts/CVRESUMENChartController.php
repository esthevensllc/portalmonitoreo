<?php

namespace App\Http\Controllers\Admin\Charts;

use Backpack\CRUD\app\Http\Controllers\ChartController;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

/**
 * Class CVRESUMENChartController
 * @package App\Http\Controllers\Admin\Charts
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CVRESUMENChartController extends ChartController
{
    public function setup()
    {
        $this->chart = new Chart();

        $this->chart->dataset('Red', 'pie', [51, 27, 22])
        ->backgroundColor([
            'rgb(70, 127, 208)',
            'rgb(77, 189, 116)',
            'rgb(96, 92, 168)',
            'rgb(255, 193, 7)',
        ]);

        // MANDATORY. Set the labels for the dataset points
        $this->chart->labels(['NO', 'SI', 'NOTIFICADO']);

        // RECOMMENDED. Set URL that the ChartJS library should call, to get its data using AJAX.
        $this->chart->load(backpack_url('charts/cvresumen'));

        // OPTIONAL
        $this->chart->minimalist(false);
        $this->chart->displayAxes(false);
        $this->chart->displayLegend(true);
    }

    /**
     * Respond to AJAX calls with all the chart data points.
     *
     * @return json
     */
    // public function data()
    // {
    //     $users_created_today = \App\User::whereDate('created_at', today())->count();

    //     $this->chart->dataset('Users Created', 'bar', [
    //                 $users_created_today,
    //             ])
    //         ->color('rgba(205, 32, 31, 1)')
    //         ->backgroundColor('rgba(205, 32, 31, 0.4)');
    // }
}