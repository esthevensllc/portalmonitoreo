<?php

namespace AMovil\Modules\PsoModules\Domain;

interface PsoModuleRepository
{
    public function findTitleByIdTracingAndModuleType(int $id_tracing, int $type_id);
}
