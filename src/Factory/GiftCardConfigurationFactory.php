<?php

declare(strict_types=1);

namespace Setono\SyliusGiftCardPlugin\Factory;

use Setono\SyliusGiftCardPlugin\Model\GiftCardConfigurationInterface;
use Setono\SyliusGiftCardPlugin\Provider\DefaultGiftCardTemplateContentProviderInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class GiftCardConfigurationFactory implements GiftCardConfigurationFactoryInterface
{
    public function __construct(private readonly FactoryInterface $decoratedFactory, private readonly DefaultGiftCardTemplateContentProviderInterface $defaultGiftCardTemplateContentProvider, private readonly string $defaultOrientation, private readonly string $defaultPageSize)
    {
    }

    public function createNew(): GiftCardConfigurationInterface
    {
        /** @var GiftCardConfigurationInterface $configuration */
        $configuration = $this->decoratedFactory->createNew();

        $configuration->setOrientation($this->defaultOrientation);
        $configuration->setPageSize($this->defaultPageSize);
        $configuration->setTemplate($this->defaultGiftCardTemplateContentProvider->getContent());

        return $configuration;
    }
}
