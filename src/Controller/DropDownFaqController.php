<?php


namespace WebEtDesign\FaqBundle\Controller;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use WebEtDesign\CmsBundle\Controller\BaseCmsController;
use WebEtDesign\FaqBundle\Entity\Category;
use WebEtDesign\FaqBundle\Entity\Faq;

class DropDownFaqController extends BaseCmsController
{
    private $config;

    /**
     * @inheritDoc
     */
    public function __construct(ParameterBagInterface $parameterBag) {
        $this->config = $parameterBag->get('wd_faq.config');
    }

    public function __invoke(Request $request)
    {
        if ($this->config['use_category']) {
            $categories = $this->getDoctrine()->getRepository(Category::class)->findWithFaqs();

            $faqs = [];
            /** @var Category $category */
            foreach ($categories as $category) {
                $faqs[$category->getTitle()] = array_filter($category->getFaqs()->toArray(), function (Faq $faq){
                    return $faq->getVisible() === true || $faq->getVisible() === null;
                });
            }

        } else {
            $faqs = $this->getDoctrine()->getRepository(Faq::class)->findOrderedByPosition();
        }

        return $this->defaultRender([
            'faqs' => $faqs,
        ]);
    }
}
