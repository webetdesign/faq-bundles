<?php

declare(strict_types=1);

namespace WebEtDesign\FaqBundle\Admin;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Knp\Menu\ItemInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class CategoryAdmin extends AbstractAdmin
{
    private $config;

    protected string $translationDomain = 'FaqCategoryAdmin';

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

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('translations.title');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('position', 'actions', [
                'actions' => [
                    'move' => [
                        'template' => '@WDSortable/Default/_sort_drag_drop.html.twig',
                    ]
                ]
            ]);
        $list
            ->add('title')
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
        $form
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

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('title')
            ->add('position');
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection->add('move', $this->getRouterIdParameter().'/move/{position}');
        $collection->remove('show');

        if (!$this->config['admin_with_export']) {
            $collection->remove('export');
        }
    }
}
