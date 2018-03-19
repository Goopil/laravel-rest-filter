<?php

namespace Goopil\RestFilter\Contracts;

/**
 * Interface Searchable.
 */
interface Searchable
{
    /**
     * Array of fields to be searched.
     *
     * @return array
     */
    public static function searchable();
}
