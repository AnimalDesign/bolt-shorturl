<?php

namespace Bolt\Extension\Animal\Shorturl\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
		$response = new \stdClass;
		$response->status = 'ok';
		$url = $app['paths']['hosturl'].$request->getBaseUrl().'/'.($config['prefix'] ? $config['prefix'].'/' : '').$shorturl;
		$response->msg = 'This record will be accessible via <a href="'.$url.'" target="_blank">'.$url.'</a>.';

		$enabledExtentions = $app['extensions']->getEnabled();
		$config = $enabledExtentions['shorturl']->config;
		$shorturl = $request->query->get('shorturl');

		// Check length & chars
		if (!preg_match('/[a-zA-Z0-9\-_.]{2,'.$config['maxlength'].'}$/', $shorturl)) {
			$response->status = 'error';
			$response->msg = 'Shorturl must at least have two characters and can only contain a-z, A-Z, 0-9, ".", "-" and "_".';
        }

		// check if unique
		//if(!$unique) {
		//	$response->status = 'error';
		//	$response->msg = 'Shorturl already exists.';
		//}

        return $app->json($response);
    }
}
