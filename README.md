# Kohana-errors

Just a really simple kohana module that sets custom error pages for web (outside development mode) and
CLI (always).

## Installation

Add kohana-errors to your composer.json and run `composer update` to install it.

```json
{
  "require": { "ingenerator/kohana-errors": "0.1.*@dev" }
}
```

## Basic Usage

In your bootstrap:
```php
Kohana::modules(array('kohana-errors' => BASEDIR.'vendor/ingenerator/kohana-errors');
```

To further customise the error page, create a view file at APPATH.views/errors/web_generic_error.php or 
APPATH.views/errors/cli_generic_error.php

Bear in mind that the custom error page will only be applied after your application has loaded the module -
so you still risk another error page showing if there are failures during very early bootstrapping of your
application.

Best practice is to configure your apache/nginx to show a custom static error page on fatal errors, rather
than trusting PHP to get it right...

## Testing and developing

There are no specs for this module, as it's very basic. Contributions are welcome.

## License

config is copyright 2014 inGenerator Ltd and released under the [BSD license](LICENSE).
