# **QueryBuilder**

A simple and complete query builder to add practicality to your project

---
## **INDEX**

- ***Instalation***
- ***Connection***
- ***Select statement***
    - ***More Examples***
    - ***Expressions***
- ***Insert statement***
- ***Update statement***
- ***Delete statement***

---
## **Instalation**

```bash
composer require lucasmialichi/query-builder
```

## **Connection:**
---
```php
// build connection ...
$qb = new QueryBuilder(
    new Connection(
        "127.0.0.1",  // host
        "root",       // user 
        123,          // password
        "teste"       // db name
    )
);
```
---
## **Select statement:**
```php
$qb->select()->from('users')->where('id', '=', ':Id');
$qb->addParam(':Id', 1);
$result = $qb->execute(); //Instance of QueryBuilder/Connection/QueryResult
```
the example above converted to SQL:

```sql 
SELECT * FROM  users where id = :Id
```

Methods for query result:

- fetchAll: fetch all result as an associative array
- fetchFunction: fetch each row implementing a userfunction
- fetchAssociative: fetch using iteration (Generator)
- count: count numbers of rows before fetch

## **more examples:**

**PHP:**
```php
$qb->select([
    "id" => "identifier"
    "name" => "userName",
    "birth_date" => "birthDate"
])
->from('users', "u")
->join("address", "u.address_id = a.id", "a")
->where('id', '=', ':Id')
->orWhere("birth_date", ">", date("Y-m-d H:i:s"))
->limit(10)
->offset(1);
```

**SQL:**

```sql
SELECT id AS identifier, name AS userName, birth_date AS birthDate FROM users u INNER JOIN address a ON u.address_id = a.id WHERE ( id = :Id ) OR ( birth_date > 2023-12-18 14:30:43 ) LIMIT 10 OFFSET 1
```
---
## **Expressions:**

There are two ways to instanciate expressions in this qb

staticly calling directly  QueryBuilder class

```php
$expression = QueryBuilder::Expr();
```

Or if you aready had initilized queryBuilder you can call it in any moment of the query

```php
$qb->select()->from('users')->where(
    $qb->expr("id")->in([1,2,3])->or()->between(1,10)
);
```

You can also use expression in select statement, like:
```php
$qb->select(
    $qb->expr()->caseWhen("id = 1", "0")
        ->when("id = 2", "1")
        ->else("2")
        ->end()
)->from('users');
```
----
## **Insert statement**
**PHP**
```php
$qb->insert([
    "name" => "alfredo",
    "birth_date" => "1988-06-11"
])->into("users");
```
the values are automatically binded to the query as parameters

**SQL:**
```sql
    INSERT INTO users (name, birth_date) VALUES ('alfredo', '1988-06-11')
```
---
## **Update statement**
**PHP**
```php
$qb->update([
    "name" => "alfredo",
    "birth_date" => "1988-06-11"
])->from("users")
  ->where("id", "=", ":userId")
  ->addParam(":userId", 2);
```
the values are automatically binded to the query as parameters

**SQL:**
```sql
UPDATE users SET name = 'alfredo', birth_date = '1988-06-11' WHERE id = :userId
```
---
## **Delete statement**

```php
$qb->delete()->from("users")->where($qb->expr("id")->notBetween(1,5))
```

**SQL:**

```sql
DELETE FROM users WHERE (id not between 1 and 5)
```