<?php


namespace WebEtDesign\FaqBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WebEtDesign\CmsBundle\Controller\BaseCmsController;
use WebEtDesign\FaqBundle\Entity\Category;
use WebEtDesign\FaqBundle\Repository\CategoryRepository;

class CategoryController extends BaseCmsController
{
    /**
     * @var CategoryRepository
     */
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository) {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param Request $request
     * @param Category|null $category
     * @return Response
     * @Entity("category", expr="repository.findOneBySlug(category)")
     */
    public function __invoke(Request $request, Category $category = null)
    {
        $categories = $this->categoryRepository->findAllByPosition();

        if (!$category) {
            $category = $categories[0] ?? null;
        }

        return $this->defaultRender([
            'category' => $category,
            'categories' => $categories,
        ]);
    }

}
