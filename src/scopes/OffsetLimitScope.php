<?php
namespace Goopil\RestFilter\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class OffsetLimitScope
 * @package Goopil\RestFilter\Scopes
 */
class OffsetLimitScope extends BaseScope
{
    /**
     * @param Builder $builder
     * @param Model $model
     * @return Builder
     */
    public function apply(Builder $builder, Model $model)
    {
        $this->defineDefault();
        $offset = config('queryScope.offsetLimit.offsetParam', 'offset');
        if ($this->request->has($offset)) {
            $builder = $builder->offset($this->request->get($offset));
        }

        $limit = config('queryScope.offsetLimit.limitParam', 'limit');
        if ($this->request->has($limit)) {
            $builder = $builder->limit($this->request->get($limit));
        }

        return $builder;
    }
}
