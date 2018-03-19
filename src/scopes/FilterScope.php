<?php
namespace Goopil\RestFilter\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class FilterScope
 * @package Goopil\RestFilter\Scopes
 */
class FilterScope extends BaseScope
{
    /**
     * @param Builder $builder
     * @param Model $model
     * @return Builder
     */
    public function apply(Builder $builder, Model $model)
    {
        $filter = $this->hasArray($this->request->get(config('queryScope.filter.param', 'filter'), null));

        if (sizeof($filter) > 0) {
            $builder = $builder->select($filter);
        }

        return $builder;
    }
}
