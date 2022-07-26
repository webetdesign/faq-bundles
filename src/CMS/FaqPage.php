<?php

namespace WebEtDesign\FaqBundle\CMS;

use Symfony\Component\HttpFoundation\Request;
use WebEtDesign\CmsBundle\Attribute\AsCmsPage;
use WebEtDesign\CmsBundle\CmsBlock\TextBlock;
use WebEtDesign\CmsBundle\CmsTemplate\AbstractPage;
use WebEtDesign\CmsBundle\DependencyInjection\Models\BlockDefinition;
use WebEtDesign\CmsBundle\DependencyInjection\Models\RouteAttributeDefinition;
use WebEtDesign\CmsBundle\DependencyInjection\Models\RouteDefinition;
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
