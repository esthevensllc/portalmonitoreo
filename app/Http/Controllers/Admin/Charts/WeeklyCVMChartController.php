<?php

namespace App\Http\Controllers\Admin\Charts;

use Backpack\CRUD\app\Http\Controllers\ChartController;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

/**
 * Class WeeklyCVMChartController
 * @package App\Http\Controllers\Admin\Charts
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class WeeklyCVMChartController extends ChartController
{
    public function setup()
    {
        $this->chart = new Chart();

        $this->chart->dataset('TOTAL', 'bar', [67])
            ->color('rgb(77, 189, 116)')
            ->backgroundColor('rgba(77, 189, 116, 0.4)');


        // MANDATORY. Set the labels for the dataset points
        $this->chart->labels(['TOTAL']);

        // RECOMMENDED. Set URL that the ChartJS library should call, to get its data using AJAX.
        $this->chart->load(backpack_url('charts/cvm'));

        // OPTIONAL
        $this->chart->minimalist(false);
        $this->chart->displayAxes(true);
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
