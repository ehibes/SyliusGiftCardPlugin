<?php

declare(strict_types=1);

namespace Setono\SyliusGiftCardPlugin\Api\Controller\Action;

use Setono\SyliusGiftCardPlugin\Model\GiftCardBalanceCollection;
use Setono\SyliusGiftCardPlugin\Repository\GiftCardRepositoryInterface;

final class GiftCardBalanceAction
{
    public function __construct(private readonly GiftCardRepositoryInterface $giftCardRepository)
    {
    }

    public function __invoke(): GiftCardBalanceCollection
    {
        return GiftCardBalanceCollection::createFromGiftCards(
            $this->giftCardRepository->findEnabled(),
        );
    }
}
