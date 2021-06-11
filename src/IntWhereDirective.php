<?php

declare(strict_types = 1);

namespace Graphpinator\WhereDirectives;

final class IntWhereDirective extends \Graphpinator\WhereDirectives\BaseWhereDirective
{
    protected const NAME = 'intWhere';
    protected const DESCRIPTION = 'Graphpinator intWhere directive.';
    protected const TYPE = \Graphpinator\Typesystem\Spec\IntType::class;

    protected static function satisfiesCondition(int $value, ?int $equals, ?int $greaterThan, ?int $lessThan) : bool
    {
        if (\is_int($equals) && $value !== $equals) {
            return false;
        }

        if (\is_int($greaterThan) && $value < $greaterThan) {
            return false;
        }

        return !\is_int($lessThan) || $value <= $lessThan;
    }

    protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
    {
        return new \Graphpinator\Typesystem\Argument\ArgumentSet([
            \Graphpinator\Typesystem\Argument\Argument::create('field', \Graphpinator\Typesystem\Container::String()),
            \Graphpinator\Typesystem\Argument\Argument::create('not', \Graphpinator\Typesystem\Container::Boolean()->notNull())
                ->setDefaultValue(false),
            \Graphpinator\Typesystem\Argument\Argument::create('equals', \Graphpinator\Typesystem\Container::Int()),
            \Graphpinator\Typesystem\Argument\Argument::create('greaterThan', \Graphpinator\Typesystem\Container::Int()),
            \Graphpinator\Typesystem\Argument\Argument::create('lessThan', \Graphpinator\Typesystem\Container::Int()),
            \Graphpinator\Typesystem\Argument\Argument::create('orNull', \Graphpinator\Typesystem\Container::Boolean()->notNull())
                ->setDefaultValue(false),
        ]);
    }
}
