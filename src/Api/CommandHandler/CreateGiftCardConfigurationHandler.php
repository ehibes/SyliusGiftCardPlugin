<?php

declare(strict_types=1);

namespace Setono\SyliusGiftCardPlugin\Api\CommandHandler;

use Doctrine\Persistence\ObjectManager;
use Setono\SyliusGiftCardPlugin\Api\Command\CreateGiftCardConfiguration;
use Setono\SyliusGiftCardPlugin\Factory\GiftCardConfigurationFactoryInterface;
use Setono\SyliusGiftCardPlugin\Model\GiftCardConfigurationInterface;

final class CreateGiftCardConfigurationHandler
{
    public function __construct(private readonly GiftCardConfigurationFactoryInterface $giftCardConfigurationFactory, private readonly ObjectManager $giftCardConfigurationManager)
    {
    }

    public function __invoke(CreateGiftCardConfiguration $command): GiftCardConfigurationInterface
    {
        $giftCardConfiguration = $this->giftCardConfigurationFactory->createNew();

        $giftCardConfiguration->setCode($command->code);
        $giftCardConfiguration->setEnabled($command->enabled);
        $giftCardConfiguration->setDefault($command->default);
        if (null !== $command->defaultValidityPeriod) {
            $giftCardConfiguration->setDefaultValidityPeriod($command->defaultValidityPeriod);
        }
        if (null !== $command->pageSize) {
            $giftCardConfiguration->setPageSize($command->pageSize);
        }
        if (null !== $command->orientation) {
            $giftCardConfiguration->setOrientation($command->orientation);
        }
        if (null !== $command->template) {
            $giftCardConfiguration->setTemplate($command->template);
        }

        $this->giftCardConfigurationManager->persist($giftCardConfiguration);

        return $giftCardConfiguration;
    }
}
