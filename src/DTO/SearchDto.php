<?php

namespace App\DTO;

class SearchDto
{
  public ?string $search = null;

  public ?string $category = null;

  public function generateQueryParameters(): array
  {
    return [
      'search' => $this->search,
      'category' => $this->category,
    ];
  }

}
