# Faq Bundle

## Install

```json
{
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/webetdesign/faq-bundles.git"
        }
    ]
}
```
> Register the repository into your `composer.json`.

```json
{
  "require": {
    "webetdesign/faq-bundle": "^1.1.0"
  }
}
```
> Require the bundle into your `composer.json`. 

_____________

By default categories are disabled add this configuration to activate them.
```yaml
# config/packages/wd_faq.yaml
wd_faq:
  locale: '%locales%'
  default_locale: '%default_locale%'
  configuration:
    use_category: true
```

```yaml
# config/packages/sonata_admin.yaml
sonata_admin:
    dashboard:
        groups:
            FAQ:
                keep_open:        false
                label:            FAQ
                icon:             '<i class="fa fa-question"></i>'
                on_top:           true
                items:
                    - wd_faq.admin.faq
```
> if you use category use the config bellow
```yaml
# config/packages/sonata_admin.yaml
sonata_admin:
    dashboard:
        groups:
            FAQ:
                keep_open:        false
                label:            FAQ
                icon:             '<i class="fa fa-question"></i>'
                items:
                    - wd_faq.admin.faq
                    - wd_faq.admin.category
```

```yaml
# config/packages/stof_doctrine_extensions.yaml
stof_doctrine_extensions:
    default_locale: fr
    orm:
        default:
            [...]
            sortable: true

```

```yaml
# config/packages/sonata_admin.yaml

  assets:
    extra_javascripts:
      [...]
      - bundles/pixsortablebehavior/js/init.js
```

```yaml
web_et_design_cms:
  pages:
    faq_category:
      label: Faq catégories
      controller: WebEtDesign\FaqBundle\Controller\CategoryController
      action: __invoke
      template: page/faq/category.html.twig # TODO create your template
      route: faq_category
      params:
        category:
          default: null
          requirement: null
          entity: WebEtDesign\FaqBundle\Entity\Category
          property: slug
      contents:
        # TODO add contents according to your needs
        - { label: 'title', code: 'title', type: 'TEXT' }
        - { label: 'subtitle', code: 'subtitle', type: 'TEXT' }
    
    faq:
      label: faq
      controller: WebEtDesign\FaqBundle\Controller\FaqController
      action: __invoke
      template: page/faq/faq.html.twig # TODO create your template
      route: faq
      params:
        category:
          default: null
          requirement: null
          entity: WebEtDesign\FaqBundle\Entity\Category
          property: slug
        faq:
          default: null
          requirement: null
          entity: WebEtDesign\FaqBundle\Entity\Faq
          property: slug
      contents:
        # TODO add contents according to your needs
        - { label: 'title', code: 'title', type: 'TEXT' }
        - { label: 'subtitle', code: 'subtitle', type: 'TEXT' }

    faq_dropdown:
      label: faq
      controller: WebEtDesign\FaqBundle\Controller\DropDownFaqController
      action: __invoke
      template: pages/faq.html.twig # TODO create your template
      contents:
        # TODO add contents according to your needs
        - { label: 'title', code: 'title', type: 'TEXT' }
```
> You can now create the pages in admin. 
> Different controllers are available if you want a page with dropdowns or several pages (category, faq)
