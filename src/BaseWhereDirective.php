<?php

declare(strict_types = 1);

namespace Graphpinator\WhereDirectives;

abstract class BaseWhereDirective extends \Graphpinator\Directive\Directive
    implements \Graphpinator\Directive\Contract\ExecutableDefinition
{
    use \Graphpinator\Directive\Contract\TExecutableDefinition;

    protected const TYPE = '';

    public function validateType(
        \Graphpinator\Type\Contract\Definition $definition,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : bool
    {
        $shapingType = $definition->getShapingType();

        if (!$shapingType instanceof \Graphpinator\Type\ListType) {
            return false;
        }

        $fieldStr = $arguments->offsetGet('field')->getValue()->getRawValue();
        $field = \is_string($fieldStr)
            ? \array_reverse(\explode('.', $fieldStr))
            : [];

        return self::recursiveValidateType($shapingType->getInnerType(), $field);
    }

    protected static function extractValue(\Graphpinator\Value\ResolvedValue $singleValue, ?string $fieldStr) : string|int|float|bool|array|null
    {
        $field = \is_string($fieldStr)
            ? \array_reverse(\explode('.', $fieldStr))
            : [];

        return self::recursiveExtractValue($singleValue, $field)->getRawValue();
    }

    private static function recursiveExtractValue(\Graphpinator\Value\ResolvedValue $singleValue, array& $field) : \Graphpinator\Value\ResolvedValue
    {
        if (\count($field) === 0) {
            return $singleValue;
        }

        $currentWhere = \array_pop($field);

        if (\is_numeric($currentWhere)) {
            \assert($singleValue instanceof \Graphpinator\Value\ListValue);
            $currentWhere = (int) $currentWhere;

            if (!$singleValue->offsetExists($currentWhere)) {
                return new \Graphpinator\Value\NullResolvedValue($singleValue->getType());
            }

            return static::recursiveExtractValue($singleValue->offsetGet($currentWhere), $field);
        }

        return static::recursiveExtractValue($singleValue->{$currentWhere}->getValue(), $field);
    }

    private static function recursiveValidateType(\Graphpinator\Type\Contract\Definition $definition, array& $field) : bool
    {
        $definition = $definition->getShapingType();

        if (\count($field) === 0) {
            return $definition instanceof (static::TYPE);
        }

        $currentWhere = \array_pop($field);

        if (\is_numeric($currentWhere)) {
            if ($definition instanceof \Graphpinator\Type\ListType) {
                return self::recursiveValidateType($definition->getInnerType(), $field);
            }

            return false;
        }

        if ($definition instanceof \Graphpinator\Type\Type && $definition->getFields()->offsetExists($currentWhere)) {
            return self::recursiveValidateType($definition->getFields()->offsetGet($currentWhere)->getType(), $field);
        }

        return false;
    }
}
