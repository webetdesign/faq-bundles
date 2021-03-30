<?php

declare(strict_types=1);

namespace WebEtDesign\FaqBundle\Admin;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Knp\Menu\ItemInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
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
    private $config;

    protected $translationDomain = 'FaqCategoryAdmin';

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

    protected function configureTabMenu(ItemInterface $menu, $action, AdminInterface $childAdmin = null): void
    {
        if (!$childAdmin && !in_array($action, ['edit', 'show'])) {
            return;
        }

        $admin = $this->isChild() ? $this->getParent() : $this;
        $id    = $admin->getRequest()->get('id');


        $menu->addChild('tab_category_edit', $admin->generateMenuUrl('edit', ['id' => $id]))
             ->setCurrent($action === 'edit' && $childAdmin === null);


        if ($this->isGranted('LIST')) {
            $menu->addChild(
                'tab_category_faq_list',
                $admin->generateMenuUrl('wd_faq.admin.faq.list', ['id' => $id])
            )->setCurrent($childAdmin !== null);
        }
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('translations.title');
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        unset($this->listModes['mosaic']);

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
        $listMapper
            ->add('title')
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
        $formMapper
            ->add(
                'translations',
                TranslationsType::class,
                [
                    'label'            => false,
                    'locales'          => $this->parameterBag->get('wd_faq.locales'),
                    'default_locale'   => $this->parameterBag->get('wd_faq.default_locale'),
                    'required_locales' => [$this->parameterBag->get('wd_faq.default_locale')],
                    'fields'           => [
                        'title' => [
                            'field_type'         => TextType::class,
                            'label'              => 'form.label_title',
                            'translation_domain' => $this->translationDomain,
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
            ->add('title')
            ->add('position');
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('move', $this->getRouterIdParameter().'/move/{position}');
        $collection->remove('show');

        if (!$this->config['admin_with_export']) {
            $collection->remove('export');
        }
    }
}
