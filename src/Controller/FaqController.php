<?php


namespace WebEtDesign\FaqBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WebEtDesign\CmsBundle\Controller\BaseCmsController;
use WebEtDesign\FaqBundle\Entity\Category;
use WebEtDesign\FaqBundle\Entity\Faq;
use WebEtDesign\FaqBundle\Repository\CategoryRepository;

class FaqController extends BaseCmsController
{
    const ROUTE_FAQ_SHOW = 'faq_show';

    public function __construct(private CategoryRepository $categoryRepository, private ParameterBagInterface $parameterBag) {}

    /**
     * @param Request $request
     * @param Faq $faq
     * @param Category|null $category
     * @return Response
     *
     * @Entity("category", expr="repository.findOneBySlug(category)")
     * @Entity("faq", expr="repository.findOneBySlug(faq)")
     *
     */
    public function __invoke(Request $request, Faq $faq, Category $category = null): Response
    {
        $config = $this->parameterBag->get('wd_faq.config');

        if ($config['use_category']) {
            $categories = $this->categoryRepository->findAllByPosition();
        }

        return $this->defaultRender([
            'faq' => $faq,
            'category' => $category,
            'categories' => $categories ?? null,
        ]);
    }

}
