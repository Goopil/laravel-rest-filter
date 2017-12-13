<?php
namespace Goopil\RestFilter\Contracts;
use Goopil\RestFilter\Scopes\FullScopes;

/**
 * Trait Queryable
 * @package Goopil\RestFilter\Contracts
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait Queryable {
    /**
     * boot method
     */
    public static function bootQueryable()
    {
        static::addGlobalScope(new FullScopes);
    }
}
