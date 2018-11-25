<?php

namespace Goopil\RestFilter\Tests\Utils;

use Illuminate\Database\Eloquent\Model;

class TestRelatedModel extends Model
{
    protected $table = 'test_related';

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
}
