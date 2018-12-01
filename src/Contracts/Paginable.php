<?php

namespace Goopil\RestFilter\Contracts;

use Illuminate\Http\Request;

/**
 * Class Paginable.
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait Paginable
{
    public static function all($columns = ['*'])
    {
        $columns = is_array($columns) ? $columns : func_get_args();
        $instance = new static();
        $request = request();

        return $request->has('page') ?
            $instance->newQuery()->paginate($request->get('perPage', $instance->getPerPage()), $columns) :
            $instance->newQuery()->get($columns);
    }

    abstract public function newQuery();

    abstract public function getPerPage();
}
