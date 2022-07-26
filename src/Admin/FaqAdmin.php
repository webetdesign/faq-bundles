<?php

declare(strict_types=1);

namespace WebEtDesign\FaqBundle\Admin;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Filter\BooleanFilter;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class FaqAdmin extends AbstractAdmin
{
    private $config;

    protected string $translationDomain = 'FaqAdmin';

    private ParameterBagInterface $parameterBag;

    protected function configureQuery(ProxyQueryInterface $query): ProxyQueryInterface
    {
        $query = parent::configureQuery($query);
        $query->orderBY('o.position', 'ASC');
        return $query;
    }

    /**
     * @inheritDoc
     */
    public function __construct($code, $class, $baseControllerName, ParameterBagInterface $parameterBag)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->parameterBag = $parameterBag;
        $this->config       = $this->parameterBag->get('wd_faq.config');
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
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
            $filter->add(
                'category',
                null,
                [
                    'show_filter'     => true,
                    'advanced_filter' => false,
                ]
            );
        }
    }

    protected function configureListFields(ListMapper $list): void
    {
        $filters = $this->getFilterParameters();
        if ($this->getRequest()->get('_route') === 'admin_webetdesign_faq_faq_list' && (!$this->config['use_category'] || (isset($filters['category']) && !empty($filters['category']['value'])))) {
            $list
                ->add('position', 'actions', [
                    'actions' => [
                        'move' => [
                            'template' => '@WDSortable/Default/_sort_drag_drop.html.twig',
                        ]
                    ]
                ]);
        }

        $list
            ->add('question')
            ->add('visible');

        if ($this->config['use_category'] && !$this->isChild()) {
            $list->addIdentifier('category');
        }

        $list
            ->add(
                ListMapper::NAME_ACTIONS,
                null,
                [
                    'actions' => [
                        'edit'   => [],
                        'delete' => [],
                    ],
                ]
            );
    }

    protected function configureFormFields(FormMapper $form): void
    {
        if ($this->config['use_category']) {
            $form->add('category', ModelListType::class);
        }

        $form
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
                            'field_type'         => CKEditorType::class,
                            'label'              => 'form.label_answer',
                            'translation_domain' => $this->translationDomain,
                            'required'           => true,
                            'config_name'   => $this->config['ckeditor_context'],
                            'attr'               => [
                                'rows' => 15,
                            ],
                        ],
                        'seoTitle' => [
                            'field_type'         => TextType::class,
                            'label'              => 'form.seo_title',
                            'required' => false,
                            'translation_domain' => $this->translationDomain,
                        ],
                        'seoDescription' => [
                            'field_type'         => TextType::class,
                            'label'              => 'form.seo_description',
                            'required' => false,
                            'translation_domain' => $this->translationDomain,
                        ],
                        'seoKeywords' => [
                            'field_type'         => TextType::class,
                            'label'              => 'form.seo_keywords',
                            'required' => false,
                            'translation_domain' => $this->translationDomain,
                        ],
                    ],
                    'excluded_fields'  => ['slug'],
                ]
            );
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('question')
            ->add('answer')
            ->add('visible');
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection->add('move', $this->getRouterIdParameter() . '/move/{position}');
        $collection->remove('show');

        if (!$this->config['admin_with_export']) {
            $collection->remove('export');
        }
    }
}
