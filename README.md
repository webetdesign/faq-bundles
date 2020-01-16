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
    "webetdesign/faq-bundle": "^1.0"
  }
}
```
> Require the bundle into your `composer.json`. 

_____________

By default categories are disabled add this configuration to activate them.
```yaml
# config/packages/wd_faq.yaml
wd_faq:
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
web_et_design_cms:
  pages:
    faq:
      label: faq
      controller: WebEtDesign\FaqBundle\Controller\FaqController
      action: __invoke
      template: pages/faq.html.twig # TODO create your template
      contents:
        # TODO add contents according to your needs
        - { label: 'title', code: 'title', type: 'TEXT' }
```
> You can now create the pages in admin.
