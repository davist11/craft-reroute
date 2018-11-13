<?php
/**
 * Reroute plugin for Craft CMS 3.x
 *
 * https://twitter.com/skoczela/status/1060025421624958976
 *
 * @link      https://www.trevor-davis.com
 * @copyright Copyright (c) 2018 Trevor Davis
 */

namespace davist11\reroute;

use davist11\reroute\services\RerouteService;
use davist11\reroute\variables\RerouteVariable;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\UrlManager;
use craft\web\twig\variables\CraftVariable;
use craft\events\RegisterUrlRulesEvent;

use yii\base\Event;

/**
 * Class Reroute
 *
 * @author    Trevor Davis
 * @package   Reroute
 * @since     2.0.0
 *
 * @property  RerouteService $rerouteService
 */
class Reroute extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var Reroute
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '2.0.0';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        // If it's a site request, determine if we have a matching reroute
        if (Craft::$app->request->isSiteRequest) {
            $this->rerouteService->redirectByUrl();
        }

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['reroute/new'] = ['template' => 'reroute/_form'];
                $event->rules['reroute/<rerouteId:\d+>'] = ['template' => 'reroute/_form'];
            }
        );

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('reroute', RerouteVariable::class);
            }
        );

        Craft::info(
            Craft::t(
                'reroute',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

}
