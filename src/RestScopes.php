<?php
namespace Goopil\RestFilter;

use Illuminate\Database\Eloquent\Scope as ScopeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use Goopil\RestFilter\Scopes\FilterScope;
use Goopil\RestFilter\Scopes\IncludeScope;
use Goopil\RestFilter\Scopes\InNotInScope;
use Goopil\RestFilter\Scopes\OffsetLimitScope;
use Goopil\RestFilter\Scopes\OrderScope;
use Goopil\RestFilter\Scopes\SearchScope;

/**
 * Class RestScopes
 * @package Goopil\RestFilter
 */
class RestScopes implements ScopeInterface
{
    /**
     * @var Request
     */
    protected $request;

    protected $delimiters = [
        'primary' => ';',
        'secondary' => ':'
    ];

    /**
     * @var array $scopes
     */
    protected $scopes = [
        InNotInScope::class,
        SearchScope::class,
        OrderScope::class,
        FilterScope::class,
        IncludeScope::class,
        OffsetLimitScope::class
    ];

    /**
     * ApiRequestFilter constructor.
     * @param Request $request
     */
    public function __construct($request = null)
    {
        $this->request = $request !== null ? $request : Request::capture();
    }

    /**
     * @param array $delimiters
     * @return $this
     */
    public function setDelimiters(array $delimiters)
    {
        $this->delimiters = $delimiters;

        return $this;
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function setDelimiter($name, $value)
    {
        $this->delimiters[$name] = $value;

        return $this;
    }

    /**
     * scopes getters
     * @return array
     */
    public function getScopes ()
    {
        return $this->scopes;
    }

    /**
     * reset and set scopes to be executed
     * @param $scopes string|array
     * @return $this
     */
    public function setScopes ($scopes)
    {
        $scopes = is_array($scopes) ? $scopes : func_get_args();
        $this->scopes = [];
        $this->assemble($scopes);

        return $this;
    }

    /**
     * add scopes to existing ones
     * @param $scopes string|array
     * @return $this
     */
    public function addScopes ($scopes)
    {
        $scopes = is_array($scopes) ? $scopes : func_get_args();
        $this->assemble($scopes);

        return $this;
    }


    /**
     * assemble scopes
     * @param $scopes
     */
    protected function assemble(array $scopes)
    {
        $errors = [];
        foreach($scopes as $scope) {
            if ($scope instanceof ScopeInterface) {
                if (!in_array($scope, $this->scopes)) {
                    $this->scopes[] = $scope;
                }
            } else {
                $errors[] = 'The class '. get_class($scope) . 'doesn\'t implements ' . ScopeInterface::class;
            }
        }

        if (sizeof($errors) > 0) {
            throw new \InvalidArgumentException(implode(' \n', $errors));
        }
    }

    /**
     * @param Builder $builder
     * @param Model $model
     * @return Builder
     */
    public function apply(Builder $builder, Model $model)
    {
        foreach ($this->scopes as $scope) {
            $current = new $scope($this->request, $this->delimiters);
            $builder = $current->apply($builder, $model);
        }

        return $builder;
    }
}
