<?php

namespace Goopil\RestFilter\Tests\Utils;

use Illuminate\Database\Eloquent\Model;
use Goopil\RestFilter\Contracts\Paginable;

class TestModel extends Model
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

    public function related()
    {
        return $this->hasMany(TestRelatedModel::class);
    }
}
