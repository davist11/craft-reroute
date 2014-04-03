<?php

namespace Craft;

class RerouteVariable
{
	public function getAll()
	{
		return craft()->reroute->getAll();
	}

	public function getById($rerouteId) {
		return craft()->reroute->getById($rerouteId);
	}
}