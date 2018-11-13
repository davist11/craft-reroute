<?php
/**
 * Reroute plugin for Craft CMS 3.x
 *
 * Manage 301/302 redirects in the control panel.
 *
 * @link      https://www.trevor-davis.com
 * @copyright Copyright (c) 2018 Trevor Davis
 */

namespace davist11\reroute\records;

use davist11\reroute\Reroute;

use Craft;
use craft\db\ActiveRecord;

/**
 * @author    Trevor Davis
 * @package   Reroute
 * @since     2.0.0
 */
class RerouteRecord extends ActiveRecord
{
    // Public Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['oldUrl', 'newUrl', 'method'], 'required'],
            [['oldUrl'], 'string', 'max' => 255],
            [['newUrl'], 'string', 'max' => 255],
            [['method'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%reroute}}';
    }
}
