<?php
/**
 * Reroute plugin for Craft CMS 3.x
 *
 * https://twitter.com/skoczela/status/1060025421624958976
 *
 * @link      https://www.trevor-davis.com
 * @copyright Copyright (c) 2018 Trevor Davis
 */

namespace davist11\reroute\migrations;

use davist11\reroute\Reroute;

use Craft;
use craft\config\DbConfig;
use craft\db\Migration;

/**
 * @author    Trevor Davis
 * @package   Reroute
 * @since     2.0.0
 */
class Install extends Migration
{
    // Public Properties
    // =========================================================================

    /**
     * @var string The database driver to use
     */
    public $driver;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        if ($this->createTables()) {
            // Refresh the db schema caches
            Craft::$app->db->schema->refresh();
        }

        return true;
    }

   /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        $this->removeTables();

        return true;
    }

    // Protected Methods
    // =========================================================================

    /**
     * @return bool
     */
    protected function createTables()
    {
        $tablesCreated = false;

        $tableSchema = Craft::$app->db->schema->getTableSchema('{{%reroute}}');
        if ($tableSchema === null) {
            $tablesCreated = true;
            $this->createTable(
                '{{%reroute}}',
                [
                    'id' => $this->primaryKey(),
                    'oldUrl' => $this->string(255)->notNull(),
                    'newUrl' => $this->string(255)->notNull(),
                    'method' => $this->integer()->notNull(),
                    'dateCreated' => $this->dateTime()->notNull(),
                    'dateUpdated' => $this->dateTime()->notNull(),
                    'uid' => $this->uid(),
                ]
            );
        }

        return $tablesCreated;
    }

    /**
     * @return void
     */
    protected function removeTables()
    {
        $this->dropTableIfExists('{{%reroute}}');
    }
}
