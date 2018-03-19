<?php
namespace Goopil\RestFilter\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class OrderScope
 * @package Goopil\RestFilter\Scopes
 */
class OrderScope extends BaseScope
{
    /**
     * @param Builder $builder
     * @param Model $model
     * @return Builder
     */
    public function apply(Builder $builder, Model $model)
    {
        $sortedBy = $this->request->get(config('queryScope.order.sortParam', 'sortedBy'), 'asc');
        $sortedBy = !empty($sortedBy) ? $sortedBy : 'asc';
        $orderBy = $this->request->get(config('queryScope.order.orderParam', 'orderBy'), null);

        if (isset($orderBy) && !empty($orderBy)) {
            $split = explode($this->secondary, $orderBy);
            if (count($split) > 1) {
                $table = $model->getModel()->getTable();
                $sortTable = $split[0];
                $sortColumn = $split[1];

                $split = explode($this->primary, $sortTable);
                if (count($split) > 1) {
                    $sortTable = $split[0];
                    $keyName = $table.'.'.$split[1];
                } else {
                    $prefix = rtrim($sortTable, 's');
                    $keyName = $table.'.'.$prefix.'_id';
                }

                $builder = $builder
                    ->leftJoin($sortTable, $keyName, '=', $sortTable . $model->getKeyName())
                    ->orderBy($sortColumn, $sortedBy)
                    ->addSelect($table.'.*');
            } else {
                $builder = $builder->orderBy($orderBy, $sortedBy);
            }
        }

        return $builder;
    }
}
