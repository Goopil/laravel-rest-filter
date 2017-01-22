<?php
namespace Goopil\RestFilter\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class IncludeScope extends BaseScope
{
    /**
     * @param Builder $builder
     * @param Model $model
     * @return Builder
     */
    public function apply(Builder $builder, Model $model)
    {
        $include = $this->hasArray($this->request->get('include', null));

        if (sizeof($include) > 0) {
            $builder = $builder->with($include);
        }

        return $builder;
    }
}
