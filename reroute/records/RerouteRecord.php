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
			'newUrl' => array(AttributeType::String, 'required' => true),
			'type' => array(AttributeType::Number, 'required' => true, 'values' => array(301,302))
		);
	}

	public function defineIndexes()
	{
		return array(
			array('columns' => array('oldUrl'), 'unique' => true),
		);
	}
}
