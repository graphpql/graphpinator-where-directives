<?php

declare(strict_types = 1);

namespace Graphpinator\WhereDirectives\Tests;

final class TestDIContainer
{
    use \Nette\StaticClass;

    private static array $types = [];
    private static ?\Graphpinator\ConstraintDirectives\ConstraintDirectiveAccessor $accessor = null;
    private static ?\Graphpinator\Typesystem\Container $container = null;

    public static function getTypeContainer() : \Graphpinator\Typesystem\Container
    {
        return new \Graphpinator\SimpleContainer([
            'ListConstraintInput' => self::getType('ListConstraintInput'),
        ], [
            'stringConstraint' => self::getType('stringConstraint'),
            'intConstraint' => self::getType('intConstraint'),
            'floatConstraint' => self::getType('floatConstraint'),
            'listConstraint' => self::getType('listConstraint'),
            'objectConstraint' => self::getType('objectConstraint'),
            'stringWhere' => self::getType('stringWhere'),
            'intWhere' => self::getType('intWhere'),
            'floatWhere' => self::getType('floatWhere'),
            'booleanWhere' => self::getType('booleanWhere'),
            'listWhere' => self::getType('listWhere'),
        ]);
    }

    public static function getType(string $name) : object
    {
        if (\array_key_exists($name, self::$types)) {
            return self::$types[$name];
        }

        self::$types[$name] = match ($name) {
            'ListConstraintInput' => new \Graphpinator\ConstraintDirectives\ListConstraintInput(
                self::getAccessor(),
            ),
            'stringConstraint' => new \Graphpinator\ConstraintDirectives\StringConstraintDirective(
                self::getAccessor(),
            ),
            'intConstraint' => new \Graphpinator\ConstraintDirectives\IntConstraintDirective(
                self::getAccessor(),
            ),
            'floatConstraint' => new \Graphpinator\ConstraintDirectives\FloatConstraintDirective(
                self::getAccessor(),
            ),
            'listConstraint' => new \Graphpinator\ConstraintDirectives\ListConstraintDirective(
                self::getAccessor(),
            ),
            'objectConstraint' => new \Graphpinator\ConstraintDirectives\ObjectConstraintDirective(
                self::getAccessor(),
            ),
            'ObjectConstraintInput' => new \Graphpinator\ConstraintDirectives\ObjectConstraintInput(
                self::getAccessor(),
            ),
            'stringWhere' => new \Graphpinator\WhereDirectives\StringWhereDirective(),
            'intWhere' => new \Graphpinator\WhereDirectives\IntWhereDirective(),
            'floatWhere' => new \Graphpinator\WhereDirectives\FloatWhereDirective(),
            'booleanWhere' => new \Graphpinator\WhereDirectives\BooleanWhereDirective(),
            'listWhere' => new \Graphpinator\WhereDirectives\ListWhereDirective(self::getType('intConstraint')),
        };

        return self::$types[$name];
    }

    public static function getAccessor() : \Graphpinator\ConstraintDirectives\ConstraintDirectiveAccessor
    {
        if (self::$accessor === null) {
            self::$accessor = new class implements \Graphpinator\ConstraintDirectives\ConstraintDirectiveAccessor {
                public function getString() : \Graphpinator\ConstraintDirectives\StringConstraintDirective
                {
                    return TestDIContainer::getType('stringConstraint');
                }

                public function getInt() : \Graphpinator\ConstraintDirectives\IntConstraintDirective
                {
                    return TestDIContainer::getType('intConstraint');
                }

                public function getFloat() : \Graphpinator\ConstraintDirectives\FloatConstraintDirective
                {
                    return TestDIContainer::getType('floatConstraint');
                }

                public function getList() : \Graphpinator\ConstraintDirectives\ListConstraintDirective
                {
                    return TestDIContainer::getType('listConstraint');
                }

                public function getListInput() : \Graphpinator\ConstraintDirectives\ListConstraintInput
                {
                    return TestDIContainer::getType('ListConstraintInput');
                }

                public function getObject() : \Graphpinator\ConstraintDirectives\ObjectConstraintDirective
                {
                    return TestDIContainer::getType('objectConstraint');
                }

                public function getObjectInput() : \Graphpinator\ConstraintDirectives\ObjectConstraintInput
                {
                    return TestDIContainer::getType('ObjectConstraintInput');
                }
            };
        }

        return self::$accessor;
    }
}
