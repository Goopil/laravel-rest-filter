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
        $hasLimit = false;

        $limitKey = config('queryScope.offsetLimit.limitParam', 'limit');
        if ($this->request->has($limitKey)) {
            $value = $this->request->get($limitKey);
            $builder = $builder->limit(is_int($value) ? $value : 15);
            $hasLimit = true;
        }

        $offsetKey = config('queryScope.offsetLimit.offsetParam', 'offset');
        if ($this->request->has($offsetKey)) {
            $value = $this->request->get($offsetKey);
            $builder = $builder->offset(is_int($value) ? $value : 0);

            /**
             * nasty work around ...
             * @see https://github.com/laravel/framework/issues/5458
             */
            if ($hasLimit == false) {
                $builder = $builder->limit(PHP_INT_MAX);
            }
        }


        return $builder;
    }
}
