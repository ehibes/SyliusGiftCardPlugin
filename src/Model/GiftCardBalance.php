<?php

declare(strict_types=1);

namespace Setono\SyliusGiftCardPlugin\Model;

final class GiftCardBalance
{
    private int $count = 0;

    private int $total = 0;

    public function __construct(private readonly string $currencyCode)
    {
    }

    public function add(int $amount): void
    {
        ++$this->count;
        $this->total += $amount;
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getAverageAmount(): int
    {
        return (int) round($this->total / $this->count);
    }
}
