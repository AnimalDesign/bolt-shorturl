<?php

namespace Bolt\Extension\Animal\Shorturl\Controller;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use Silex\ControllerProviderInterface;

class AsyncController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $router = $app['controllers_factory'];
        $router->match('/check', [$this, 'checkShorturlAction'])
            ->method('GET');

        return $router;
    }

    public function checkShorturlAction(Application $app, Request $request)
    {
        $enabledExtentions = $app['extensions']->getEnabled();
        $config = $enabledExtentions['shorturl']->config;
        $shorturl = $request->query->get('shorturl');
        $recordId = $request->query->get('recordId');

        $response = new \stdClass();
        $response->status = 'ok';
        $url = $app['paths']['hosturl'].$request->getBaseUrl().'/'.($config['prefix'] ? $config['prefix'].'/' : '').$shorturl;
        $response->msg = 'This record will be accessible via <a href="'.$url.'" target="_blank">'.$url.'</a>.';

        // Check length & chars
        if (!preg_match('/[a-zA-Z0-9\-_.]{2,'.$config['maxlength'].'}$/', $shorturl)) {
            $response->status = 'error';
            $response->msg = 'Shorturl must at least have two characters and can only contain a-z, A-Z, 0-9, ".", "-" and "_".';
        }

        // check if unique
        $contentTypes = $app['config']->get('contenttypes');
        foreach ($contentTypes as $name => $contentType) {
            foreach ($contentType['fields'] as $key => $field) {
                if ($field['type'] === 'shorturl') {
                    $contentTypeContent = $app['storage']->getContent($name, array());
                    foreach ($contentTypeContent as $content) {
                        if ($content['id'] !== $recordId && !empty($content[$key]) && $content[$key] == $shorturl) {
                            $response->status = 'error';
                            $response->msg = 'Shorturl already exists.';

                            break;
                        }
                    }
                }
            }
        }

        return $app->json($response);
    }
}
