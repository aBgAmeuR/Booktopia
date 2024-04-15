<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class ToggleWishlist
{
  public ?string $articleId = null;

  public ?bool $isInWishlist = false;
}
