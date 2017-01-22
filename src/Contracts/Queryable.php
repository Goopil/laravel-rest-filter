<?php
namespace Goopil\RestFilter\Contracts;
use Goopil\RestFilter\RestScopes;

trait Queryable {
    public static function bootQueryable()
    {
        static::addGlobalScope(new RestScopes);
    }
}