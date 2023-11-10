<?php

declare(strict_types = 1);

namespace Graphpinator\WhereDirectives\Tests\Integration;

use \Infinityloop\Utils\Json;

final class InvalidFieldTest extends \PHPUnit\Framework\TestCase
{
    public static function simpleDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => '{ stringField @stringWhere(equals: "aaa") }',
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ listField @intWhere(equals: 123) }',
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ listField @stringWhere(field: "0", equals: "aaa") }',
                ]),
            ],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param \Infinityloop\Utils\Json $request
     */
    public function testSimple(Json $request) : void
    {
        $this->expectException(\Graphpinator\Normalizer\Exception\DirectiveIncorrectUsage::class);
        $this->getGraphpinator()->run(new \Graphpinator\Request\JsonRequestFactory($request));
    }

    private function getGraphpinator() : \Graphpinator\Graphpinator
    {
        return new \Graphpinator\Graphpinator(
            new \Graphpinator\Typesystem\Schema(
                \Graphpinator\WhereDirectives\Tests\TestDIContainer::getTypeContainer(),
                new class extends \Graphpinator\Typesystem\Type {
                    public function validateNonNullValue(mixed $rawValue) : bool
                    {
                        return true;
                    }

                    protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
                    {
                        return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
                            \Graphpinator\Typesystem\Field\ResolvableField::create(
                                'stringField',
                                \Graphpinator\Typesystem\Container::String()->notNull(),
                                static function () : string {
                                    return 'abc';
                                },
                            ),
                            \Graphpinator\Typesystem\Field\ResolvableField::create(
                                'listField',
                                \Graphpinator\Typesystem\Container::String()->notNullList(),
                                static function () : array {
                                    return ['aaa', 'bbb', 'ccc', 'aaa', 'abc'];
                                },
                            ),
                        ]);
                    }
                },
            ),
        );
    }
}
