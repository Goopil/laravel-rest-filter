<?php

namespace Goopil\RestFilter\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FilterScope.
 */
class FilterScope extends BaseScope
{
    /**
     * @param Builder $builder
     * @param Model   $model
     *
     * @return Builder
     */
    public function apply(Builder $builder, Model $model)
    {
        $filter = $this->hasArray($this->request->get(config('queryScope.filter.param', 'filter'), null));

        if (count($filter) > 0) {
            $builder = $builder->select($filter);
        }

        return $builder;
    }
}
