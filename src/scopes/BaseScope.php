<?php

namespace Goopil\RestFilter\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope as ScopeInterface;
use Illuminate\Http\Request;

/**
 * Class BaseScope.
 */
abstract class BaseScope implements ScopeInterface
{
    /**
     * @var Request|null
     */
    protected $request;

    /**
     * @var string|null
     */
    protected $primary;

    /**
     * @var string|null
     */
    protected $secondary;

    /**
     * BaseScope constructor.
     *
     * @param null $request
     * @param null $primary
     * @param null $secondary
     */
    public function __construct($request = null, $primary = null, $secondary = null)
    {
        $this->request = $request !== null ? $request : app(Request::class);
        $this->primary = $primary !== null ? $primary : config('queryScope.primarySeparator');
        $this->secondary = $this->secondary !== null ? $secondary : config('queryScope.secondarySeparator');
    }

    /**
     * return params has array.
     *
     * @param string|array $content
     *
     * @return array
     */
    public function hasArray($content = [])
    {
        if (is_array($content)) {
            return $content;
        }

        if (is_string($content)) {
            return str_contains($content, $this->primary) ?
                explode($this->primary, $content) :
                [$content];
        }

        return [];
    }

    /**
     * @param Builder $builder
     * @param Model   $model
     *
     * @return Builder
     */
    abstract public function apply(Builder $builder, Model $model);
}
