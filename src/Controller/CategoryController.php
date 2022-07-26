<?php


namespace WebEtDesign\FaqBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WebEtDesign\CmsBundle\Controller\BaseCmsController;
use WebEtDesign\FaqBundle\Entity\Category;
use WebEtDesign\FaqBundle\Repository\CategoryRepository;

class CategoryController extends BaseCmsController
{
    const ROUTE_FAQ_CATEGORIES = 'faq_categories';

    public function __construct(private CategoryRepository $categoryRepository) {}

    /**
     * @param Request $request
     * @param Category|null $category
     * @return Response
     * @Entity("category", expr="repository.findOneBySlug(category)")
     */
    public function __invoke(Request $request, Category $category = null): Response
    {
        $categories = $this->categoryRepository->findAllByPosition();
        $defaultCategory = false;

        if (!$category) {
            $category = $categories[0] ?? null;
            $defaultCategory = true;
        }

        return $this->defaultRender([
            'defaultCategory' => $defaultCategory,
            'category' => $category,
            'categories' => $categories,
        ]);
    }

}
