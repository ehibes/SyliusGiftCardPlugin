<?php

declare(strict_types=1);

namespace Setono\SyliusGiftCardPlugin\Api\CommandHandler;

use Setono\SyliusGiftCardPlugin\Api\Command\AddGiftCardToOrder;
use Setono\SyliusGiftCardPlugin\Applicator\GiftCardApplicatorInterface;
use Setono\SyliusGiftCardPlugin\Model\GiftCardInterface;
use Setono\SyliusGiftCardPlugin\Model\OrderInterface;
use Setono\SyliusGiftCardPlugin\Repository\GiftCardRepositoryInterface;
use Setono\SyliusGiftCardPlugin\Repository\OrderRepositoryInterface;
use Webmozart\Assert\Assert;

final class AddGiftCardToOrderHandler
{
    public function __construct(private readonly GiftCardRepositoryInterface $giftCardRepository, private readonly OrderRepositoryInterface $orderRepository, private readonly GiftCardApplicatorInterface $giftCardApplicator)
    {
    }

    public function __invoke(AddGiftCardToOrder $command): GiftCardInterface
    {
        $giftCardCode = $command->getGiftCardCode();
        Assert::notNull($giftCardCode);

        $giftCard = $this->giftCardRepository->findOneByCode($giftCardCode);
        Assert::notNull($giftCard);

        /** @var OrderInterface|null $order */
        $order = $this->orderRepository->findOneBy(['tokenValue' => $command->orderTokenValue]);
        Assert::notNull($order);

        $this->giftCardApplicator->apply($order, $giftCard);

        return $giftCard;
    }
}
