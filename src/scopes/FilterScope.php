<?php
namespace Goopil\RestFilter\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FilterScope extends BaseScope
{
    /**
     * @param Builder $builder
     * @param Model $model
     * @return Builder
     */
    public function apply(Builder $builder, Model $model)
    {
        $filter = $this->request->get('filter', null);

        if (isset($filter) && !empty($filter)) {
            if (is_string($filter)) {
                $filter = explode($this->primarySeparator, $filter);
            }

            $builder = $builder->select($filter);
        }
        
        return $builder;
    }
}
