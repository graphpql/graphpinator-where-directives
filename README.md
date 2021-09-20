# GraPHPinator Where directives [![PHP](https://github.com/infinityloop-dev/graphpinator-where-directives/workflows/PHP/badge.svg?branch=master)](https://github.com/infinityloop-dev/graphpinator-where-directives/actions?query=workflow%3APHP) [![codecov](https://codecov.io/gh/infinityloop-dev/graphpinator-where-directives/branch/master/graph/badge.svg)](https://codecov.io/gh/infinityloop-dev/graphpinator-where-directives)

:zap::globe_with_meridians::zap: Executable directives to filter values in lists.

## Introduction

This package allows client to filter list values using directives. Filtration happens on server, the most common use case is to save bandwidth when client knows exactly which data it needs.

## Installation

Install package using composer

```composer require infinityloop-dev/graphpinator-where-directives```

## How to use

> :warning: **These directives do NOT replace standard and optimal filtering provided by arguments and underlying technologies.** These directives serve only as an addition to these.

> :warning: **These directives are executable directives. Their usage is requested by client.** Think twice before including this functionality on your server.

In order to enable where directives on your server, the only thing you need to do is to put selected directives to your `Container`. You may use all or only some.

> `ListWhereDirective` has a special requirement on `infinityloop-dev/graphpinator-constraint-directives`, which needs to be enabled first if you wish to use `@listWhere`.

This package contains following directives:

- `\Graphpinator\WhereDirectives\StringWhereDirective`
- `\Graphpinator\WhereDirectives\IntWhereDirective`
- `\Graphpinator\WhereDirectives\FloatWhereDirective`
- `\Graphpinator\WhereDirectives\BooleanWhereDirective`
- `\Graphpinator\WhereDirectives\ListWhereDirective`

### Directive options

#### Common options

All directives have three common arguments

- `field` (String) - This optional argument allows filtering of nested structures. Represents path to navigate into filtered value.
    - For more information visit following [section](#Field-argument).
- `orNull` (Boolean) - Whether to accept null as a value which satisfies condition (default false).
- `not` (Boolean) - Negate the result of value conditions (default false).

#### Specific options

- `@stringWhere`
    - equals
    - contains
    - startsWith
    - endsWith
- `@intWhere` & `@floatWhere`
    - equals
    - greaterThan
    - lessThan
- `@booleanWhere`
    - equals
- `@listWhere`
    - minItems
    - maxItems

#### Priority of operations

Priority can be described using following code snippet:

```php
$conditionIsMet = $value === null
    ? $orNull
    : satisfiesSpecificConditions($value);
$valueIsFilteredOut = $conditionIsMet === $not;
```
This effectively means that `not` argument has lowest priority and negates even the `orNull` argument.

### Field argument

Let's say we have a list of `BlogPost` objects with some fields in it. 
Then, to receive all blogposts, we could have a query like this.

```graphql
{ blogposts { title content likeCount author { name } } }
```

What if client needs only popular blogpost, with more than 100 likes, and server provides no relevant filtering options?

```graphql
{ blogposts @intWhere(field: "likeCount", greaterThan: 100) { title content likeCount author { name } } }
```

What if client needs only post from author named "Foo Bar" ?

```graphql
{ blogposts @stringWhere(field: "author.name", equals: "Foo Bar") { title content likeCount author { name } } }
```
