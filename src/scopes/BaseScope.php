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
     * @var array|\Illuminate\Http\Request|null|string
     */
    protected $request;

    /**
     * @var string $primarySeparator
     */
    protected $primarySeparator = ';';

    /**
     * @var string $secondarySeparator
     */
    protected $secondarySeparator = ':';

    /**
     * BaseScope constructor.
     * @param null $request
     */
//    public function __construct($request = null)
//    {
//        $this->request = $request === null ? $request : app(Request::class);
//    }

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
    * return params has array
    *
    * @param string|array $content
    * @return array
    */
    protected function hasArray($content)
    {
        if (is_array($content)) {
            return $content;
        }

        if (is_string($content) && !empty($content)) {
            return str_contains($content, $this->primarySeparator) ?
                explode($this->primarySeparator, $content) :
                [ $content ];
        }

        return [];
    }

    /**
     * @param Builder $builder
     * @param Model $model
     * @return Builder
     */
    abstract public function apply(Builder $builder, Model $model);
}
