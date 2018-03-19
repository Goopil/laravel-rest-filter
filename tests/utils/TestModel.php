<?php
namespace Goopil\RestFilter\Tests\Utils;

use Illuminate\Database\Eloquent\Model;

class TestModel extends Model {

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
}
