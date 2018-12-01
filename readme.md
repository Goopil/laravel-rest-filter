[![Latest Version on Packagist](https://img.shields.io/packagist/v/goopil/eloquent-rest-filter.svg?style=flat-square)](https://packagist.org/packages/goopil/eloquent-rest-filter)
[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://gitlab.com/goopil/lib/laravel/rest-filter/blob/master/LICENSE)
[![Build Status](https://travis-ci.org/Goopil/laravel-rest-filter.svg?branch=master)](https://travis-ci.org/Goopil/laravel-rest-filter)
[![Coverage Status](https://coveralls.io/repos/github/Goopil/laravel-rest-filter/badge.svg?branch=master)](https://coveralls.io/github/Goopil/laravel-rest-filter?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Goopil/laravel-rest-filter/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Goopil/laravel-rest-filter/?branch=master)
[![StyleCI](https://styleci.io/repos/125917556/shield?branch=master)](https://styleci.io/repos/125917556)
[![Maintainability](https://api.codeclimate.com/v1/badges/045909a491e073dbf16e/maintainability)](https://codeclimate.com/github/Goopil/laravel-rest-filter/maintainability)

# Rest api scopes
This package map some usual REST query filters to [Eloquent](https://laravel.com/docs/5.3/eloquent) scopes.
This is mainly an extract of the filters present in [andersao/l5-repository](https://github.com/andersao/l5-repository). but attachable to the model itself not via repositories.
A big thanks to the previous contributors for their great work ;)

it consist of different scopes & an execution heap to ease the registration. you can register any scope individually or use the heap to register them all.
head up: if you use them individually, the Request is a mandatory constructor parameter.

* [Search](#search)
* [Filter](#filter)
* [Sort](#sort)
* [Include](#include)
* [Pagination](#pagination)
* [In / not in](#in-notin)
* [Range selector](#range-selector)
*

you can use [qs](https://github.com/ljharb/qs) to ease the parameters formatting

## installation
install the package

`composer require goopil/eloquent-rest-filter`

> for laravel <= 5.3
add `\Goopil\RestFilter\RestScopeProvider::class` to use the config file

## publish config
you can publish it with
`php artisan vendor:publish --provider="Goopil\RestFilter\RestScopeProvider" --tag=config`

## declaration
To register all defined query Scopes, you can define the scopes for each query or on the controller to work on a routes basis.

### In Controllers
```php
use App\User;
use Goopil\RestFilter\RestScopes\Scopes\FullScope;

MyController extends Controller
{
    public function __construct()
    {
        // global constructor registration
        User::addGlobalScope(new FullScope);
    }

    public function index (MyCustomRequest $r)
    {
        // you can pass the current request if you use it in your context and specify
        the separator used for this controller only
        User::addGlobalScope(new FullScope($r, ';', '|'));

        // or the request will automaticaly be fetched if none are provided.
        User::addGlobalScope(new FullScope);

        return response()->json([
            data => User::all()
        ]);
    }
 }
```

### In Models

> manually

```php
use Illuminate\Database\Eloquent\Model as Eloquent;
use Goopil\RestFilter\RestScopes\Scopes\FullScope;

MyModel extends Eloquent
{
    public static function boot ()
    {
        parent::boot();
        static::addGlobalScope(new FullScope);
    }
}
```
> via a trait

```php
use Illuminate\Database\Eloquent\Model as Eloquent;
use Goopil\RestFilter\Contracts\Queryable;

MyModel extends Eloquent
{
    use Queryable;
}
```

# query syntax
### parameters format
The parameters support array parameters.
`http://exemple.com/api/v1/users?search[1]=John&search[2]=Tom`

### delimiter
| value | function |
| :---: | :---: |
| `;` |  delimit per field name and query value
| `|` |  delimit per field options

## search
the search feature make use of `Goopil\RestFilter\Contracts\Searchable`.
this interface simply specify a `searchable()` method on the model. That will return an array of fields to search into. Relations fields are supported.
By default or where clause ar implemented if you want to force a where close, you can add a `!` on the comparison segments
#### searchable method
```php
    static public function searchable()
    {
        return [
            'id',
            'active',
            'username',
            'email',
            'firstname',
            'lastname',
            'roles.name',
            'roles.slug',
            'roles.description',
        ];
    }
```

##### Single value on registered searchable fields
`http://exemple.com/api/v1/users?search[]=John%20Doe`

##### Single value on registered nested searchable fields
`http://exemple.com/api/v1/users?search[roles.name]=administrator`

##### Single value on registered searchable fields with comparison operator
`http://exemple.com/api/v1/users?search[]=Johns;like`

##### Single value on specific field with comparison operator
`http://exemple.com/api/v1/users?search[email]=john@gmail.com;like`

##### multi fields search
`http://exemple.com/api/v1/users?search[name]=John%20Doe&search[email]=john@gmail.com`

##### multi fields search with per field comparison operator
`http://exemple.com/api/v1/users?search[name]=john&search;like&search[email].com;like`

##### multi fields search with per field comparison operator and force where parameter
`http://exemple.com/api/v1/users?search[name]=john&search;like&search[email].com;!like`

## filter
`http://exemple.com/api/v1/users?filter=id;name`

| name | type | format |
| :---: | :---: | :---: |
| filter | string | {field1} ; {value1}

## sort
`http://exemple.com/api/v1/users?filter=id;name&orderBy=id&sortedBy=desc`

| name | type | format |
| :---: | :---: | :---: |
| orderBy | string | {field1}
| sortedBy | string | {desc}

## include
`http://exemple.com/api/v1/users?include=roles`
`http://exemple.com/api/v1/users?include=roles;sessions`
`http://exemple.com/api/v1/users?include=roles.permissions;sessions`

| name | type | format |
| :---: | :---: | :---: |
| include | string | {field1} ; {value1}

## pagination
The `Goopil\RestFilter\Contracts\Paginable` rewrite the static `all()` method
to parse the request for `page` & `perPage` params and call the `paginate()` method instead if needed.

`http://exemple.com/api/v1/users?page=1`
`http://exemple.com/api/v1/users?page=1&perPage=20`

| name | type | default |
| :---: | :---: | :---: |
| page | int | 1
| perPage | int | 15

##### output
as per laravel pagination
```json
{
    "total": 53,
    "per_page": "1",
    "current_page": 1,
    "last_page": 53,
    "next_page_url": "http:\/\/exemple.com\/api\/v1\/users?page=2",
    "prev_page_url": null,
    "from": 1,
    "to": 1,
    "data": [{
      ...
     }]
}
```


## in notIn
##### simple
`http://exemple.com/api/v1/users?in=1;2;3;4;5;6`

| name | type | format
| :---: | :---: |  :---: |
| in | ids | string with delimiter or array of int

##### exclusive in notIn filter
The `notIn` array has precedence over the `in` array
`http://exemple.com/api/v1/users?in=1;2;3;4;5;6&notIn=2`

```json
 [
     { "name": "user1", "id": 1 },
     { "name": "user3", "id": 3 },
    ...
 ]
```

| name | field | format
| :---: | :---: |  :---: |
| in | id | string with delimiter or array of int
| notIn | id | string with delimiter or array of int

## range selector
`http://exemple.com/api/v1/users?offset=10`
`http://exemple.com/api/v1/users?limit=20`
`http://exemple.com/api/v1/users?offset=10&limit=20`

| name | field | format | default
| :---: | :---: | :---: | :---: |
| offset | id | int | 1
| limit | id | int | 15

## bugs
If you find a bug or want to report somethings just drop an issue.

## contributions
Contributions are very welcome. Just fork it and do a PR.
