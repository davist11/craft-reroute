<?php

namespace Craft;

class RerouteRecord extends BaseRecord
{
	public function getTableName()
	{
		return 'reroute';
	}

	protected function defineAttributes()
	{
		return array(
			'oldUrl' => array(AttributeType::String, 'required' => true),
			'newUrl' => array(AttributeType::String, 'required' => true)
		);
	}
}