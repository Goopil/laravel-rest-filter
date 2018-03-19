<?php

namespace Goopil\RestFilter\Scopes;

use Illuminate\Database\Eloquent\Scope as ScopeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Class BaseScope
 * @package Goopil\RestFilter\Scopes
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
        if ($request !== null) {
            $this->request = $request;
        }

        if ($primary !== null) {
            $this->primary = $primary;
        }

        if ($secondary !== null) {
            $this->secondary = $secondary;
        }
    }

    /**
     * Define primary separator
     *
     * @param $delimiter
     *
     * @return $this
     */
    public function setPrimaryDelimiter($delimiter)
    {
        $this->primary = $delimiter;

        return $this;
    }

    /**
     * Define secondary
     *
     * @param $delimiter
     *
     * @return $this
     */
    public function setSecondaryDelimiter($delimiter)
    {
        $this->secondary = $delimiter;

        return $this;
    }

    /**
     * Define current request
     *
     * @param $request
     *
     * @return $this
     */
    public function setRequest($request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Get default if vars are not set
     */
    protected function defineDefault()
    {
        if ($this->request === null) {
            $this->request = app(Request::class);
        }

        if ($this->primary === null) {
            $this->primary = config('queryScope.primarySeparator');
        }

        if ($this->secondary === null) {
            $this->secondary = config('queryScope.secondarySeparator');
        }
    }

    /**
     * return params has array
     *
     * @param string|array $content
     *
     * @return array
     */
    protected function hasArray($content)
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
     * @param Model $model
     *
     * @return Builder
     */
    abstract public function apply(Builder $builder, Model $model);
}
