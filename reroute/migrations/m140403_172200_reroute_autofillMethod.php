<?php
namespace Craft;

class m140403_172200_reroute_autofillMethod extends BaseMigration
{
	public function safeUp()
	{
		$table = 'reroute';
		$data = array('method' => 301);
		$this->update($table, $data);
		return true;
	}
}