<?php

declare(strict_types=1);

namespace Setono\SyliusGiftCardPlugin\Api\Command;

class CreateGiftCardConfiguration
{
    public function __construct(public string $code, public bool $default = false, public bool $enabled = true, public ?string $defaultValidityPeriod = null, public ?string $pageSize = null, public ?string $orientation = null, public ?string $template = null)
    {
    }
}
