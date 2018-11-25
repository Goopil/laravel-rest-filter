<?php

namespace Goopil\RestFilter\Tests\Utils;

use Goopil\RestFilter\Contracts\Paginable;
use Illuminate\Database\Eloquent\Model;

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
