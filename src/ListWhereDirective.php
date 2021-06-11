<?php

declare(strict_types = 1);

namespace Graphpinator\WhereDirectives;

final class ListWhereDirective extends \Graphpinator\WhereDirectives\BaseWhereDirective
{
    protected const NAME = 'listWhere';
    protected const DESCRIPTION = 'Graphpinator listWhere directive.';
    protected const TYPE = \Graphpinator\Typesystem\ListType::class;

    public function __construct(
        private \Graphpinator\ConstraintDirectives\IntConstraintDirective $intConstraintDirective,
    )
    {
    }

    protected static function satisfiesCondition(array $value, ?int $minItems, ?int $maxItems) : bool
    {
        if (\is_int($minItems) && \count($value) < $minItems) {
            return false;
        }

        return !\is_int($maxItems) || \count($value) <= $maxItems;
    }

    protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
    {
        return new \Graphpinator\Typesystem\Argument\ArgumentSet([
            \Graphpinator\Typesystem\Argument\Argument::create('field', \Graphpinator\Typesystem\Container::String()),
            \Graphpinator\Typesystem\Argument\Argument::create('not', \Graphpinator\Typesystem\Container::Boolean()->notNull())
                ->setDefaultValue(false),
            \Graphpinator\Typesystem\Argument\Argument::create('minItems', \Graphpinator\Typesystem\Container::Int())
                ->addDirective(
                    $this->intConstraintDirective,
                    ['min' => 0],
                ),
            \Graphpinator\Typesystem\Argument\Argument::create('maxItems', \Graphpinator\Typesystem\Container::Int())
                ->addDirective(
                    $this->intConstraintDirective,
                    ['min' => 0],
                ),
            \Graphpinator\Typesystem\Argument\Argument::create('orNull', \Graphpinator\Typesystem\Container::Boolean()->notNull())
                ->setDefaultValue(false),
        ]);
    }
}
