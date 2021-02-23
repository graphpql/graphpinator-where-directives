<?php

declare(strict_types = 1);

namespace Graphpinator\WhereDirectives;

final class ListWhereDirective extends \Graphpinator\WhereDirectives\BaseWhereDirective
{
    protected const NAME = 'listWhere';
    protected const DESCRIPTION = 'Graphpinator listWhere directive.';
    protected const TYPE = \Graphpinator\Type\ListType::class;

    public function __construct(
        private \Graphpinator\ConstraintDirectives\IntConstraintDirective $intConstraintDirective,
    ) {}

    protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
    {
        return new \Graphpinator\Argument\ArgumentSet([
            \Graphpinator\Argument\Argument::create('field', \Graphpinator\Container\Container::String()),
            \Graphpinator\Argument\Argument::create('not', \Graphpinator\Container\Container::Boolean()->notNull())
                ->setDefaultValue(false),
            \Graphpinator\Argument\Argument::create('minItems', \Graphpinator\Container\Container::Int())
                ->addDirective(
                    $this->intConstraintDirective,
                    ['min' => 0],
                ),
            \Graphpinator\Argument\Argument::create('maxItems', \Graphpinator\Container\Container::Int())
                ->addDirective(
                    $this->intConstraintDirective,
                    ['min' => 0],
                ),
            \Graphpinator\Argument\Argument::create('orNull', \Graphpinator\Container\Container::Boolean()->notNull())
                ->setDefaultValue(false),
        ]);
    }

    protected static function satisfiesCondition(array $value, ?int $minItems, ?int $maxItems) : bool
    {
        if (\is_int($minItems) && \count($value) < $minItems) {
            return false;
        }

        if (\is_int($maxItems) && \count($value) > $maxItems) {
            return false;
        }

        return true;
    }
}
