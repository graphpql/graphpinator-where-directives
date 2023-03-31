<?php

declare(strict_types = 1);

namespace Graphpinator\WhereDirectives;

use \Graphpinator\Typesystem\Location\SelectionDirectiveResult;

abstract class BaseWhereDirective extends \Graphpinator\Typesystem\Directive implements \Graphpinator\Typesystem\Location\FieldLocation
{
    protected const REPEATABLE = true;
    protected const TYPE = '';

    public function validateFieldUsage(
        \Graphpinator\Typesystem\Field\Field $field,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : bool
    {
        $shapingType = $field->getType()->getShapingType();

        if (!$shapingType instanceof \Graphpinator\Typesystem\ListType) {
            return false;
        }

        $fieldStr = $arguments->offsetGet('field')->getValue()->getRawValue();
        $where = \is_string($fieldStr)
            ? \array_reverse(\explode('.', $fieldStr))
            : [];

        return self::recursiveValidateType($shapingType->getInnerType(), $where);
    }

    public function resolveFieldBefore(\Graphpinator\Value\ArgumentValueSet $arguments) : SelectionDirectiveResult
    {
        return SelectionDirectiveResult::NONE;
    }

    public function resolveFieldAfter(
        \Graphpinator\Value\ArgumentValueSet $arguments,
        \Graphpinator\Value\FieldValue $fieldValue,
    ) : SelectionDirectiveResult
    {
        $listValue = $fieldValue->getValue();
        \assert($listValue instanceof \Graphpinator\Value\ListResolvedValue);

        $argumentValues = $arguments->getValuesForResolver();
        $fieldArgument = $argumentValues['field'];
        $notArgument = $argumentValues['not'];
        $orNullArgument = $argumentValues['orNull'];
        unset($argumentValues['field'], $argumentValues['not'], $argumentValues['orNull']);

        foreach ($listValue as $key => $singleValue) {
            $rawValue = self::extractValue($singleValue, $fieldArgument);
            $condition = $rawValue === null
                ? $orNullArgument
                : static::satisfiesCondition($rawValue, ...$argumentValues); // @phpstan-ignore-line

            if ($condition === $notArgument) {
                unset($listValue[$key]);
            }
        }

        return SelectionDirectiveResult::NONE;
    }

    private static function extractValue(\Graphpinator\Value\ResolvedValue $singleValue, ?string $fieldStr) : string|int|float|bool|array|null
    {
        $field = \is_string($fieldStr)
            ? \array_reverse(\explode('.', $fieldStr))
            : [];

        return self::recursiveExtractValue($singleValue, $field)->getRawValue();
    }

    private static function recursiveExtractValue(\Graphpinator\Value\ResolvedValue $singleValue, array &$field) : \Graphpinator\Value\ResolvedValue
    {
        if (\count($field) === 0) {
            return $singleValue;
        }

        $currentWhere = \array_pop($field);

        if (\is_numeric($currentWhere)) {
            \assert($singleValue instanceof \Graphpinator\Value\ListResolvedValue);
            $currentWhere = (int) $currentWhere;

            if (!$singleValue->offsetExists($currentWhere)) {
                return new \Graphpinator\Value\NullResolvedValue($singleValue->getType());
            }

            return self::recursiveExtractValue($singleValue->offsetGet($currentWhere), $field);
        }

        return self::recursiveExtractValue($singleValue->{$currentWhere}->getValue(), $field);
    }

    private static function recursiveValidateType(\Graphpinator\Typesystem\Contract\Type $definition, array &$field) : bool
    {
        $definition = $definition->getShapingType();

        if (\count($field) === 0) {
            //@phpcs:ignore SlevomatCodingStandard.PHP.UselessParentheses.UselessParentheses
            return $definition instanceof (static::TYPE);
        }

        $currentWhere = \array_pop($field);

        if (\is_numeric($currentWhere)) {
            if ($definition instanceof \Graphpinator\Typesystem\ListType) {
                return self::recursiveValidateType($definition->getInnerType(), $field);
            }

            return false;
        }

        if ($definition instanceof \Graphpinator\Typesystem\Type && $definition->getFields()->offsetExists($currentWhere)) {
            return self::recursiveValidateType($definition->getFields()->offsetGet($currentWhere)->getType(), $field);
        }

        return false;
    }
}
