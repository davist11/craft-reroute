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
		return '1.0.1';
	}

	public function getDeveloper()
	{
		return 'Trevor Davis';
	}

	public function getDeveloperUrl()
	{
		return 'http://trevordavis.net';
	}

	public function hasCpSection()
	{
		return true;
	}

	public function hookRegisterCpRoutes()
	{
		return array(
			'reroute\/new' => 'reroute/_form',
			'reroute\/(?P<rerouteId>\d+)' => 'reroute/_form',
		);
	}

	public function init() {
		if(craft()->request->isSiteRequest()) {
			$url = craft()->request->getUrl();
			$reroute = craft()->reroute->getByUrl($url);

			if ($reroute) {
				craft()->request->redirect($reroute['newUrl'], true, 301);
			}
		}
	}
}