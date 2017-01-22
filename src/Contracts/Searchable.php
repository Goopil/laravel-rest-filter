<?php
namespace Goopil\RestFilter\Contracts;

/**
 * Interface Searchable
 * @package Mfs\Common\Scopes\Api
 */
interface Searchable
{
    /**
     * @return array of searchable fields
     */
    public static function searchable();
}
