<?php

namespace Revlv;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Container\Container as Application;

abstract class BaseRepository
{
    /**
     * Holds the model instance
     *
     * @var
     */
    protected $model;

    /**
     * Holds the container instance of the repository
     * na
     * @var
     */
    protected $app;

    /**
     * Filters
     *
     * @var array
     */
    protected $filters = [];

    /**
     * Create an instance of EloquentRepository
     *
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->app = $application;

        $this->makeModel();
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    abstract public function model();

    /**
     * Create a new instance of model
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model)
        {
            throw new \Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    /**
     * Find a record by id
     *
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        $model = $this
            ->model
            ->find($id);

        return $this->exec($model);
    }

    /**
     * Create a wrapper that executes the model functions
     *
     * @param $model
     * @return mixed
     */
    public function exec($model)
    {
        return $model;
    }

    /**
     * Eager load relations
     *
     * @param array $relations
     * @return $this
     */
    public function with($relations = [])
    {
        $this->model = $this
            ->model
            ->with($relations);

        return $this;
    }

    /**
     * Return the model implemented by repository
     *
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Begin querying on the model
     *
     * @return mixed
     */
    public function query()
    {
        return $this
            ->model
            ->query();
    }

    /**
     * @param int $limit
     * @return mixed
     */
    public function paginate($limit = 10)
    {
        return $this
            ->applyQueryFilter($this->query())
            ->paginate($limit);
    }

    /**
     * Apply filter to the query being executed
     *
     * @param $query
     * @return mixed
     */
    public function applyQueryFilter($query)
    {
        // Remove all null arrays
        $filters = array_filter($this->filters);

        foreach($filters as $field => $value)
        {
            if (str_contains($value, ','))
            {
                $query->whereIn($field, explode(',', $value));

                continue;
            }

            $query->where($field, 'LIKE', "%$value%");
        }

        return $query;
    }

    /**
     * Add filters that will be used for querying later
     *
     * @param $filters
     * @return $this
     */
    public function addFilters(array $filters)
    {
        foreach($filters as $field => $value)
        {
            $this->filters[$field] = $value;
        }

        return $this;
    }

    /**
     * Return all data
     *
     * @return mixed
     */
    public function all($relations = [])
    {
        return $this
            ->applyQueryFilter($this->query())
            ->with($relations)
            ->get();
    }

    /**
     * Update the model
     *
     * @param $data
     * @param $id
     * @return mixed
     */
    public function update($data, $id)
    {
        $model = $this
            ->model
            ->find($id);

        $model
            ->update($data);

        return $model;
    }

    /**
     * Save Model
     * @param $data
     * @return mixed
     */
    public function save($data)
    {
        return $this
            ->model
            ->create($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $model = $this
            ->model
            ->find($id);

        $model->delete();

        return $model;
    }

    /**
     * Return the model by its key valued pair
     *
     * @param string $id
     * @param string $value
     * @return mixed
     */
    public function lists($id = 'id', $value = 'name')
    {
        $model =  $this->model;

        return $model->pluck($value, $id)->all();
    }
}