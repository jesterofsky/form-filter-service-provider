Form Filter Service Provider
======
Simple wrapper around [lexik/form-filter-bundle][1] to enable it to work with Silex

Instalation
--------

Through [Composer](http://getcomposer.org) as [jesterofsky/form-filter-service-provider][2].

Usage
--------

```php
// Register Service Provider
$app->register(new Jesterofsky\FormFilterProvider\FormFilterServiceProvider());
```
Create a folder in your twig tempaltes folder (I'm using a folder named 'common' here). 

Copy twig template **form_div_layout.html.twig** from vendor/form-filter-bundle/Lexik/Bundle/FormFilterBundle/Resources/views/Form to your your newly created folder and add the following to your twig configuration:

```php
$app['twig.form.templates'] = array(
    'form_div_layout.html.twig', 
    'common/form_div_layout.html.twig'
    );
```

Usage in controller is almost the same as for the bundle in Symfony. The only difference is that 
your call the sevice with **$app['lexik_form_filter.query_builder_updater']**. Please see the documentation at 
[lexik/form-filter-bundle][3] for details.

Where the first element of the array is twig's default form templates file and the second one is our file. (There is probably a better way to do this, but this works for now).

Creating your own data_extraction_method
--------
If you need to create your own data_extraction_method, the process is the same as for Symfony Bundle. The only difference is how to register new data_extraction_method as a service. Here instead of adding a definition to a service file, you would add:


```php
// add this before registering the Service Provider itself
$app['form-filter.data_extraction_methods'] = array(new SampleExtractionMethod());

```

TODO
--------
1. Add support for ```force_case_insensitivity``` option
2. Smarter way to load form template

[1]: https://github.com/lexik/LexikFormFilterBundle
[2]: https://packagist.org/packages/jesterofsky/form-filter-service-provider
[3]: https://github.com/lexik/LexikFormFilterBundle/blob/master/Resources/doc/index.md#4-working-with-the-bundle
[4]: https://github.com/lexik/LexikFormFilterBundle/blob/master/Resources/doc/index.md#5-the-filtertypeextension-type