<?php

namespace Goopil\RestFilter\Tests\Utils;

use Illuminate\Database\Eloquent\Model;
use Goopil\RestFilter\Contracts\Queryable;

class TestQueryableModel extends Model
{
    use Queryable;
}
