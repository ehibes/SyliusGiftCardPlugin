<?php

declare(strict_types=1);

namespace Setono\SyliusGiftCardPlugin\Tests\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Setono\SyliusGiftCardPlugin\Model\GiftCardInterface;
use Setono\SyliusGiftCardPlugin\Model\OrderInterface;
use Sylius\Behat\Context\Setup\OrderContext;
use Sylius\Behat\Context\Ui\Shop\Checkout\CheckoutCompleteContext;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Webmozart\Assert\Assert;

final class CheckoutContext implements Context
{
    public function __construct(private readonly CheckoutCompleteContext $checkoutCompleteContext, private readonly OrderContext $orderContext, private readonly OrderRepositoryInterface $orderRepository, private readonly EntityManagerInterface $giftCardManager)
    {
    }

    /**
     * @When I confirm my order and pay successfully
     */
    public function iConfirmMyOrderAndPaySuccessfully(): void
    {
        $this->checkoutCompleteContext->iConfirmMyOrder();

        /** @var OrderInterface[] $orders */
        $orders = $this->orderRepository->findAll();

        $this->orderContext->thisOrderIsAlreadyPaid($orders[0]);
    }

    /**
     * @Then the gift card :giftCard should be disabled
     */
    public function theGiftCardWithTheCodeShouldBeInactive(GiftCardInterface $giftCard): void
    {
        // todo this is needed, but I don't know why
        $this->giftCardManager->refresh($giftCard);

        Assert::same($giftCard->getAmount(), 0);
        Assert::false($giftCard->isEnabled());
    }
}
