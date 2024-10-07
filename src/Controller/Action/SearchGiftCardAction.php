<?php

declare(strict_types=1);

namespace Setono\SyliusGiftCardPlugin\Controller\Action;

use Setono\SyliusGiftCardPlugin\Form\Type\GiftCardSearchType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class SearchGiftCardAction
{
    public function __construct(private readonly FormFactoryInterface $formFactory, private readonly Environment $twig)
    {
    }

    public function __invoke(Request $request): Response
    {
        $searchGiftCardCommand = new SearchGiftCardCommand();
        $form = $this->formFactory->create(GiftCardSearchType::class, $searchGiftCardCommand);
        $form->handleRequest($request);

        return new Response($this->twig->render('@SetonoSyliusGiftCardPlugin/Shop/GiftCard/search.html.twig', [
            'form' => $form->createView(),
            'giftCard' => ($form->isSubmitted() && $form->isValid()) ? $searchGiftCardCommand->getGiftCard() : null,
        ]));
    }
}
