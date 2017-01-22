<?php
namespace Goopil\RestFilter\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class OffsetLimitScope extends BaseScope
{
    /**
     * @param Builder $builder
     * @param Model $model
     * @return Builder
     */
    public function apply(Builder $builder, Model $model)
    {
        if ($this->request->has('offset')) {
            $builder = $builder->offset($this->request->get('offset'));
        }

        if ($this->request->has('limit')) {
            $builder = $builder->limit($this->request->get('limit'));
        }
        
        return $builder;
    }
}
