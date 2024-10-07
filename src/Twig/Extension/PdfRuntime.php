<?php

declare(strict_types=1);

namespace Setono\SyliusGiftCardPlugin\Twig\Extension;

use Setono\SyliusGiftCardPlugin\Factory\GiftCardFactoryInterface;
use Setono\SyliusGiftCardPlugin\Model\GiftCardConfigurationInterface;
use Setono\SyliusGiftCardPlugin\Renderer\PdfRendererInterface;
use Twig\Extension\RuntimeExtensionInterface;

final class PdfRuntime implements RuntimeExtensionInterface
{
    public function __construct(private readonly PdfRendererInterface $PDFRenderer, private readonly GiftCardFactoryInterface $giftCardFactory)
    {
    }

    public function getBase64EncodedExamplePdfContent(GiftCardConfigurationInterface $giftCardChannelConfiguration): string
    {
        $giftCard = $this->giftCardFactory->createExample();

        return $this->PDFRenderer->render($giftCard, $giftCardChannelConfiguration)->getEncodedContent();
    }
}
