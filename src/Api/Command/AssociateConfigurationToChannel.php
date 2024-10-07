<?php

declare(strict_types=1);

namespace Setono\SyliusGiftCardPlugin\Api\Command;

class AssociateConfigurationToChannel implements ConfigurationCodeAwareInterface
{
    public ?string $configurationCode = null;

    public function __construct(public string $localeCode, public string $channelCode)
    {
    }

    public function getConfigurationCode(): ?string
    {
        return $this->configurationCode;
    }

    public function setConfigurationCode(?string $configurationCode): void
    {
        $this->configurationCode = $configurationCode;
    }
}
