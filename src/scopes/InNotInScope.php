<?php
namespace Goopil\RestFilter\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class InNotInScope
 * @package Goopil\RestFilter\Scopes
 */
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
        $this->defineDefault();
        foreach ($this->contexts as $context => $methodName) {
            $param = config("queryScope.inNotIn.{$context}", $context);

            $context = $this->hasArray($this->request->get($param, null));

            if (sizeof($context) > 0) {
                $builder = $builder->{$methodName}($model->getKeyName(), $context);
            }
        }

        return $builder;
    }
}
