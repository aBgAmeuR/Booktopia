<?php

namespace App\Twig\Components;

use App\DTO\SearchDto;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('header')]
class Header
{
  public bool $is_logged_in;

  public SearchDto $searchDto;

  public function __construct(?SearchDto $searchDto = null)
  {
    $this->searchDto = $searchDto;
  }
}