# Faq Bundle

## Install

```json
{
  "repositories": [
    {
      "type": "git",
      "url": "https://github.com/webetdesign/faq-bundles.git"
    },
    {
      "type": "git",
      "url": "https://github.com/webetdesign/wd-sortable-bundle.git"
    }
  ]
}
```

> Register the repository into your `composer.json`.

```json
{
  "require": {
    "webetdesign/faq-bundle": "^3.0"
  }
}
```

> Require the bundle into your `composer.json`.

> Register the bundles in ```config/bundles.php```
```php
  return [
    WebEtDesign\SortableBundle\WDSortableBundle::class => ['all' => true],
    WebEtDesign\FaqBundle\FaqBundle::class => ['all' => true],
]
```
_____________

By default categories are disabled add this configuration to activate them.

```yaml
# config/packages/webetdesign/wd_faq.yaml
wd_faq:
  locales: '%locales%'
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
        keep_open: false
        label: FAQ
        icon: '<i class="fa fa-question"></i>'
        on_top: true
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
        keep_open: false
        label: FAQ
        icon: '<i class="fa fa-question"></i>'
        items:
          - wd_faq.admin.faq
          - wd_faq.admin.category
```

```yaml
# config/packages/sonata_admin.yaml

assets:
  extra_javascripts:
    [ ... ]
    - bundles/wdsortable/js/init.js
```

> You can now create the pages in admin.
> You can extend base pages to add informations
