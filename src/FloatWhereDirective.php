<?php

declare(strict_types = 1);

namespace Graphpinator\WhereDirectives;

final class FloatWhereDirective extends \Graphpinator\WhereDirectives\BaseWhereDirective
{
    protected const NAME = 'floatWhere';
    protected const DESCRIPTION = 'Graphpinator floatWhere directive.';
    protected const TYPE = \Graphpinator\Type\Scalar\FloatType::class;

    protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
    {
        return new \Graphpinator\Argument\ArgumentSet([
            \Graphpinator\Argument\Argument::create('field', \Graphpinator\Container\Container::String()),
            \Graphpinator\Argument\Argument::create('not', \Graphpinator\Container\Container::Boolean()->notNull())
                ->setDefaultValue(false),
            \Graphpinator\Argument\Argument::create('equals', \Graphpinator\Container\Container::Float()),
            \Graphpinator\Argument\Argument::create('greaterThan', \Graphpinator\Container\Container::Float()),
            \Graphpinator\Argument\Argument::create('lessThan', \Graphpinator\Container\Container::Float()),
            \Graphpinator\Argument\Argument::create('orNull', \Graphpinator\Container\Container::Boolean()->notNull())
                ->setDefaultValue(false),
        ]);
    }

    protected static function satisfiesCondition(float $value, ?float $equals, ?float $greaterThan, ?float $lessThan) : bool
    {
        if (\is_float($equals) && $value !== $equals) {
            return false;
        }

        if (\is_float($greaterThan) && $value < $greaterThan) {
            return false;
        }

        if (\is_float($lessThan) && $value > $lessThan) {
            return false;
        }

        return true;
    }
}
