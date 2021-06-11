<?php

declare(strict_types = 1);

namespace Graphpinator\WhereDirectives;

final class FloatWhereDirective extends \Graphpinator\WhereDirectives\BaseWhereDirective
{
    protected const NAME = 'floatWhere';
    protected const DESCRIPTION = 'Graphpinator floatWhere directive.';
    protected const TYPE = \Graphpinator\Typesystem\Spec\FloatType::class;

    protected static function satisfiesCondition(float $value, ?float $equals, ?float $greaterThan, ?float $lessThan) : bool
    {
        if (\is_float($equals) && $value !== $equals) {
            return false;
        }

        if (\is_float($greaterThan) && $value < $greaterThan) {
            return false;
        }

        return !\is_float($lessThan) || $value <= $lessThan;
    }

    protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
    {
        return new \Graphpinator\Typesystem\Argument\ArgumentSet([
            \Graphpinator\Typesystem\Argument\Argument::create('field', \Graphpinator\Typesystem\Container::String()),
            \Graphpinator\Typesystem\Argument\Argument::create('not', \Graphpinator\Typesystem\Container::Boolean()->notNull())
                ->setDefaultValue(false),
            \Graphpinator\Typesystem\Argument\Argument::create('equals', \Graphpinator\Typesystem\Container::Float()),
            \Graphpinator\Typesystem\Argument\Argument::create('greaterThan', \Graphpinator\Typesystem\Container::Float()),
            \Graphpinator\Typesystem\Argument\Argument::create('lessThan', \Graphpinator\Typesystem\Container::Float()),
            \Graphpinator\Typesystem\Argument\Argument::create('orNull', \Graphpinator\Typesystem\Container::Boolean()->notNull())
                ->setDefaultValue(false),
        ]);
    }
}
