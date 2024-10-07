<?php

declare(strict_types=1);

namespace Setono\SyliusGiftCardPlugin\Form\Type;

use Setono\SyliusGiftCardPlugin\Provider\DatePeriodUnitProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

final class DatePeriodType extends AbstractType
{
    public function __construct(private readonly DatePeriodUnitProviderInterface $datePeriodUnitProvider)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('value', IntegerType::class, [
            'label' => 'setono_sylius_gift_card.form.date_period.value',
        ]);
        $builder->add('unit', ChoiceType::class, [
            'label' => 'setono_sylius_gift_card.form.date_period.unit',
            'choices' => $this->datePeriodUnitProvider->getPeriodUnits(),
            'choice_label' => fn (string $choice): string => \sprintf('setono_sylius_gift_card.form.date_period.unit_%s', $choice),
        ]);
    }
}
