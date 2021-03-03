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
        $this->expectException(\Graphpinator\Exception\Normalizer\DirectiveIncorrectUsage::class);
        $this->getGraphpinator()->run(new \Graphpinator\Request\JsonRequestFactory($request));
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
