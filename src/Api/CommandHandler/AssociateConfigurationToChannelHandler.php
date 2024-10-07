<?php

declare(strict_types=1);

namespace Setono\SyliusGiftCardPlugin\Api\CommandHandler;

use Setono\SyliusGiftCardPlugin\Api\Command\AssociateConfigurationToChannel;
use Setono\SyliusGiftCardPlugin\Model\GiftCardChannelConfigurationInterface;
use Setono\SyliusGiftCardPlugin\Model\GiftCardConfigurationInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

final class AssociateConfigurationToChannelHandler
{
    public function __construct(private readonly RepositoryInterface $giftCardConfigurationRepository, private readonly ChannelRepositoryInterface $channelRepository, private readonly RepositoryInterface $localeRepository, private readonly RepositoryInterface $giftCardChannelConfigurationRepository, private readonly FactoryInterface $giftCardChannelConfigurationFactory)
    {
    }

    public function __invoke(AssociateConfigurationToChannel $command): GiftCardConfigurationInterface
    {
        Assert::notNull($command->getConfigurationCode(), 'GiftCardConfiguration code can not be null');

        /** @var GiftCardConfigurationInterface|null $configuration */
        $configuration = $this->giftCardConfigurationRepository->findOneBy(['code' => $command->getConfigurationCode()]);
        Assert::notNull($configuration, 'GiftCardConfiguration can not be null');

        $channel = $this->channelRepository->findOneByCode($command->channelCode);
        Assert::notNull($channel, 'Channel can not be null');

        /** @var LocaleInterface|null $locale */
        $locale = $this->localeRepository->findOneBy(['code' => $command->localeCode]);
        Assert::notNull($locale, 'Locale can not be null');

        /** @var GiftCardChannelConfigurationInterface|null $existingChannelConfiguration */
        $existingChannelConfiguration = $this->giftCardChannelConfigurationRepository->findOneBy([
            'configuration' => $configuration,
            'channel' => $channel,
            'locale' => $locale,
        ]);
        if (null !== $existingChannelConfiguration) {
            return $configuration;
        }

        /** @var GiftCardChannelConfigurationInterface $channelConfiguration */
        $channelConfiguration = $this->giftCardChannelConfigurationFactory->createNew();
        $channelConfiguration->setConfiguration($configuration);
        $channelConfiguration->setChannel($channel);
        $channelConfiguration->setLocale($locale);

        $configuration->addChannelConfiguration($channelConfiguration);

        return $configuration;
    }
}
