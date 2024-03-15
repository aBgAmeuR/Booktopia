<?php

namespace App\Twig\Components;

use App\Entity\Catalogue\Livre;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent()]
final class ProductCards
{
    use DefaultActionTrait;

    /**
     * @var Livre[]
     */
    #[LiveProp(updateFromParent: true)]
    public ?array $products = [];

    #[LiveProp(updateFromParent: true)]
    public int $results = 0;

}