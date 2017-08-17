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
    protected $primarySeparator;

    /**
     * @var string $secondarySeparator
     */
    protected $secondarySeparator;

    /**
     * BaseScope constructor.
     * @param Request $request
     * @param array $separators
     */
    public function __construct(Request $request, $separators = null)
    {
        $separators = $separators === null ? ['primary' => ';', 'secondary' => ':'] : $separators;

        if (!array_key_exists('primary', $separators) || !array_key_exists('secondary', $separators)) {
            throw new \InvalidArgumentException('primary or secondary key missing');
        } elseif ($separators['primary'] === $separators['secondary']) {
            throw new \InvalidArgumentException('the primary and secondary keys can\'t be the same');
        }

        $this->request = $request !== null ? $request : Request::capture();
        $this->primarySeparator = $separators['primary'];
        $this->secondarySeparator = $separators['secondary'];
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

        if (is_string($content)) {
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
