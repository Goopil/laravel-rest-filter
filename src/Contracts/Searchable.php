<?php

namespace Goopil\RestFilter\Contracts;

/**
 * Interface Searchable
 * @package Goopil\RestFilter\Contracts
 */
interface Searchable
{
    /**
     * Array of fields to be searched
     *
     * @return array
     */
    static public function searchable();
}
