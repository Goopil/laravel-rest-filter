<?php

namespace Goopil\RestFilter\Scopes\Search;

class FieldParser {

    protected $search;

    protected $searchFields;

    protected $casts;

    protected $searchableFields;

    public function __construct($search, $searchFields, $casts, $searchableFields)
    {
        $this->search = $search;
        $this->searchFields = $searchFields;
        $this->casts = $casts;
        $this->searchableFields = $searchableFields;
    }
}