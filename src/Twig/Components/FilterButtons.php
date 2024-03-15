<?php

namespace App\Twig\Components;

use App\DTO\SearchDto;
use App\Form\CategorySearchType;
use App\Entity\Catalogue\Livre;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[AsLiveComponent]
final class FilterButtons extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

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

    protected function instantiateForm(): FormInterface
    {

        $this->getCategoryFromProducts();
        // we can extend AbstractController to get the normal shortcuts
        return $this->createForm(CategorySearchType::class, $this->searchDto);
    }

    #[LiveAction]
    public function save(Request $request)
    {
        $categorySearch = $request->request->get('category_search[category]');
        dd($request->request);

        // $category = $categorySearch['category'];
        $this->searchDto->category = $categorySearch;
        $this->submitForm();
        
        /** @var SearchDto $searchDto */
        $searchDto = $this->getForm()->getData();

        return $this->redirectToRoute('app_search', $searchDto->generateQueryParameters());
    }

    private function getCategoryFromProducts(): void
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
        $this->categories = array_keys(array_slice($categoryCount, 0, 5));
    }
}
