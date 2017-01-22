<?php
namespace Goopil\RestFilter\Contracts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class Paginable
 * @package Goopil\RestFilter\Contracts
 */
trait Paginable
{
    public static function all($columns = ['*'])
    {
        $columns = is_array($columns) ? $columns : func_get_args();
        /**
         * @var $instance Model
         */
        $instance = new static;
        /**
         * @var $request Request
         */
        $request = app(Request::capture());

        return $request->has('page') ?
            $instance->newQuery()->paginate($request->get('perPage', $instance->getPerPage()), $columns) :
            $instance->newQuery()->get($columns);
    }
}
