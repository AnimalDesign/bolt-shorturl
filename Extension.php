<?php

namespace Bolt\Extension\Animal\Shorturl;

use Bolt\Application;
use Bolt\BaseExtension;
use Silex\Application as BoltApplication;
use Symfony\Component\HttpFoundation\Request;

class Extension extends BaseExtension
{
    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->app['config']->getFields()->addField(new Field\ShorturlField());
        if ($this->app['config']->getWhichEnd() == 'backend') {
            $this->app['htmlsnippets'] = true;
            $this->app['twig.loader.filesystem']->prependPath(__DIR__.'/assets');
        }
    }

    public function initialize()
    {
        $config = $this->config;

        if ($this->app['config']->getWhichEnd() == 'frontend') {
            $this->app->before(function (Request $request) use ($config, $app) {
                $requestedPath = trim($request->getPathInfo(), '/');

                // Abort, if not a shorturl
                if (!preg_match('/'.($config['prefix'] ? $config['prefix'].'\/' : '').'[a-zA-Z0-9\-_.]{2,'.$config['maxlength'].'}$/', $requestedPath)) {
                    return;
                }

                // Get all fields of type shorturl
                $contentTypes = $this->app['config']->get('contenttypes');
                foreach ($contentTypes as $name => $contentType) {
                    foreach ($contentType['fields'] as $field) {
                        if ($field['type'] === 'shorturl') {
                            $contentTypeContent = $this->app['storage']->getContent($name, array());
                            foreach ($contentTypeContent as $content) {
                                if (!empty($content[$field['type']]) && $requestedPath === ($config['prefix'] ? $config['prefix'].'/' : '').$content[$field['type']]) {
                                    return $this->app->redirect($request->getBaseUrl().$content->link(), 302);
                                }
                            }
                        }
                    }
                }
            }, BoltApplication::EARLY_EVENT);
        }
    }

    /**
     * Set the defaults for configuration parameters.
     *
     * @return array
     */
    protected function getDefaultConfig()
    {
        return array(
            'maxlength' => 10,
            'prefix' => 's'
        );
    }

    public function getName()
    {
        return 'shorturl';
    }
}
