<?php

declare(strict_types=1);

namespace Setono\SyliusGiftCardPlugin\Tests\Behat\Context\Api\Shop;

use ApiPlatform\Core\Api\IriConverterInterface;
use Behat\Behat\Context\Context;
use Setono\SyliusGiftCardPlugin\Model\ProductInterface;
use Sylius\Behat\Client\ApiClientInterface;
use Sylius\Behat\Client\RequestFactoryInterface;
use Sylius\Behat\Client\ResponseCheckerInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Product\Resolver\ProductVariantResolverInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

final class CartContext implements Context
{
    private ApiClientInterface $cartsClient;

    private ResponseCheckerInterface $responseChecker;

    private SharedStorageInterface $sharedStorage;

    private ProductVariantResolverInterface $productVariantResolver;

    private IriConverterInterface $iriConverter;

    private RequestFactoryInterface $requestFactory;

    private string $apiUrlPrefix;

    public function __construct(
        ApiClientInterface $cartsClient,
        ResponseCheckerInterface $responseChecker,
        SharedStorageInterface $sharedStorage,
        ProductVariantResolverInterface $productVariantResolver,
        IriConverterInterface $iriConverter,
        RequestFactoryInterface $requestFactory,
        string $apiUrlPrefix,
    ) {
        $this->cartsClient = $cartsClient;
        $this->responseChecker = $responseChecker;
        $this->sharedStorage = $sharedStorage;
        $this->productVariantResolver = $productVariantResolver;
        $this->iriConverter = $iriConverter;
        $this->requestFactory = $requestFactory;
        $this->apiUrlPrefix = $apiUrlPrefix;
    }

    /**
     * @When /^I add (this product) to the cart with amount ("[^"]+") and custom message "([^"]+)"$/
     */
    public function iAddProductWithAmountAndMessage(ProductInterface $product, int $amount, string $message): void
    {
        $tokenValue = $tokenValue ?? $this->pickupCart();

        $request = $this->requestFactory->customItemAction('shop', 'orders', $tokenValue, HttpRequest::METHOD_POST, 'items');

        $request->updateContent([
            'productVariant' => $this->productVariantResolver->getVariant($product)->getCode(),
            'quantity' => 1,
            'amount' => $amount,
            'customMessage' => $message,
        ]);

        $this->cartsClient->executeCustomRequest($request);
    }

    private function pickupCart(?string $localeCode = null): string
    {
        $request = $this->requestFactory->custom(
            sprintf('%s/shop/orders', $this->apiUrlPrefix),
            HttpRequest::METHOD_POST,
            ['HTTP_ACCEPT_LANGUAGE' => $localeCode ?? ''],
        );

        $this->cartsClient->executeCustomRequest($request);

        $tokenValue = $this->responseChecker->getValue($this->cartsClient->getLastResponse(), 'tokenValue');

        $this->sharedStorage->set('cart_token', $tokenValue);

        return $tokenValue;
    }
}
