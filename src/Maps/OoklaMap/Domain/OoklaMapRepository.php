<?php

namespace AMovil\Maps\OoklaMap\Domain;

use DateTime;

interface OoklaMapRepository
{
    public function getByMonthAndOperatorAndKpiname(DateTime $month, string $operator, string $kpiName);
    public function getKpiMonths();
    public function getKpiOperators();
    public function getLastMonths(int $months);
}
