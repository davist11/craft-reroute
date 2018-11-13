<?php
/**
 * Reroute plugin for Craft CMS 3.x
 *
 * https://twitter.com/skoczela/status/1060025421624958976
 *
 * @link      https://www.trevor-davis.com
 * @copyright Copyright (c) 2018 Trevor Davis
 */

namespace davist11\reroute\models;

use davist11\reroute\Reroute;

use Craft;
use craft\base\Model;

/**
 * @author    Trevor Davis
 * @package   Reroute
 * @since     2.0.0
 */
class RerouteModel extends Model
{
    public $id;
    public $oldUrl;
    public $newUrl;
    public $method;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['oldUrl', 'newUrl', 'method'], 'required'],
            [['id', 'method'], 'integer'],
            [['oldUrl', 'newUrl'], 'string'],
        ];
    }
}
