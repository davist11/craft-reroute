<?php
namespace Craft;

class m140403_171200_reroute_addMethodColumn extends BaseMigration
{
	public function safeUp()
	{
		$table = 'reroute';
		$this->addColumnAfter($table, 'method', ColumnType::Int, 'newUrl');
		return true;
	}
}