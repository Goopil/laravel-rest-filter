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
    protected $forceWhereSymbol = '!';

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

        return $this->handle($builder, $model);
    }

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
     * @param array $query
     *
     * @return array []
     */
    protected function parseQuery(array $query = [])
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
        $segments   = explode($this->primarySeparator, $value);
        $condition  = $this->defaultCondition;
        $forceWhere = false;

        if (count($segments) > 1) {
            if (str_contains($segments[1], $this->forceWhereSymbol)) {
                $forceWhere  = true;
                $segments[1] = str_replace($this->forceWhereSymbol, '', $segments[1]);
            }

            $condition = in_array($segments[1], $this->acceptedConditions) ? $segments[1] : $this->defaultCondition;
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

        // todo: implements check with casts typing

        return $parameters;
    }


    protected function formatWhereHasClause($query, $fieldName, $fieldQuery, $force = false)
    {
        foreach ($fieldQuery as $parameters) {
            $parameters = $this->mendSpecificFields($parameters);
            $method     = $force ? 'whereHas' : 'orWhereHas';
            $temp       = explode('.', $fieldName);
            $relation   = $temp[0];
            $fieldName  = $temp[1];

            if (method_exists($this->model, $relation)) {
                $query->{$method}($relation, function ($query) use ($relation, $fieldName, $parameters) {
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

    protected function formatWhereClause($query, $fieldName, $fieldQuery, $force = false)
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
