<?php
/**
 * Reroute plugin for Craft CMS 3.x
 *
 * Manage 301/302 redirects in the control panel.
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
