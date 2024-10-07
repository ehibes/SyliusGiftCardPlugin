<?php

declare(strict_types=1);

namespace Setono\SyliusGiftCardPlugin\Tests\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Setono\SyliusGiftCardPlugin\Factory\GiftCardConfigurationFactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class GiftCardConfigurationContext implements Context
{
    public function __construct(private readonly RepositoryInterface $giftCardConfigurationRepository, private readonly GiftCardConfigurationFactoryInterface $giftCardConfigurationFactory)
    {
    }

    /**
     * @Given /^the store has a gift card configuration with code "([^"]+)"$/
     */
    public function theStoreHasGiftCardConfigurationWithCode(string $code): void
    {
        $giftCardConfiguration = $this->giftCardConfigurationFactory->createNew();
        $giftCardConfiguration->setCode($code);
        $giftCardConfiguration->enable();
        foreach ($giftCardConfiguration->getImages() as $image) {
            $giftCardConfiguration->removeImage($image);
        }

        $this->giftCardConfigurationRepository->add($giftCardConfiguration);
    }
}
