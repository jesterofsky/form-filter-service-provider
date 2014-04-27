<?php

namespace Jesterofsky\Silex;

use Silex\Application;
use Silex\ServiceProviderInterface;

use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdater;
use Lexik\Bundle\FormFilterBundle\Filter\DataExtractor\FormDataExtractor;
use Lexik\Bundle\FormFilterBundle\Filter\DataExtractor\Method\TextExtractionMethod;
use Lexik\Bundle\FormFilterBundle\Filter\DataExtractor\Method\DefaultExtractionMethod;
use Lexik\Bundle\FormFilterBundle\Filter\DataExtractor\Method\ValueKeysExtractionMethod;
use Lexik\Bundle\FormFilterBundle\Filter\Extension\FilterExtension;
use Lexik\Bundle\FormFilterBundle\Event\FilterEvents;
use Lexik\Bundle\FormFilterBundle\Event\Listener\PrepareListener;
use Lexik\Bundle\FormFilterBundle\Event\Subscriber\DoctrineSubscriber;

class FormFilterServiceProvider implements ServiceProviderInterface {

    public function register(Application $app) {

        $app['lexik_form_filter.query_builder_updater'] = $app->share(function ($app) {

            $frmDataExt = $app['lexik_form_filter.form_data_extractor'];
            $dispatcher = $app['dispatcher'];

            return new FilterBuilderUpdater($frmDataExt, $dispatcher);
        });

        $app['lexik_form_filter.form_data_extractor'] = $app->share(function ($app) {
            $formDataExtractor = new FormDataExtractor();

            $formDataExtractor->addMethod(new DefaultExtractionMethod());
            $formDataExtractor->addMethod(new TextExtractionMethod());
            $formDataExtractor->addMethod(new ValueKeysExtractionMethod());

            if ($app->offsetExists('form-filter.data_extraction_methods')) 
                foreach ($app['form-filter.data_extraction_methods'] as $extMethod)
                    $formDataExtractor->addMethod($extMethod);
            
            return $formDataExtractor;
        });

        $app['form.extensions'] = $app->share($app->extend('form.extensions', function($extensions) use ($app) {
                $extensions[] = new FilterExtension();

                return $extensions;
            }));

        $app['dispatcher']->addListener(FilterEvents::PREPARE, array(new PrepareListener(), 'onFilterBuilderPrepare'));

        $app['dispatcher']->addSubscriber(new DoctrineSubscriber());
    }

    public function boot(Application $app) {
        
    }

}
