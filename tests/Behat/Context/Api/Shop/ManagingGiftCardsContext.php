<?php

declare(strict_types=1);

namespace Setono\SyliusGiftCardPlugin\Tests\Behat\Context\Api\Shop;

use Behat\Behat\Context\Context;
use Sylius\Behat\Client\ApiClientInterface;
use Sylius\Behat\Client\RequestFactoryInterface;
use Sylius\Behat\Client\ResponseCheckerInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Currency\Model\CurrencyInterface;
use Symfony\Component\HttpFoundation\Request as HTTPRequest;
use Webmozart\Assert\Assert;

final class ManagingGiftCardsContext implements Context
{
    public function __construct(private readonly ApiClientInterface $client, private readonly ResponseCheckerInterface $responseChecker, private readonly SharedStorageInterface $sharedStorage, private readonly RequestFactoryInterface $requestFactory)
    {
    }

    /**
     * @When I browse gift cards
     */
    public function iBrowseGiftCards(): void
    {
        $this->client->index('gift-cards');
    }

    /**
     * @When I open the gift card :code page
     */
    public function iOpenGiftCardPage(string $code): void
    {
        $this->client->show('gift-cards', $code);
    }

    /**
     * @Given I apply gift card with code :code
     */
    public function iApplyGiftCardToOrder(string $code): void
    {
        $this->applyGiftCardToOrder($code);
    }

    /**
     * @Given I remove gift card with code :code
     */
    public function iRemoveGiftCardFromOrder(string $code): void
    {
        $this->removeGiftCardFromOrder($code);
    }

    /**
     * @Then /^Gift cards list should contain a gift card with code "([^"]+)"$/
     */
    public function giftCardsListShouldContain(string $code): void
    {
        $response = $this->client->index('gift-cards');

        Assert::notEmpty($this->responseChecker->getCollectionItemsWithValue($response, 'code', $code));
    }

    /**
     * @Then /^Gift cards list should not contain a gift card with code "([^"]+)"$/
     */
    public function giftCardsListShouldNotContain(string $code): void
    {
        $response = $this->client->index('gift-cards');

        Assert::isEmpty($this->responseChecker->getCollectionItemsWithValue($response, 'code', $code));
    }

    /**
     * @Then /^It should be valued at ("[^"]+")$/
     */
    public function itShouldBeValuedAt(int $amount): void
    {
        Assert::same($this->responseChecker->getValue($this->client->getLastResponse(), 'amount'), $amount);
    }

    /**
     * @Then /^It should be initially valued at ("[^"]+")$/
     */
    public function itShouldBeInitiallyValuedAt(int $amount): void
    {
        Assert::same($this->responseChecker->getValue($this->client->getLastResponse(), 'initialAmount'), $amount);
    }

    /**
     * @Then It should have :currency currency
     */
    public function itShouldHaveCurrency(CurrencyInterface $currency): void
    {
        Assert::same($this->responseChecker->getValue($this->client->getLastResponse(), 'currencyCode'), $currency->getCode());
    }

    /**
     * @Then the gift card :code should be disabled
     */
    public function theGiftCardShouldBeDisabled(string $code): void
    {
        $this->client->show('gift-cards', $code);

        Assert::same($this->responseChecker->getValue($this->client->getLastResponse(), 'enabled'), false);
    }

    /**
     * @Then the gift card :code should (still) be enabled
     */
    public function theGiftCardShouldBeEnabled(string $code): void
    {
        $this->client->show('gift-cards', $code);

        Assert::same($this->responseChecker->getValue($this->client->getLastResponse(), 'enabled'), true);
    }

    private function applyGiftCardToOrder(string $giftCardCode): void
    {
        $request = $this->requestFactory->customItemAction(
            'shop',
            'gift-cards',
            $giftCardCode,
            HTTPRequest::METHOD_PATCH,
            'add-to-order',
        );
        $request->setContent(['orderTokenValue' => $this->sharedStorage->get('cart_token')]);
        $this->client->executeCustomRequest($request);
    }

    private function removeGiftCardFromOrder(string $giftCardCode): void
    {
        $request = $this->requestFactory->customItemAction(
            'shop',
            'gift-cards',
            $giftCardCode,
            HTTPRequest::METHOD_PATCH,
            'remove-from-order',
        );

        $request->setContent(['orderTokenValue' => $this->sharedStorage->get('cart_token')]);

        $this->client->executeCustomRequest($request);
    }
}
