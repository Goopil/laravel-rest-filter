# Rest api scopes
This package provide a set of scopes to implement somes query filter. It is greatly inspired by the package [andersao/l5-repository](https://github.com/andersao/l5-repository) (A big thanks to him for his great work ;) )  

The multi filter implemented in this packages a really great but not the repository pattern it use. So long story short, this is just an extract of the filters in Eloquent scopes with some minors adjustments & additions.

## installation
`composer require goopil/rest-query-scopes`

## usage
### On a controller basis registration
```
use App\User;
use Goopil\RestFilter\RestScopes;

MyController extends Controller
{
    public function __construct() 
    {
        // global constructor registration
        User::addGlobalScope(new RestScopes);
    }

    public function index ()
    {
        // per method registration
        User::addGlobalScope(new RestScopes);
        return response()->json([
            success => true,
            data => User::all()
        ]);
    }
    
    public function index (MyCustomRequest $r)
    {
        User::addGlobalScope(new RestScopes($r));
        return response()->json([
            success => true,
            data => User::all()
        ]);
    }
 }
```

you can pass the current request if you use it in the method.
The package will fetch the current request if null is provided.
So if you do have a request object you should pass it to it.

### global registration (in model)
```
use Illuminate\Database\Eloquent\Model as Eloquent;
MyModel extends Eloquent
{
    public static function boot () 
    {
        parent::boot();
        static::addGlobalScope(app(RestScopes::class));
    }
}
```

# Api rest query syntax
## parameters format
the params parser work in 2 steps. first it check if the current params is an array (http). 
* If true, it leave the params has is
* if not, it split the array with the primary delimiter

## delimiter
| value | function |
| :---: | :---: |
| `;` |  delimit fields or search 
| `:` |  delimit per field options

## search
To be able to filter fields on an Eloquent model. The mentioned model MUST implement the `Goopil\RestFilter\Contracts\Searchable`.
this interface simply specify a searchable method on the model.
yes you can filter in relations too !
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
`http://exemple.com/api/v1/users?include=roles.permissions`

| name | type | format |
| :---: | :---: | :---: |
| include | string | {field1} ; {value1}

## pagination
You must add the `Goopil\RestFilter\Contracts\Paginable` to your models if you want to use it.
it rewrite the static `all()` method to parse the request for `page` & `perPage` params and call the right method if they're present.
 
`http://exemple.com/api/v1/users?page=1`
`http://exemple.com/api/v1/users?page=1&perPage=20`

| name | type | default |
| :---: | :---: | :---: |
| page | int | 1
| perPage | int | 15

##### output
as per laravel pagination
```js
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

```js
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