<?php

declare(strict_types=1);

namespace Setono\SyliusGiftCardPlugin\Tests\Behat\Context\Transform;

use Behat\Behat\Context\Context;
use Setono\SyliusGiftCardPlugin\Model\GiftCardInterface;
use Setono\SyliusGiftCardPlugin\Repository\GiftCardRepositoryInterface;

final class GiftCardContext implements Context
{
    public function __construct(private readonly GiftCardRepositoryInterface $giftCardRepository)
    {
    }

    /**
     * @Transform :giftCard
     */
    public function getGiftCardByCode(string $code): GiftCardInterface
    {
        return $this->giftCardRepository->findOneByCode($code);
    }
}
