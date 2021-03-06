<?php

declare(strict_types = 1);

namespace Graphpinator\WhereDirectives\Tests\Integration;

use Infinityloop\Utils\Json;

final class InvalidFieldTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
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
     * @param Json $request
     */
    public function testSimple(Json $request) : void
    {
        $this->expectException(\Graphpinator\Normalizer\Exception\DirectiveIncorrectUsage::class);
        $this->getGraphpinator()->run(new \Graphpinator\Request\JsonRequestFactory($request));
    }

    private function getGraphpinator() : \Graphpinator\Graphpinator
    {
        return new \Graphpinator\Graphpinator(
            new \Graphpinator\Type\Schema(
                \Graphpinator\WhereDirectives\Tests\TestDIContainer::getTypeContainer(),
                new class extends \Graphpinator\Type\Type {
                    public function validateNonNullValue(mixed $rawValue) : bool
                    {
                        return true;
                    }

                    protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
                    {
                        return new \Graphpinator\Field\ResolvableFieldSet([
                            \Graphpinator\Field\ResolvableField::create(
                                'stringField',
                                \Graphpinator\Container\Container::String()->notNull(),
                                static function ($parent) : string {
                                    return 'abc';
                                },
                            ),
                            \Graphpinator\Field\ResolvableField::create(
                                'listField',
                                \Graphpinator\Container\Container::String()->notNullList(),
                                static function ($parent) : array {
                                    return ['aaa', 'bbb', 'ccc', 'aaa', 'abc'];
                                },
                            ),
                        ]);
                    }
                }
            ),
        );
    }
}
