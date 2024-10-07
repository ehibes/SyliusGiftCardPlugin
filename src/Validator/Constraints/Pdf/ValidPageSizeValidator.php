<?php

declare(strict_types=1);

namespace Setono\SyliusGiftCardPlugin\Validator\Constraints\Pdf;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class ValidPageSizeValidator extends ConstraintValidator
{
    public function __construct(private readonly array $availablePageSizes)
    {
    }

    /**
     * @param mixed $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ValidPageSize) {
            throw new UnexpectedTypeException($constraint, ValidPageSize::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!\in_array($value, $this->availablePageSizes, true)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
