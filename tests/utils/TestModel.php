<?php

namespace Goopil\RestFilter\Tests\Utils;

use Illuminate\Database\Eloquent\Model;
use Goopil\RestFilter\Contracts\Paginable;
use Goopil\RestFilter\Contracts\Searchable;

class TestModel extends Model implements Searchable
{
    use Paginable;

    protected $table = 'test';

    protected $fillable = [
        'bool',
        'char',
        'string',
        'text',
        'int',
        'double',
        'decimal',
        'datetime',
        'date',
        'time',
    ];

    public static function searchable()
    {
        return [
            'bool',
            'char',
            'string',
            'text',
            'int',
            'double',
            'decimal',
            'datetime',
            'date',
            'time',
            'related.bool',
            'related.char',
            'related.string',
            'related.text',
            'related.int',
            'related.double',
            'related.decimal',
            'related.datetime',
            'related.date',
            'related.time',
        ];
    }

    protected $casts = [
        'bool' => 'bool',
        'char' => 'char',
        'string' => 'string',
        'text' => 'text',
        'int' => 'int',
        'double' => 'double',
        'decimal' => 'double',
        'datetime' => 'datetime',
        'date' => 'date',
        'time' => 'time',
    ];

    public function related()
    {
        return $this->hasMany(TestRelatedModel::class);
    }
}
