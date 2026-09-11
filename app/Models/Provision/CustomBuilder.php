<?php

namespace App\Models\Provision;

use Illuminate\Database\Query\Builder as QueryBuilder;

class CustomBuilder extends \Illuminate\Database\Eloquent\Builder
{
    /**
     * Create a new Eloquent query builder instance.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return void
     */
    public function __construct(QueryBuilder $query)
    {
        $this->query = $query;
        $this->model = new ProvisionSharedModel();
        //dd($this->query->all());
        //dd(collect($this->query->get(["*"])->all()));
    }
    
    /**
     * Execute the query as a "select" statement.
     *
     * @param  array|string  $columns
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function get($columns = ['*'])
    {
        $builder = $this->applyScopes();

        // If we actually found models we will also eager load any relationships that
        // have been specified as needing to be eager loaded, which will solve the
        // n+1 query issue for the developers to avoid running a lot of queries.
        /*if (count($models = $builder->getModels($columns)) > 0) {
            $models = $builder->eagerLoadRelations($models);
        }

        return $builder->getModel()->newCollection($models);
        */
        $models = $builder->getModels($columns);
        return (new ProvisionSharedModel())->newCollection($models);
    }

    /**
     * Get the hydrated models without eager loading.
     *
     * @param  array|string  $columns
     * @return \Illuminate\Database\Eloquent\Model[]|static[]
     */
    public function getModels($columns = ['*'])
    {
        // dd(collect($this->query->get($columns)->all()));
        
        $data = $this->query->get($columns)->all();
        //dd($data);
        $models = [];
        foreach($data as $row){
            $attributes = json_decode(json_encode($row), true);
            $model = new ProvisionSharedModel;
            foreach($attributes as $key => $value){
                $model->{$key} = $value;
            }
            $models[] = $model;
        }
        return $models;
    }
}
