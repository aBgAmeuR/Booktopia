<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class AddToCardForm
{
    use DefaultActionTrait;

    public ?string $id = null;

    public ?float $unitPrice = 0.0;
}
