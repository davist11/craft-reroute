<?php

namespace Craft;

class ReroutePlugin extends BasePlugin
{
	public function getName()
	{
		return Craft::t('Reroute');
	}

	public function getVersion()
	{
		return '1.0.5';
	}

	public function getDeveloper()
	{
		return 'Trevor Davis';
	}

	public function getDeveloperUrl()
	{
		return 'http://trevordavis.net';
	}

	public function getDocumentationUrl()
	{
		return 'https://github.com/davist11/craft-reroute';
	}

	public function getReleaseFeedUrl()
	{
		return 'https://raw.githubusercontent.com/davist11/craft-reroute/master/releases.json';
	}

	public function hasCpSection()
	{
		return true;
	}

	public function registerCpRoutes()
	{
		return array(
			'reroute\/new' => 'reroute/_form',
			'reroute\/(?P<rerouteId>\d+)' => 'reroute/_form',
		);
	}

	public function init() {
		// import plugins resources
	    if ( craft()->request->isCpRequest() && craft()->userSession->isLoggedIn() )
    	{
    		craft()->templates->includeJsResource('reroute/js/reroute.js');
    		craft()->templates->includeCssResource('reroute/css/reroute.css');
    	}

		if(!craft()->isConsole() && craft()->request->isSiteRequest()) {
			$url = craft()->request->getUrl();
			$reroute = craft()->reroute->getByUrl($url);

			if ($reroute) {
				craft()->request->redirect($reroute['newUrl'], true, $reroute['method']);
			} else {
				$urlParts = parse_url($url);
				$rerouteWithoutQueryString = craft()->reroute->getByUrl($urlParts['path']);

				if ($rerouteWithoutQueryString) {
					$glue = (strpos($rerouteWithoutQueryString['newUrl'], '?') === FALSE) ? '?' : '&';

					$redirectUrl = isset($urlParts['query']) ? $rerouteWithoutQueryString['newUrl'] . $glue . $urlParts['query'] : $rerouteWithoutQueryString['newUrl'];

					craft()->request->redirect($redirectUrl, true, $rerouteWithoutQueryString['method']);
				}
			}
		}
	}
}