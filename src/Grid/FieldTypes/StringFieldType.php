<?php

declare(strict_types=1);

namespace Setono\SyliusGiftCardPlugin\Grid\FieldTypes;

use InvalidArgumentException;
use Sylius\Component\Grid\Definition\Field;
use Sylius\Component\Grid\FieldTypes\FieldTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Throwable;
use Webmozart\Assert\Assert;

final class StringFieldType implements FieldTypeInterface
{
    public function __construct(private readonly PropertyAccessorInterface $propertyAccessor)
    {
    }

    public function render(Field $field, $data, array $options): string
    {
        try {
            if (!is_object($data) && !is_array($data)) {
                throw new InvalidArgumentException('The $data should be either an array or an object');
            }

            /** @var mixed $value */
            $value = $this->propertyAccessor->getValue($data, $field->getPath());
            Assert::true($this->isStringable($value));
        } catch (Throwable) {
            return '';
        }

        return htmlspecialchars((string) $value);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
    }

    /**
     * @psalm-assert-if-true null|scalar|object $value
     */
    private function isStringable(mixed $value): bool
    {
        return $value === null || is_scalar($value) || (is_object($value) && method_exists($value, '__toString'));
    }
}
