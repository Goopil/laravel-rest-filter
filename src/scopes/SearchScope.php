<?php
namespace Goopil\RestFilter\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Goopil\RestFilter\Contracts\Searchable;

/**
 * Class SearchScope
 * @package Mfs\Common\Scopes\Api
 */
class SearchScope extends BaseScope
{
    protected $acceptedConditions =  ['=', '>=', '<=', '<', '>', 'like', 'ilike'];

    /**
     * @param Builder $builder
     * @param Model $model
     * @return Builder
     */
    public function apply(Builder $builder, Model $model)
    {
        if (! $model instanceof Searchable) {
            return $builder;
        }

        return $this->handle($builder, $model);
    }

    protected function handle(Builder $builder,Model $model)
    {
        $fieldsSearchable = $model::searchable();
        $search = $this->request->get('search', null);
        $searchFields = $this->request->get('searchFields', null);
        $casts = $model->getCasts();
        
        if ($search && is_array($fieldsSearchable) && count($fieldsSearchable)) {
            $searchFields = is_array($searchFields) || is_null($searchFields) ?
                $searchFields :
                explode($this->primarySeparator, $searchFields);
           
            $fields = $this->parserFieldsSearch($fieldsSearchable, $searchFields);
            $isFirstField = true;
            $searchData = $this->parserSearchData($search);
            $search = $this->parserSearchValue($search);
            $forceAndWhere = false;

            return $builder->where(function (Builder $query) use (
                $fields,
                $search,
                $searchData,
                $isFirstField,
                $forceAndWhere,
                $casts,
                $model
            ) {
                foreach ($fields as $field => $condition) {

                    $value = null;
                    $condition = trim(strtolower($condition));
                    if (substr( $condition, 0, 1) === "|") {
                        $forceAndWhere = true;
                        $condition = str_replace('|', '', $condition);
                    }

                    $invalidSearch = false;
    
                    if (isset($searchData[$field])) {
                        $value = ($condition == "like" || $condition == "ilike") ?
                            "%{$searchData[$field]}%" :
                            $searchData[$field];
                    } else {
                        if (!is_null($search)) {
                            $value = ($condition == "like" || $condition == "ilike") ? "%{$search}%" : $search;
                        }
                    }

                    if (array_key_exists($field, $casts) && $casts[$field] === 'boolean') {
                        $invalidSearch =  !is_bool($value);
                    }

                    $relation = null;
                    if (stripos($field, '.')) {
                        $explode = explode('.', $field);
                        $field = array_pop($explode);
                        $relation = implode('.', $explode);
                    }

                    $modelTableName = $model->getTable();
                    
                    if (!$invalidSearch) {
                        if ($isFirstField || $forceAndWhere) {
                            if (!is_null($value)) {
                                if (!is_null($relation)) {
                                    $query->whereHas($relation, function ($query) use ($field, $condition, $value) {
                                        $query->where($field, $condition, $value);
                                    });
                                } else {
                                    $query->where($modelTableName . '.' . $field, $condition, $value);
                                }
                                $isFirstField = false;
                            }
                        } else {
                            if (!is_null($value)) {
                                if (!is_null($relation)) {
                                    $query->orWhereHas($relation, function ($query) use ($field, $condition, $value) {
                                        $query->where($field, $condition, $value);
                                    });
                                } else {
                                    $query->orWhere($modelTableName . '.' . $field, $condition, $value);
                                }
                            }
                        }
                    }

                    $forceAndWhere = false;
                }
            });
        } else {
            return $builder;
        }
    }

    /**
     * @param $search
     *
     * @return array
     */
    protected function parserSearchData($search)
    {
        $searchData = [];

        if (stripos($search, $this->secondarySeparator)) {
            $fields = explode($this->primarySeparator, $search);

            foreach ($fields as $row) {
                try {
                    list($field, $value) = explode($this->secondarySeparator, $row);
                    $searchData[$field] = $value;
                } catch (\Exception $e) {
                    //Surround offset error
                }
            }
        }

        return $searchData;
    }

    /**
     * @param $search
     *
     * @return null
     */
    protected function parserSearchValue($search)
    {
        if (stripos($search, $this->primarySeparator) || stripos($search, $this->secondarySeparator)) {
            $values = explode($this->primarySeparator, $search);
            foreach ($values as $value) {
                $s = explode($this->secondarySeparator, $value);
                if (count($s) == 1) {
                    return $s[0];
                }
            }

            return null;
        }

        return $search;
    }

    /**
     * @param array $fields
     * @param array|null $searchFields
     * @return array
     */
    protected function parserFieldsSearch(array $fields = [], array $searchFields = null)
    {
        if (!is_null($searchFields) && count($searchFields)) {
            $originalFields = $fields;
            $fields = [];

            foreach ($searchFields as $index => $field) {
                $field_parts = explode($this->secondarySeparator, $field);
                $temporaryIndex = array_search($field_parts[0], $originalFields);

                if (count($field_parts) == 2) {
                    if (in_array(str_replace('|', '', $field_parts[1]), $this->acceptedConditions)) {
                        unset($originalFields[$temporaryIndex]);
                        $field = $field_parts[0];
                        $condition = $field_parts[1];
                        $originalFields[$field] = $condition;
                        $searchFields[$index] = $field;
                    }
                }
            }

            foreach ($originalFields as $field => $condition) {
                if (is_numeric($field)) {
                    $field = $condition;
                    $condition = "=";
                }
                if (in_array($field, $searchFields)) {
                    $fields[$field] = $condition;
                }
            }

            if (count($fields) == 0) {
                throw new \InvalidArgumentException('these fields: '. implode(',', $searchFields) . ' are not supported');
            }
        }

        return $fields;
    }
}
