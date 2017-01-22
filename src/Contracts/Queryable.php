<?php

use Goopil\RestFilter\RestScopes;

trait QueryableTrait {
    public static function bootQueryableTrait()
    {
        static::addGlobalScope(new RestScopes);
    }
}