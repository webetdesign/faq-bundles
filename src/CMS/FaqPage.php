<?php

namespace WebEtDesign\FaqBundle\CMS;

use Symfony\Component\HttpFoundation\Request;
use WebEtDesign\CmsBundle\Attribute\AsCmsPage;
use WebEtDesign\CmsBundle\CMS\Block\TextBlock;
use WebEtDesign\CmsBundle\CMS\Template\AbstractPage;
use WebEtDesign\CmsBundle\CMS\Configuration\BlockDefinition;
use WebEtDesign\CmsBundle\CMS\Configuration\RouteAttributeDefinition;
use WebEtDesign\CmsBundle\CMS\Configuration\RouteDefinition;
use WebEtDesign\FaqBundle\Controller\CategoryController;
use WebEtDesign\FaqBundle\Controller\FaqController;
use WebEtDesign\FaqBundle\Entity\Category;
use WebEtDesign\FaqBundle\Entity\Faq;

#[AsCmsPage(self::CODE)]
class FaqPage extends AbstractPage
{
    const CODE = 'FAQ';

    protected ?string $label = 'FAQ - Affichage';

    protected ?string $template = 'pages/faq/faq.html.twig';

    public function getRoute(): ?RouteDefinition
    {
        return RouteDefinition::new()
            ->setController(FaqController::class)
            ->setAttributes([
                RouteAttributeDefinition::new('category')
                    ->setEntityClass(Category::class)
                    ->setEntityProperty('slug')
                    ->setDefault(null)
                    ->setRequirement(null)
                ,
                RouteAttributeDefinition::new('faq')
                    ->setEntityClass(Faq::class)
                    ->setEntityProperty('slug')
                    ->setDefault(null)
                    ->setRequirement(null)
            ])
            ->setName(FaqController::ROUTE_FAQ_SHOW)
            ->setMethods([
                Request::METHOD_GET,
            ]);
    }

    public function getBlocks(): iterable
    {
        yield BlockDefinition::new('title', TextBlock::code, 'Titre');
        yield BlockDefinition::new('subtitle', TextBlock::code, 'Sous-titre');
    }

}
