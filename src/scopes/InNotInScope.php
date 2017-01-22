<?php
namespace Goopil\RestFilter\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class InNotInScope extends BaseScope
{
    /**
     * @var array context to parse
     */
    protected $contexts = [
        'in' => 'whereIn',
        'notIn' => 'whereNotIn'
    ];

    /**
     * @param Builder $builder
     * @param Model $model
     * @return Builder
     */
    public function apply(Builder $builder, Model $model)
    {
        foreach ($this->contexts as $context => $methodName) {
            $context = $this->hasArray($this->request->get($context, null));

            if (sizeof($context) > 0) {
                $builder = $builder->{$methodName}($model->getKeyName(), $context);
            }
        }

        return $builder;
    }
}
