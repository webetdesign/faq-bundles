<?php

declare(strict_types=1);

namespace WebEtDesign\FaqBundle\Admin;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Filter\BooleanFilter;
use Sonata\FormatterBundle\Form\Type\SimpleFormatterType;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class FaqAdmin extends AbstractAdmin
{
    private $config;

    protected $translationDomain = 'FaqAdmin';

    /**
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $parameterBag;

    protected $datagridValues = [
        '_page'       => 1,
        '_sort_order' => 'ASC',
        '_sort_by'    => 'position',
    ];

    /**
     * @inheritDoc
     */
    public function __construct($code, $class, $baseControllerName, ParameterBagInterface $parameterBag)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->parameterBag = $parameterBag;
        $this->config       = $this->parameterBag->get('wd_faq.config');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('translations.question', null, ['advanced_filter' => false])
            ->add(
                'visible',
                BooleanFilter::class,
                [
                    'advanced_filter' => false,
                    'field_options'   => ['choice_translation_domain' => $this->translationDomain],
                ]
            );

        if ($this->config['use_category']) {
            $datagridMapper->add(
                'category',
                null,
                [
                    'show_filter'     => true,
                    'advanced_filter' => false,
                ]
            );
        }
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        unset($this->listModes['mosaic']);

        $filters = $this->getFilterParameters();
        if (!$this->config['use_category'] || (isset($filters['category']) && !empty($filters['category']['value']))) {
            $listMapper
                ->add(
                    'position',
                    'actions',
                    [
                        'actions' => [
                            'move' => [
                                'template'                  => '@PixSortableBehavior/Default/_sort_drag_drop.html.twig',
                                'enable_top_bottom_buttons' => false,
                            ],
                        ],
                    ]
                );
        }

        $listMapper
            ->add('question')
            ->add('visible');

        if ($this->config['use_category'] && !$this->isChild()) {
            $listMapper->addIdentifier('category');
        }

        $listMapper
            ->add(
                '_action',
                null,
                [
                    'actions' => [
                        'edit'   => [],
                        'delete' => [],
                    ],
                ]
            );
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        if ($this->config['use_category']) {
            $formMapper->add('category', ModelListType::class);
        }

        $formMapper
            ->add(
                'visible',
                CheckboxType::class,
                [
                    'required' => false,
                ]
            )
            ->add(
                'translations',
                TranslationsType::class,
                [
                    'label'            => false,
                    'locales'          => $this->parameterBag->get('wd_faq.locales'),
                    'default_locale'   => $this->parameterBag->get('wd_faq.default_locale'),
                    'required_locales' => [$this->parameterBag->get('wd_faq.default_locale')],
                    'fields'           => [
                        'question' => [
                            'field_type'         => TextType::class,
                            'label'              => 'form.label_question',
                            'translation_domain' => $this->translationDomain,
                        ],
                        'answer'   => [
                            'field_type'         => SimpleFormatterType::class,
                            'label'              => 'form.label_answer',
                            'translation_domain' => $this->translationDomain,
                            'required'           => true,
                            'format'             => 'richhtml',
                            'ckeditor_context'   => $this->config['ckeditor_context'],
                            'attr'               => [
                                'rows' => 15,
                            ],
                        ],
                    ],
                    'excluded_fields'  => ['slug'],
                ]
            );
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('id')
            ->add('question')
            ->add('answer')
            ->add('visible');
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('move', $this->getRouterIdParameter() . '/move/{position}');
        $collection->remove('show');

        if (!$this->config['admin_with_export']) {
            $collection->remove('export');
        }
    }
}
