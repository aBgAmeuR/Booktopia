<?php

namespace App\Twig\Components;

use App\DTO\SearchDto;
use App\Entity\Catalogue\Livre;
use App\Repository\LivreRepository;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent()]
final class FilterProducts
{
    use DefaultActionTrait;

    #[LiveProp]
    public ?SearchDto $searchDto = null;

    /**
     * @var Livre[]
     */
    #[LiveProp]
    public ?array $products = [];

    /**
     * @var string[]
     */
    #[LiveProp]
    public ?array $categories = []; 


    #[LiveProp()]
    public int $results = 0;

    public function __construct(private LivreRepository $livreRepository)
    {
        
    }

    public function mount(SearchDto $searchDto)
    {
        $this->searchDto = $searchDto;
        $this->searchProducts();
    }

    #[LiveListener('updateProducts')]
    public function udpate(#[LiveArg()] string $searchDto)
    {
        $this->searchDto = unserialize($searchDto);
        $this->searchProducts();
    }

    private function searchProducts(): void
    {
        $this->products = $this->livreRepository->search($this->searchDto);
        $this->results = count($this->products);
        $this->categories = $this->getCategoryFromProducts();
    }

    private function getCategoryFromProducts(): array
    {
        $categoryCount = [];
        foreach ($this->products as $product) {
            $category = $product->getCategorie();
            if (!empty ($category)) {
                if (isset ($categoryCount[$category])) {
                    $categoryCount[$category]++;
                } else {
                    $categoryCount[$category] = 1;
                }
            }
        }

        arsort($categoryCount);
        return array_keys(array_slice($categoryCount, 0, 5));
    }
}