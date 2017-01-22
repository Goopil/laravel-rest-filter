# Rest api scopes
This [Eloquent](https://laravel.com/docs/5.3/eloquent) package implements some current REST filters passed via query string. And map them in form of scope.  
This is mainly an extract of what was done in [andersao/l5-repository](https://github.com/andersao/l5-repository) in term of query but to the model itself not via a repository.
A big thanks to the previous contributors for their great work ;)

* [Search](#search)
* [Filter](#filter)
* [Sort](#sort)
* [Include](#include)
* [Pagination](#pagination)
* [In / not in](#in-notin)
* [Range selector](#range-selector)

## installation
`composer require goopil/rest-query-scopes`

## declaration
### On a controller basis registration
```php
use App\User;
use Goopil\RestFilter\RestScopes;

MyController extends Controller
{
    public function __construct() 
    {
        // global constructor registration
        User::addGlobalScope(new RestScopes);
    }

    public function index (MyCustomRequest $r)
    {
        // you can pass the current request if you use it in your context.
        User::addGlobalScope(new RestScopes($r));
        
        // or the request will automaticaly be fetched if none are provided. 
        User::addGlobalScope(new RestScopes);

        return response()->json([
            data => User::all()
        ]);
    }
 }
```


### global registration (in model)
```php
use Illuminate\Database\Eloquent\Model as Eloquent;
use Goopil\RestFilter\RestScopes;

MyModel extends Eloquent
{
    public static function boot () 
    {
        parent::boot();
        
        static::addGlobalScope(new RestScopes);
    }
}
```

you can also use `Goopil\RestFilter\Contracts\Queryable` which will hook itself in the Eloquent boot process to register the filters.

# Api rest query syntax
### parameters format
The parameters support array or string with delimiters.
so this is valid  
`http://exemple.com/api/v1/users?search[1]=John&search[2]=Tom`  
and this too  
`http://exemple.com/api/v1/users?search=John;Tom` 

### delimiter
| value | function |
| :---: | :---: |
| `;` |  delimit fields or search 
| `:` |  delimit per field options

## search
the search feature make use of `Goopil\RestFilter\Contracts\Searchable`.
this interface simply specify a `searchable()` method on the model. That will return an array of fields to search into. Relations fieds are supported.
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
`http://exemple.com/api/v1/users?search=John%20Doe`

| name | type | format |
| :---: | :---: | :---: |
| search | string | any

##### Single value on registered nested searchable fields
`http://exemple.com/api/v1/users?search=administrator&searchFields=roles.name`

| name | type | format |
| :---: | :---: | :---: |
| search | string | {field1} |
| searchFields | string | {relation.field1} |

##### Single value on registered searchable fields with comparison operator
`http://exemple.com/api/v1/users?search=John&searchFields=name:like`

| name | type | format | accepted |
| :---: | :---: | :---: |  :---: |
| search | string | any
| searchFields | string | {fieldName} : {comparisonType} | comparisonType: <br> '=' or 'like'

##### Single value on specific field with comparison operator
`http://exemple.com/api/v1/users?search=john@gmail.com&searchFields=email:=`

| name | type | params format |
| :---: | :---: | :---: |
| search | string | {field1} : {value1} ; {field2} : {value2}

##### multi fields search
`http://exemple.com/api/v1/users?search=name:John Doe;email:john@gmail.com`

| name | type | format |
| :---: | :---: | :---: |
| search | string | {field1} : {value1} ; {field2} : {value2}

##### multi fields search with per field comparison operator
`http://exemple.com/api/v1/users?search=name:John;email:john@gmail.com&searchFields=name:like;email:=`  

| name | type | format |
| :---: | :---: | :---: |
| search | string | {field1} : {value1} ; {field2} : {value2} |
| searchFields | string | {field1} : {operator1} ; {field2} : {operator1} |

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
The `Goopil\RestFilter\Contracts\Paginable` rewrite the static `all()` method to parse the request for `page` & `perPage` params and call the right method if they're present.
 
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
The notIn array has precedence over the in array 
`http://exemple.com/api/v1/users?in=1;2;3;4;5;6&notIn=2`

```json
 [
     { "name": "user1", "id": 1 },
     { "name": "user3", "id": 3 },
     { "name": "user4", "id": 4 },
     { "name": "user5", "id": 5 }
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
Contributions are welcome. Just fork it and do a PR.

## todo
- [ ] add some tests
- [ ] extract documentation in static file ?