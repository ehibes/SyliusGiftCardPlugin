<?php

declare(strict_types=1);

namespace Setono\SyliusGiftCardPlugin\Api\Command;

class AddGiftCardToOrder implements GiftCardCodeAwareInterface
{
    public ?string $giftCardCode = null;

    public function __construct(public string $orderTokenValue)
    {
    }

    public function getGiftCardCode(): ?string
    {
        return $this->giftCardCode;
    }

    public function setGiftCardCode(?string $giftCardCode): void
    {
        $this->giftCardCode = $giftCardCode;
    }
}
