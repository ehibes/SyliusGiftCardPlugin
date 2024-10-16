<?php

declare(strict_types=1);

namespace Setono\SyliusGiftCardPlugin\Tests\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Doctrine\Persistence\ObjectManager;
use Setono\SyliusGiftCardPlugin\Api\Command\AddGiftCardToOrder;
use Setono\SyliusGiftCardPlugin\Factory\GiftCardFactoryInterface;
use Setono\SyliusGiftCardPlugin\Model\ProductInterface;
use Setono\SyliusGiftCardPlugin\Repository\GiftCardRepositoryInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class GiftCardContext implements Context
{
    public function __construct(private readonly SharedStorageInterface $sharedStorage, private readonly GiftCardRepositoryInterface $giftCardRepository, private readonly GiftCardFactoryInterface $giftCardFactory, private readonly ObjectManager $productManager, private readonly MessageBusInterface $messageBus)
    {
    }

    /**
     * todo this should probably be moved to a ProductContext instead
     *
     * @Given /^(this product) is a gift card$/
     */
    public function thisProductIsAGiftCard(ProductInterface $product): void
    {
        $product->setGiftCard(true);

        $this->productManager->flush();
    }

    /**
     * @Given /^(this product) is a configurable gift card$/
     */
    public function thisProductIsAConfigurableGiftCard(ProductInterface $product): void
    {
        $product->setGiftCard(true);
        $product->setGiftCardAmountConfigurable(true);

        $this->productManager->flush();
    }

    /**
     * @Given /^the store has a gift card with code "([^"]+)" valued at ("[^"]+")$/
     * @Given /^the store has a gift card with code "([^"]+)" valued at ("[^"]+") on (channel "[^"]+")$/
     */
    public function theStoreHasGiftCardWithCode(
        string $code,
        int $price,
        ?ChannelInterface $channel = null,
    ): void {
        if (null === $channel) {
            /** @var ChannelInterface $channel */
            $channel = $this->sharedStorage->get('channel');
        }

        $giftCard = $this->giftCardFactory->createNew();
        $giftCard->setCode($code);
        $giftCard->setChannel($channel);
        $giftCard->setAmount($price);
        $giftCard->setCurrencyCode($channel->getBaseCurrency()->getCode());
        $giftCard->enable();

        $this->giftCardRepository->add($giftCard);
    }

    /**
     * @Given /^the store has a gift card with code "([^"]+)" valued at ("[^"]+") associated to (customer "[^"]+")$/
     */
    public function theStoreHasGiftCardWithCodeForCustomer(
        string $code,
        int $price,
        CustomerInterface $customer,
    ): void {
        /** @var ChannelInterface $channel */
        $channel = $this->sharedStorage->get('channel');

        $giftCard = $this->giftCardFactory->createNew();
        $giftCard->setCode($code);
        $giftCard->setChannel($channel);
        $giftCard->setAmount($price);
        $giftCard->setCurrencyCode($channel->getBaseCurrency()->getCode());
        $giftCard->enable();
        $giftCard->setCustomer($customer);

        $this->giftCardRepository->add($giftCard);
    }

    /**
     * @Given My cart has gift card with code :code
     */
    public function iApplyGiftCardToOrder(string $code): void
    {
        /** @var string $cartToken */
        $cartToken = $this->sharedStorage->get('cart_token');
        $message = new AddGiftCardToOrder($cartToken);
        $message->setGiftCardCode($code);
        $this->messageBus->dispatch($message);
    }
}
