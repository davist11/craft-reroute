<?php
/**
 * Reroute plugin for Craft CMS 3.x
 *
 * Manage 301/302 redirects in the control panel.
 *
 * @link      https://www.trevor-davis.com
 * @copyright Copyright (c) 2018 Trevor Davis
 */

namespace davist11\reroute\variables;

use davist11\reroute\Reroute;

use Craft;

/**
 * @author    Trevor Davis
 * @package   Reroute
 * @since     2.0.0
 */
class RerouteVariable
{
    // Public Methods
    // =========================================================================

    public function getAll()
    {
        return Reroute::getInstance()->rerouteService->getAll();
    }

    public function getById($rerouteId)
    {
        return Reroute::getInstance()->rerouteService->getById($rerouteId);
    }
}
