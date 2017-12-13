<?php

namespace Goopil\RestFilter\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Goopil\RestFilter\Contracts\Searchable;

/**
 * Class SearchScope
 * @package Goopil\RestFilter\Scopes
 */
class SearchScope extends BaseScope
{
    /**
     * valid db conditions
     *
     * @var array
     */
    protected $acceptedConditions = ['=', '>=', '<=', '<', '>', 'like', 'ilike'];

    /**
     * default db condition when not set
     *
     * @var string
     */
    protected $defaultCondition = '=';

    /**
     * force where query symbol
     *
     * @var string
     */
    protected $forceWhereSymbol;

    /**
     * searchable fields
     *
     * @var
     */
    protected $validFields;

    /**
     * model table
     *
     * @var string
     */
    protected $modelTableName;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @param Builder $builder
     * @param Model $model
     *
     * @return Builder
     */
    public function apply(Builder $builder, Model $model)
    {
        if ( ! $model instanceof Searchable) {
            return $builder;
        }

        $this->forceWhereSymbol = config('queryScope.forceWhereOperator', '!');

        return $this->handle($builder, $model);
    }

    /**
     * @param Builder $builder
     * @param Searchable $model
     *
     * @return $this|Builder
     */
    protected function handle(Builder $builder, Searchable $model)
    {
        $search            = $this->request->get('search', null);
        $this->validFields = $model::searchable();

        if ($search === null || ! is_array($this->validFields) || count($this->validFields) < 1) {
            return $builder;
        }

        $this->model          = new $model;
        $this->modelTableName = $this->model->getTable();
        $fieldsByType         = $this->parseQuery($search);
        $isFirstField         = true;

        return $builder->where(function (Builder $query) use ($fieldsByType, $isFirstField) {
            foreach ($fieldsByType['and'] as $fieldName => $fieldQuery) {
                if (str_contains($fieldName, '.')) {
                    $this->formatWhereHasClause($query, $fieldName, $fieldQuery, true);
                } else {
                    $this->formatWhereClause($query, $fieldName, $fieldQuery, true);
                }
            }

            $isOrAlone = count($fieldsByType['or']) > 1 ? 'where' : 'orWhere';
            $query->{$isOrAlone}(function ($query) use ($fieldsByType) {
                foreach ($fieldsByType['or'] as $fieldName => $fieldQuery) {
                    if (str_contains($fieldName, '.')) {
                        $this->formatWhereHasClause($query, $fieldName, $fieldQuery);
                    } else {
                        $this->formatWhereClause($query, $fieldName, $fieldQuery);
                    }
                }

            });

            return $query;
        });
    }

    /**
     * format fields from query
     *
     * @param mixed $query
     *
     * @return array []
     */
    protected function parseQuery($query)
    {
        $query  = $this->hasArray($query);
        $result = ['or' => [], 'and' => []];

        foreach ($query as $fieldName => $value) {
            list($type, $tempValue) = $this->parseCondition($value);

            if (in_array($fieldName, $this->validFields, true)) {
                $result[$type][$fieldName][] = $tempValue;
            } elseif (is_int($fieldName)) {
                foreach ($this->validFields as $validField) {
                    $result[$type][$validField][] = $tempValue;
                }
            }
        }

        return $result;
    }

    /**
     * parse condition symbol and determine if force where is needed
     *
     * @param $value
     *
     * @return array
     */
    protected function parseCondition($value)
    {
        $segments   = explode($this->secondary, $value);
        $condition  = $this->defaultCondition;
        $forceWhere = false;

        if (count($segments) > 1) {
            $tempCondition = $segments[1];
            if (str_contains($tempCondition, $this->forceWhereSymbol)) {
                $forceWhere    = true;
                $tempCondition = str_replace($this->forceWhereSymbol, '', $tempCondition);
            }

            $condition = in_array($tempCondition, $this->acceptedConditions) ? $tempCondition : $this->defaultCondition;
        }

        return [
            $forceWhere ? 'and' : 'or',
            [
                'value'     => $segments[0],
                'condition' => $condition,
            ]
        ];
    }

    protected function mendSpecificFields($parameters)
    {
        if (in_array($parameters['condition'], ['like', 'ilike'])) {
            $parameters['value'] = "%{$parameters['value']}%";
        }

        // todo: implements check with casts typing from model

        return $parameters;
    }


    protected function formatWhereHasClause(Builder $query, $fieldName, $fieldQuery, $force = false)
    {
        foreach ($fieldQuery as $parameters) {
            $parameters = $this->mendSpecificFields($parameters);
            $method     = $force ? 'whereHas' : 'orWhereHas';
            $temp       = explode('.', $fieldName);
            $relation   = $temp[0];
            $fieldName  = $temp[1];

            if (method_exists($this->model, $relation)) {
                $query->{$method}($relation, function (Builder $query) use ($relation, $fieldName, $parameters) {
                    $query->where(
                        "{$relation}.{$fieldName}",
                        $parameters['condition'],
                        $parameters['value']
                    );
                });
            } else {
                throw new \InvalidArgumentException("Relation {$relation} not implemented in " . get_class($this->model));
            }

        }

        return $query;
    }

    /**
     * parse force where symbol
     *
     * @param Builder $query
     * @param string $fieldName
     * @param arry $fieldQuery
     * @param bool $force
     *
     * @return Builder
     */
    protected function formatWhereClause(Builder $query, $fieldName, $fieldQuery, $force = false)
    {
        foreach ($fieldQuery as $parameters) {
            $parameters = $this->mendSpecificFields($parameters);
            $method     = $force ? 'where' : 'orWhere';

            $query->{$method}(
                "{$this->modelTableName}.{$fieldName}",
                $parameters['condition'],
                $parameters['value']
            );
        }

        return $query;
    }
}
