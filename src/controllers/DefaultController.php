<?php
/**
 * Reroute plugin for Craft CMS 3.x
 *
 * Manage 301/302 redirects in the control panel.
 *
 * @link      https://www.trevor-davis.com
 * @copyright Copyright (c) 2018 Trevor Davis
 */

namespace davist11\reroute\controllers;

use davist11\reroute\Reroute;

use Craft;
use craft\web\Controller;
use davist11\reroute\models\RerouteModel;

/**
 * @author    Trevor Davis
 * @package   Reroute
 * @since     2.0.0
 */
class DefaultController extends Controller
{
    // Public Methods
    // =========================================================================

    /**
     * @return mixed
     */
    public function actionSave()
    {
        $this->requirePostRequest();

        // Is this an existing entry or a new entry?
        if ($id = Craft::$app->request->post('rerouteId')) {
            $model = Reroute::getInstance()->rerouteService->getById($id);
        } else {
            $model = new RerouteModel();
        }

        // Get the submitted data
        $data = Craft::$app->request->post('reroute');
        $model->oldUrl = $data['oldUrl'];
        $model->newUrl = $data['newUrl'];
        $model->method = $data['method'];

        $success = false;

        if($model->validate()) {
            $success = Reroute::getInstance()->rerouteService->save($model);
        }

        if ($success) {
            Craft::$app->getSession()->setNotice('Reroute saved.');

            return $this->redirectToPostedUrl();
        } else {
             Craft::$app->getSession()->setError('There was a problem with your submission, please check the form and try again!');

            Craft::$app->urlManager->setRouteParams([
                'reroute' => $model,
            ]);
        }
    }

    /**
     * This action is called when a Reroute is deleted
     * @return string json formatted
     */
    public function actionDelete()
    {
        $this->requireAcceptsJson();

        $id = Craft::$app->request->getRequiredParam('id');
        $result = Reroute::getInstance()->rerouteService->deleteById($id);

        return $this->asJson(['success' => $result]);
    }
}
