<?php

namespace App\Http\Controllers\Admin\Charts;

use Backpack\CRUD\app\Http\Controllers\ChartController;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

/**
 * Class WeeklyBarCVMChartController
 * @package App\Http\Controllers\Admin\Charts
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class WeeklyBarCVMChartController extends ChartController
{
    public function setup()
    {
        $this->chart = new Chart();

        $this->chart->dataset('2G', 'bar', [10, 20, 80, 30, 10, 20, 80, 30])
            ->color('rgb(77, 189, 116)')
            ->backgroundColor('rgba(77, 189, 116, 0.4)');

        $this->chart->dataset('3G', 'bar', [20, 30, 60, 40, 20, 30, 60, 40])
            ->color('rgb(96, 92, 168)')
            ->backgroundColor('rgba(96, 92, 168, 0.4)');

        $this->chart->dataset('4G', 'bar', [30, 40, 100, 50, 30, 40, 100, 50])
            ->color('rgb(255, 193, 7)')
            ->backgroundColor('rgba(255, 193, 7, 0.4)');

        $this->chart->dataset('5G', 'bar', [40, 50, 80, 10, 40, 50, 80, 10])
            ->color('rgba(70, 127, 208, 1)')
            ->backgroundColor('rgba(70, 127, 208, 0.4)');

        // MANDATORY. Set the labels for the dataset points
        $this->chart->labels(['2G', '3G', '4G', '5G']);

        // RECOMMENDED. Set URL that the ChartJS library should call, to get its data using AJAX.
        $this->chart->load(backpack_url('charts/barcvm'));

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