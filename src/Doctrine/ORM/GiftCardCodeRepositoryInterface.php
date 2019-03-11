<?php

declare(strict_types=1);

namespace Setono\SyliusGiftCardPlugin\Doctrine\ORM;

use Setono\SyliusGiftCardPlugin\Model\GiftCardCodeInterface;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface GiftCardCodeRepositoryInterface extends RepositoryInterface
{
    public function findOneActiveByCodeAndChannelCode(string $code, string $channelCode): ?GiftCardCodeInterface;

    public function findOneByCode(string $code): ?GiftCardCodeInterface;

    public function findActiveByCurrentOrder(OrderInterface $order): array;
}
