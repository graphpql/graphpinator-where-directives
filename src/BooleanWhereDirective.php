<?php

declare(strict_types = 1);

namespace Graphpinator\WhereDirectives;

final class BooleanWhereDirective extends \Graphpinator\WhereDirectives\BaseWhereDirective
{
    protected const NAME = 'booleanWhere';
    protected const DESCRIPTION = 'Graphpinator booleanWhere directive.';
    protected const TYPE = \Graphpinator\Typesystem\Spec\BooleanType::class;

    protected static function satisfiesCondition(bool $value, ?bool $equals) : bool
    {
        return !\is_bool($equals) || $value === $equals;
    }

    protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
    {
        return new \Graphpinator\Typesystem\Argument\ArgumentSet([
            \Graphpinator\Typesystem\Argument\Argument::create('field', \Graphpinator\Typesystem\Container::String()),
            \Graphpinator\Typesystem\Argument\Argument::create('not', \Graphpinator\Typesystem\Container::Boolean()->notNull())
                ->setDefaultValue(false),
            \Graphpinator\Typesystem\Argument\Argument::create('equals', \Graphpinator\Typesystem\Container::Boolean()),
            \Graphpinator\Typesystem\Argument\Argument::create('orNull', \Graphpinator\Typesystem\Container::Boolean()->notNull())
                ->setDefaultValue(false),
        ]);
    }
}
