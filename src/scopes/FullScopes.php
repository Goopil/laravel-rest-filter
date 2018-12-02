<?php

namespace Goopil\RestFilter\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Scope as ScopeInterface;

/**
 * Class FullScopes.
 */
class FullScopes extends BaseScope implements ScopeInterface
{
    /**
     * @var array
     */
    protected $scopes = [
        InNotInScope::class,
        FilterScope::class,
        IncludeScope::class,
        OffsetLimitScope::class,
        SearchScope::class,
        OrderScope::class,
    ];

    /**
     * reset and set scopes to be executed.
     *
     * @param $scopes string|array
     *
     * @return $this
     */
    public function setScopes($scopes)
    {
        $this->scopes = [];
        $scopes = is_array($scopes) ? $scopes : func_get_args();
        $this->assemble($scopes);

        return $this;
    }

    /**
     * add scopes to existing ones.
     *
     * @param $scopes string|array
     *
     * @return $this
     */
    public function addScopes($scopes)
    {
        $scopes = is_array($scopes) ? $scopes : func_get_args();
        $this->assemble($scopes);

        return $this;
    }

    /**
     * assemble scopes.
     *
     * @param $scopes
     */
    protected function assemble(array $scopes)
    {
        $errors = [];
        foreach ($scopes as $scope) {
            if (is_string($scope)) {
                $scope = app($scope);
            }

            if ($scope instanceof ScopeInterface) {
                if (! in_array($scope, $this->scopes)) {
                    $this->scopes[] = $scope;
                }
            } else {
                $errors[] = 'The class '.get_class($scope).'doesn\'t implements '.ScopeInterface::class;
            }
        }

        if (count($errors) > 0) {
            throw new \InvalidArgumentException(implode(' \n', $errors));
        }
    }

    /**
     * Process scopes.
     *
     * @param Builder $builder
     * @param Model   $model
     *
     * @return Builder
     */
    public function apply(Builder $builder, Model $model)
    {
        foreach ($this->scopes as $scope) {
            /** @var $current ScopeInterface */
            $current = new $scope($this->request, $this->primary, $this->secondary);
            $current->apply($builder, $model);
        }

        return $builder;
    }
}
