<?php

declare(strict_types = 1);

namespace Graphpinator\WhereDirectives\Tests\Integration;

use Infinityloop\Utils\Json;

final class FilterTest extends \PHPUnit\Framework\TestCase
{
    public function stringDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => '{ stringList @stringWhere(equals: "aaa") }',
                ]),
                Json::fromNative((object) ['data' => ['stringList' => ['aaa', 'aaa']]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ stringList @stringWhere(not: true, equals: "aaa") }',
                ]),
                Json::fromNative((object) ['data' => ['stringList' => ['bbb', 'ccc', 'abc']]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ stringList @stringWhere(contains: "b") }',
                ]),
                Json::fromNative((object) ['data' => ['stringList' => ['bbb', 'abc']]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ stringList @stringWhere(not: true, contains: "b") }',
                ]),
                Json::fromNative((object) ['data' => ['stringList' => ['aaa', 'ccc', 'aaa']]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ stringList @stringWhere(startsWith: "a") }',
                ]),
                Json::fromNative((object) ['data' => ['stringList' => ['aaa', 'aaa', 'abc']]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ stringList @stringWhere(not: true, startsWith: "a") }',
                ]),
                Json::fromNative((object) ['data' => ['stringList' => ['bbb', 'ccc']]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ stringList @stringWhere(endsWith: "c") }',
                ]),
                Json::fromNative((object) ['data' => ['stringList' => ['ccc', 'abc']]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ stringList @stringWhere(not: true, endsWith: "c") }',
                ]),
                Json::fromNative((object) ['data' => ['stringList' => ['aaa', 'bbb', 'aaa']]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ stringList @stringWhere(not: true, equals: "aaa", endsWith: "c") }',
                ]),
                Json::fromNative((object) ['data' => ['stringList' => ['aaa', 'bbb', 'ccc', 'aaa', 'abc']]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ stringList @stringWhere(not: true, equals: "aaa") @stringWhere(not: true, endsWith: "c") }',
                ]),
                Json::fromNative((object) ['data' => ['stringList' => ['bbb']]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ stringList @stringWhere(startsWith: "a", endsWith: "c") }',
                ]),
                Json::fromNative((object) ['data' => ['stringList' => ['abc']]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ stringList @stringWhere(startsWith: "a") @stringWhere(endsWith: "c") }',
                ]),
                Json::fromNative((object) ['data' => ['stringList' => ['abc']]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ stringNullList @stringWhere(equals: "aaa") }',
                ]),
                Json::fromNative((object) ['data' => ['stringNullList' => ['aaa', 'aaa']]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ stringNullList @stringWhere(equals: "aaa", orNull: true) }',
                ]),
                Json::fromNative((object) ['data' => ['stringNullList' => ['aaa', null, 'aaa']]]),
            ],
        ];
    }

    public function simpleIntDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => '{ intList @intWhere(equals: 2) }',
                ]),
                Json::fromNative((object) ['data' => ['intList' => [2]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ intList @intWhere(not: true, equals: 2) }',
                ]),
                Json::fromNative((object) ['data' => ['intList' => [1, 3, 4, 5]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ intList @intWhere(greaterThan: 2) }',
                ]),
                Json::fromNative((object) ['data' => ['intList' => [2, 3, 4, 5]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ intList @intWhere(not:true, greaterThan: 2) }',
                ]),
                Json::fromNative((object) ['data' => ['intList' => [1]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ intList @intWhere(lessThan: 2) }',
                ]),
                Json::fromNative((object) ['data' => ['intList' => [1, 2]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ intList @intWhere(not: true, lessThan: 2) }',
                ]),
                Json::fromNative((object) ['data' => ['intList' => [3, 4, 5]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ intNullList @intWhere(not: true, lessThan: 2, orNull: true) }',
                ]),
                Json::fromNative((object) ['data' => ['intNullList' => [3, 4, 5]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ intNullList @intWhere(lessThan: 2, orNull: true) }',
                ]),
                Json::fromNative((object) ['data' => ['intNullList' => [1, 2, null]]]),
            ],
        ];
    }

    public function simpleFloatDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => '{ floatList @floatWhere(equals: 1.0) }',
                ]),
                Json::fromNative((object) ['data' => ['floatList' => [1.0]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ floatList @floatWhere(not: true, equals: 1.0) }',
                ]),
                Json::fromNative((object) ['data' => ['floatList' => [2.0, 3.0, 4.0, 5.0]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ floatList @floatWhere(greaterThan: 1.01) }',
                ]),
                Json::fromNative((object) ['data' => ['floatList' => [2.0, 3.0, 4.0, 5.0]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ floatList @floatWhere(not:true, greaterThan: 1.01) }',
                ]),
                Json::fromNative((object) ['data' => ['floatList' => [1.00]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ floatList @floatWhere(lessThan: 1.00) }',
                ]),
                Json::fromNative((object) ['data' => ['floatList' => [1.00]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ floatList @floatWhere(not:true, lessThan: 2.00) }',
                ]),
                Json::fromNative((object) ['data' => ['floatList' => [3.0, 4.0, 5.0]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ floatNullList @floatWhere(lessThan: 2.0, orNull: true) }',
                ]),
                Json::fromNative((object) ['data' => ['floatNullList' => [1.0, 2.0, null]]]),
            ],
        ];
    }

    public function simpleBoolDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => '{ boolList @booleanWhere(equals: true) }',
                ]),
                Json::fromNative((object) ['data' => ['boolList' => [true]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ boolList @booleanWhere(equals: false) }',
                ]),
                Json::fromNative((object) ['data' => ['boolList' => [false, false]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ boolNullList @booleanWhere(equals: true, orNull: true) }',
                ]),
                Json::fromNative((object) ['data' => ['boolNullList' => [true, null]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ boolNullList @booleanWhere(equals: false, orNull: true) }',
                ]),
                Json::fromNative((object) ['data' => ['boolNullList' => [false, null, false]]]),
            ],
        ];
    }

    public function simpleListDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => '{ listList @listWhere(minItems: 2) }',
                ]),
                Json::fromNative((object) ['data' => ['listList' =>
                    [[1, 0], [2, 1], [3, 2], [4, 3], [5, 4]],
                ]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ listList @listWhere(minItems: 2, not: true) }',
                ]),
                Json::fromNative((object) ['data' => ['listList' => []]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ listList @listWhere(minItems: 3) }',
                ]),
                Json::fromNative((object) ['data' => ['listList' => []]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ listList @listWhere(maxItems: 2) }',
                ]),
                Json::fromNative((object) ['data' => ['listList' =>
                    [[1, 0], [2, 1], [3, 2], [4, 3], [5, 4]],
                ]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ listList @listWhere(maxItems: 1) }',
                ]),
                Json::fromNative((object) ['data' => ['listList' => []]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ listNullList @listWhere(maxItems: 1, orNull: true) }',
                ]),
                Json::fromNative((object) ['data' => ['listNullList' => [null]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ listList @intWhere(field: "0", greaterThan: 4) }',
                ]),
                Json::fromNative((object) ['data' => ['listList' => [[4, 3], [5, 4]]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ listList @intWhere(field: "3") }',
                ]),
                Json::fromNative((object) ['data' => ['listList' => []]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ listList @intWhere(field: "3", orNull: true) }',
                ]),
                Json::fromNative((object) ['data' => ['listList' => [[1,0],[2,1],[3,2],[4,3],[5,4]]]]),
            ],
        ];
    }

    /**
     * @dataProvider stringDataProvider
     * @dataProvider simpleIntDataProvider
     * @dataProvider simpleFloatDataProvider
     * @dataProvider simpleBoolDataProvider
     * @dataProvider simpleListDataProvider
     * @param Json $request
     * @param Json $expected
     */
    public function testSimple(Json $request, Json $expected) : void
    {
        $graphpinator = $this->getGraphpinator();
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
        self::assertNull($result->getErrors());
    }

    private function getGraphpinator() : \Graphpinator\Graphpinator
    {
        return new \Graphpinator\Graphpinator(
            new \Graphpinator\Type\Schema(
                new class extends \Graphpinator\Container\Container {
                    public function getType(string $name) : ?\Graphpinator\Type\Contract\NamedDefinition
                    {
                        return \Graphpinator\WhereDirectives\Tests\TestDIContainer::getType($name);
                    }

                    public function getTypes(bool $includeBuiltIn = false) : array
                    {
                    }

                    public function getDirective(string $name) : ?\Graphpinator\Directive\Directive
                    {
                        return \Graphpinator\WhereDirectives\Tests\TestDIContainer::getType($name);
                    }

                    public function getDirectives(bool $includeBuiltIn = false) : array
                    {
                    }
                },
                new class extends \Graphpinator\Type\Type {
                    public function validateNonNullValue(mixed $rawValue) : bool
                    {
                        return true;
                    }

                    protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
                    {
                        return new \Graphpinator\Field\ResolvableFieldSet([
                            \Graphpinator\Field\ResolvableField::create(
                                'stringList',
                                \Graphpinator\Container\Container::String()->notNullList(),
                                static function ($parent) : array {
                                    return ['aaa', 'bbb', 'ccc', 'aaa', 'abc'];
                                },
                            ),
                            \Graphpinator\Field\ResolvableField::create(
                                'stringNullList',
                                \Graphpinator\Container\Container::String()->list()->notNull(),
                                static function ($parent) : array {
                                    return ['aaa', 'bbb', null, 'ccc', 'aaa', 'abc'];
                                },
                            ),
                            \Graphpinator\Field\ResolvableField::create(
                                'intList',
                                \Graphpinator\Container\Container::Int()->notNullList(),
                                static function ($parent) : array {
                                    return [1, 2, 3, 4, 5];
                                },
                            ),
                            \Graphpinator\Field\ResolvableField::create(
                                'intNullList',
                                \Graphpinator\Container\Container::Int()->list()->notNull(),
                                static function ($parent) : array {
                                    return [1, 2, null, 3, 4, 5];
                                },
                            ),
                            \Graphpinator\Field\ResolvableField::create(
                                'floatList',
                                \Graphpinator\Container\Container::Float()->notNullList(),
                                static function ($parent) : array {
                                    return [1.00, 2.00, 3.00, 4.00, 5.00];
                                },
                            ),
                            \Graphpinator\Field\ResolvableField::create(
                                'floatNullList',
                                \Graphpinator\Container\Container::Float()->list()->notNull(),
                                static function ($parent) : array {
                                    return [1.00, 2.00, null, 3.00, 4.00, 5.00];
                                },
                            ),
                            \Graphpinator\Field\ResolvableField::create(
                                'boolList',
                                \Graphpinator\Container\Container::Boolean()->notNullList(),
                                static function ($parent) : array {
                                    return [true, false, false];
                                },
                            ),
                            \Graphpinator\Field\ResolvableField::create(
                                'boolNullList',
                                \Graphpinator\Container\Container::Boolean()->list()->notNull(),
                                static function ($parent) : array {
                                    return [true, false, null, false];
                                },
                            ),
                            \Graphpinator\Field\ResolvableField::create(
                                'listList',
                                \Graphpinator\Container\Container::Int()->notNull()->list()->notNull()->list()->notNull(),
                                static function ($parent) : array {
                                    return [[1, 0], [2, 1], [3, 2], [4, 3], [5, 4]];
                                },
                            ),
                            \Graphpinator\Field\ResolvableField::create(
                                'listNullList',
                                \Graphpinator\Container\Container::Int()->notNull()->list()->list()->notNull(),
                                static function ($parent) : array {
                                    return [[1, 0], [2, 1], [3, 2], null, [4, 3], [5, 4]];
                                },
                            ),
                        ]);
                    }
                }
            ),
        );
    }
}
