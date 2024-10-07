<?php

declare(strict_types=1);

namespace Setono\SyliusGiftCardPlugin\Tests\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Setono\SyliusGiftCardPlugin\Model\ProductInterface;
use Setono\SyliusGiftCardPlugin\Tests\Behat\Page\Admin\Product\CreateSimpleProductPageInterface;
use Webmozart\Assert\Assert;

final class ManagingGiftCardsContext implements Context
{
    public function __construct(private readonly CreateSimpleProductPageInterface $createGiftCardPage)
    {
    }

    /**
     * @When I set its gift card value to true
     */
    public function iSetGiftCardToTrue(): void
    {
        $this->createGiftCardPage->specifyGiftCard(true);
    }

    /**
     * @Then the product :product should be a gift card
     */
    public function theProductShouldBeAGiftCard(ProductInterface $product): void
    {
        Assert::true($product->isGiftCard());
    }
}
