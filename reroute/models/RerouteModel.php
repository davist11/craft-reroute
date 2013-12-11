<?php

namespace Craft;

class RerouteModel extends BaseModel
{
	protected function defineAttributes()
	{
		return array(
			'id' => AttributeType::Number,
			'oldUrl' => array(AttributeType::String, 'required' => true),
			'newUrl' => array(AttributeType::String, 'required' => true),
			'type' => array(AttributeType::Number, 'required' => true, 'values' => array(301,302))
		);
	}
}
