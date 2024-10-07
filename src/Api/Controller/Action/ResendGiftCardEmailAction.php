<?php

declare(strict_types=1);

namespace Setono\SyliusGiftCardPlugin\Api\Controller\Action;

use Setono\SyliusGiftCardPlugin\EmailManager\GiftCardEmailManagerInterface;
use Setono\SyliusGiftCardPlugin\Model\GiftCardInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class ResendGiftCardEmailAction
{
    public function __construct(private readonly GiftCardEmailManagerInterface $giftCardEmailManager)
    {
    }

    public function __invoke(GiftCardInterface $data): Response
    {
        if (($order = $data->getOrder()) instanceof OrderInterface) {
            $this->giftCardEmailManager->sendEmailWithGiftCardsFromOrder($order, [$data]);
        } elseif (($customer = $data->getCustomer()) instanceof CustomerInterface) {
            $this->giftCardEmailManager->sendEmailToCustomerWithGiftCard($customer, $data);
        } else {
            throw new BadRequestHttpException();
        }

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
