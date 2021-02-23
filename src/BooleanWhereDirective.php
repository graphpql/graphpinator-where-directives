<?php

declare(strict_types = 1);

namespace Graphpinator\WhereDirectives;

final class BooleanWhereDirective extends \Graphpinator\WhereDirectives\BaseWhereDirective
{
    protected const NAME = 'booleanWhere';
    protected const DESCRIPTION = 'Graphpinator booleanWhere directive.';
    protected const TYPE = \Graphpinator\Type\Scalar\BooleanType::class;

    protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
    {
        return new \Graphpinator\Argument\ArgumentSet([
            \Graphpinator\Argument\Argument::create('field', \Graphpinator\Container\Container::String()),
            \Graphpinator\Argument\Argument::create('not', \Graphpinator\Container\Container::Boolean()->notNull())
                ->setDefaultValue(false),
            \Graphpinator\Argument\Argument::create('equals', \Graphpinator\Container\Container::Boolean()),
            \Graphpinator\Argument\Argument::create('orNull', \Graphpinator\Container\Container::Boolean()->notNull())
                ->setDefaultValue(false),
        ]);
    }

    protected static function satisfiesCondition(bool $value, ?bool $equals) : bool
    {
        if (\is_bool($equals) && $value !== $equals) {
            return false;
        }

        return true;
    }
}
