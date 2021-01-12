<?php

declare(strict_types=1);

namespace WebEtDesign\FaqBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\FormatterBundle\Form\Type\SimpleFormatterType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

final class FAQAdmin extends AbstractAdmin
{
    private $config;

    /**
     * @inheritDoc
     */
    public function __construct($code, $class, $baseControllerName, $config)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->config = $config;
    }


    protected $datagridValues = [
        '_page'       => 1,
        '_sort_order' => 'ASC',
        '_sort_by'    => 'position',
    ];

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('id')
            ->add('visible', null, [
                'label' => 'Visible',
            ]);

        if ($this->config['use_category']) {
            $datagridMapper->add('category', null, [
                'show_filter' => true,
                'label' => 'Catégorie'
            ]);
        }
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        unset($this->listModes['mosaic']);

        $filters = $this->getFilterParameters();
        if (!$this->config['use_category'] || isset($filters['category']) && !empty($filters['category']['value'])) {
            $listMapper
                ->add('position', 'actions', [
                    'actions' => [
                        'move' => [
                            'template'                  => '@PixSortableBehavior/Default/_sort_drag_drop.html.twig',
                            'enable_top_bottom_buttons' => false,
                        ]
                    ]
                ]);
        }

        if ($this->config['use_category']) {
            $listMapper->addIdentifier('category', null, [
                'label' => 'Catégorie'
            ]);
        }

        $listMapper
            ->add('question', null,  [
                'label' => 'Question'
            ])
            ->add('createdAt', null,  [
                'label' => 'Date de création',
                'format' => 'd/m/y'
            ])
            ->add('updatedAt', null,  [
                'label' => 'Dernière modification',
                'format' => 'd/m/y'
            ])
            ->add('visible', null, [
                'label' => 'Visible',
            ])
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
        if ($this->config['use_category']) {
            $formMapper->add('category', ModelListType::class, [
                'label' => 'Catégorie'
            ]);
        }

        $formMapper
            ->add('question', null,  [
                'label' => 'Question'
            ])
            ->add('answer', SimpleFormatterType::class,
                [
                    'required'         => false,
                    'format'           => 'richhtml',
                    'ckeditor_context' => 'default',
                    'label' => 'Réponse',
                    'attr'             => [
                        'rows' => 15
                    ]
                ])
            ->add('visible', CheckboxType::class, [
                'label' => 'Visible',
                'required' => false
            ])
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('question', null,  [
                'label' => 'Question'
            ])
            ->add('createdAt', null,  [
                'label' => 'Date de création',
                'format' => 'd/m/y'
            ])
            ->add('updatedAt', null,  [
                'label' => 'Dernière modification',
                'format' => 'd/m/y'
            ])
            ->add('answer', null, [
                'label' => 'Réponse',
            ])
            ->add('visible', null, [
                'label' => 'Visible',
            ]);
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->add('move', $this->getRouterIdParameter() . '/move/{position}');
    }
}
