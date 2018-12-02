<?php

namespace Goopil\RestFilter\Tests\Utils;

use Goopil\RestFilter\Contracts\Queryable;
use Illuminate\Database\Eloquent\Model;

class TestQueryableModel extends Model
{
    use Queryable;
}
