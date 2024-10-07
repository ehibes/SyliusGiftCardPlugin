<?php

declare(strict_types=1);

namespace Setono\SyliusGiftCardPlugin\Api\Controller\Action;

use Setono\SyliusGiftCardPlugin\Model\GiftCardConfigurationInterface;
use Setono\SyliusGiftCardPlugin\Model\GiftCardInterface;
use Setono\SyliusGiftCardPlugin\Provider\GiftCardConfigurationProviderInterface;
use Setono\SyliusGiftCardPlugin\Renderer\PdfRendererInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class DownloadGiftCardPdfAction
{
    public function __construct(private readonly GiftCardConfigurationProviderInterface $configurationProvider, private readonly PdfRendererInterface $pdfRenderer)
    {
    }

    public function __invoke(GiftCardInterface $data): Response
    {
        $configuration = $this->configurationProvider->getConfigurationForGiftCard($data);
        if (!$configuration instanceof GiftCardConfigurationInterface) {
            throw new NotFoundHttpException('No configuration found for this GiftCard');
        }

        return $this->pdfRenderer->render($data, $configuration);
    }
}
