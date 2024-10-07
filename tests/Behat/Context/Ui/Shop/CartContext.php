<?php

declare(strict_types=1);

namespace Setono\SyliusGiftCardPlugin\Tests\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Setono\SyliusGiftCardPlugin\Model\ProductInterface;
use Setono\SyliusGiftCardPlugin\Tests\Behat\Page\Shop\Cart\SummaryPageInterface;
use Setono\SyliusGiftCardPlugin\Tests\Behat\Page\Shop\Product\ShowPageInterface;
use Webmozart\Assert\Assert;

final class CartContext implements Context
{
    public function __construct(private readonly SummaryPageInterface $summaryPage, private readonly ShowPageInterface $productShowPage)
    {
    }

    /**
     * @When I apply gift card with code :code
     */
    public function iUseGiftCardWithCode(string $code): void
    {
        $this->summaryPage->applyGiftCard($code);
    }

    /**
     * @Then my discount gift card should be :giftCardTotal
     */
    public function myDiscountGiftCardShouldBe(string $giftCardTotal): void
    {
        $this->summaryPage->open();

        Assert::same($this->summaryPage->getGiftCardTotal(), $giftCardTotal);
    }

    /**
     * @When /^I add (this product) to the cart with amount ("[^"]+") and custom message "([^"]+)"$/
     */
    public function iAddProductWithAmountAndMessage(ProductInterface $product, int $amount, string $message): void
    {
        $this->productShowPage->open(['slug' => $product->getSlug()]);
        $this->productShowPage->changeAmount((string) ($amount / 100));
        $this->productShowPage->defineCustomMessage($message);
        $this->productShowPage->addToCart();
    }
}
