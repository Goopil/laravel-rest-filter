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
        $existing = array_filter($include, function($includeName) use ($model) {
            return method_exists($model, $includeName);
        });

        if (sizeof($existing) > 0) {
            $builder = $builder->with($existing);
        }

        return $builder;
    }
}
