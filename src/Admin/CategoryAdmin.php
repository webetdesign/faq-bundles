<?php

declare(strict_types=1);

namespace WebEtDesign\FaqBundle\Admin;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class CategoryAdmin extends AbstractAdmin
{

    protected $datagridValues = [
        '_page'       => 1,
        '_sort_order' => 'ASC',
        '_sort_by'    => 'position',
    ];

    /**
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $parameterBag;

    /**
     * @inheritDoc
     */
    public function __construct(
        $code,
        $class,
        $baseControllerName = null,
        ParameterBagInterface $parameterBag
    ) {
        parent::__construct($code, $class, $baseControllerName);
        $this->parameterBag = $parameterBag;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('id')
            ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        unset($this->listModes['mosaic']);

        $listMapper
            ->add('position', 'actions', [
                'actions' => [
                    'move' => [
                        'template'                  => '@PixSortableBehavior/Default/_sort_drag_drop.html.twig',
                        'enable_top_bottom_buttons' => false,
                    ]
                ]
            ]);
        $listMapper
            ->add('title')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('_action', null, [
                'actions' => [
                    'show'   => [],
                    'edit'   => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('translations', TranslationsType::class, [
                'label'            => false,
                'locales'          => $this->parameterBag->get('wd_faq.locales'),
                'default_locale'   => $this->parameterBag->get('wd_faq.default_locale'),
                'required_locales' => [$this->parameterBag->get('wd_faq.default_locale')],
                'fields'           => [
                    'title'       => [
                        'field_type' => TextType::class,
                        'label'      => 'Titre',
                    ],
                ],
                'excluded_fields'  => ['slug']
            ])
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('id')
            ->add('title')
            ->add('position')
            ->add('createdAt')
            ->add('updatedAt');
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->add('move', $this->getRouterIdParameter() . '/move/{position}');
    }
}
