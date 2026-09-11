<?php

namespace AMovil\Maps\OoklaMapFija\Domain;

use DateTime;

interface OoklaMapFijaRepository
{
    public function getByMonthAndOperatorAndKpiname(DateTime $month, string $operator, string $kpiName);
    public function getKpiMonths();
    public function getKpiOperators();
    public function getLastMonths(int $months);
}
