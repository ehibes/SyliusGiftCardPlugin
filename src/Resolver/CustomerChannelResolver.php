<?php

declare(strict_types=1);

namespace Setono\SyliusGiftCardPlugin\Resolver;

use RuntimeException;
use Setono\SyliusGiftCardPlugin\Repository\OrderRepositoryInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;

final class CustomerChannelResolver implements CustomerChannelResolverInterface
{
    public function __construct(private readonly OrderRepositoryInterface $orderRepository, private readonly ChannelRepositoryInterface $channelRepository)
    {
    }

    public function resolve(CustomerInterface $customer): ChannelInterface
    {
        $latestOrder = $this->orderRepository->findLatestByCustomer($customer);
        if (null !== $latestOrder) {
            $channel = $latestOrder->getChannel();
            if (null !== $channel) {
                return $channel;
            }
        }

        /** @var ChannelInterface|null $channel */
        $channel = $this->channelRepository->findOneBy([
            'enabled' => true,
        ]);

        if (null === $channel) {
            throw new RuntimeException('There are no enabled channels');
        }

        return $channel;
    }
}
