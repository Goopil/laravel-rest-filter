<?php

namespace Goopil\RestFilter\Scopes\Search;

class SearchCriteria
{
    protected $name;

    protected $relation = null;

    protected $defaultCondition;

    protected $condition = null;

    protected $values = [];

    public function __construct(array $data, $defaultCondition, array $value = [])
    {
        $this->defaultCondition = $defaultCondition;
        $this->values = $value;

        $this->formatDbRef(reset($data));

        if (count($data) === 2) {
            $this->condition = end($data);
        }

    }

    protected function formatDbRef($name)
    {
        if (str_contains($name, '.')) {
            $temp           = explode('.', $name);
            $this->name     = end($temp);
            $this->relation = reset($temp);
        } else {
            $this->name = $name;
        }
    }

    public function getCondition()
    {
        return $this->condition !== null ?
            str_replace('|', '', $this->condition) :
            $this->defaultCondition;
    }

    public function isForceWhere()
    {
        return starts_with($this->condition, '|');
    }

    public function getName()
    {
        return $this->name;
    }

    public function getRelation()
    {
        return $this->relation;
    }

    public function isFullText () {
        return str_contains($this->condition, 'like');
    }

    public function isRelation () {
        return $this->relation !== null;
    }

    public function newValue ($value) {
        $this->values[] = $value;
    }
}