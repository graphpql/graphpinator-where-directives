<?php

declare(strict_types = 1);

namespace Graphpinator\WhereDirectives;

final class StringWhereDirective extends \Graphpinator\WhereDirectives\BaseWhereDirective
{
    protected const NAME = 'stringWhere';
    protected const DESCRIPTION = 'Graphpinator stringWhere directive.';
    protected const TYPE = \Graphpinator\Typesystem\Spec\StringType::class;

    protected static function satisfiesCondition(string $value, ?string $equals, ?string $contains, ?string $startsWith, ?string $endsWith) : bool
    {
        if (\is_string($equals) && $value !== $equals) {
            return false;
        }

        if (\is_string($contains) && !\str_contains($value, $contains)) {
            return false;
        }

        if (\is_string($startsWith) && !\str_starts_with($value, $startsWith)) {
            return false;
        }

        return !\is_string($endsWith) || \str_ends_with($value, $endsWith);
    }

    protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
    {
        return new \Graphpinator\Typesystem\Argument\ArgumentSet([
            \Graphpinator\Typesystem\Argument\Argument::create('field', \Graphpinator\Container\Container::String()),
            \Graphpinator\Typesystem\Argument\Argument::create('not', \Graphpinator\Container\Container::Boolean()->notNull())
                ->setDefaultValue(false),
            \Graphpinator\Typesystem\Argument\Argument::create('equals', \Graphpinator\Container\Container::String()),
            \Graphpinator\Typesystem\Argument\Argument::create('contains', \Graphpinator\Container\Container::String()),
            \Graphpinator\Typesystem\Argument\Argument::create('startsWith', \Graphpinator\Container\Container::String()),
            \Graphpinator\Typesystem\Argument\Argument::create('endsWith', \Graphpinator\Container\Container::String()),
            \Graphpinator\Typesystem\Argument\Argument::create('orNull', \Graphpinator\Container\Container::Boolean()->notNull())
                ->setDefaultValue(false),
        ]);
    }
}
