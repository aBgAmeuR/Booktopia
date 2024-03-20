<?php

namespace App\Twig\Components;

use App\Form\AddToCartType;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsLiveComponent('add_to_cart_form')]
class AddToCartForm
{
    use DefaultActionTrait;

    #[LiveProp]
    public float $unitPrice = 0.0;

    #[LiveProp]
    public int $quantity = 1;

    public function getSubtotal(): float
    {
        return $this->unitPrice * $this->quantity;
    }
}