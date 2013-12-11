<?php

namespace Craft;

class RerouteController extends BaseController
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->requirePostRequest();
	}


	/**
	 * This action is called when a Reroute is saved
	 */
	public function actionSave()
	{
		// Is this an existing entry or a new entry?
		if ($id = craft()->request->getPost('rerouteId')) {
			$model = craft()->reroute->getById($id);
		} else {
			$model = craft()->reroute->create();
		}

		// Get the submitted data
		$data = craft()->request->getPost('reroute');
		$model->oldUrl = $data['oldUrl'];
		$model->newUrl = $data['newUrl'];
		$model->type = $data['type'];

		// Did we pass validation?
		if($model->validate()) {
			craft()->reroute->save($model);

			craft()->userSession->setNotice(Craft::t('Reroute saved.'));

			return $this->redirectToPostedUrl();
		} else {
			craft()->userSession->setError(Craft::t('There was a problem with your submission, please check the form and try again!'));
			craft()->urlManager->setRouteVariables(array('reroute' => $model));
		}
	}


	/**
	 * This action is called when a Reroute is deleted
	 * @return string json formatted
	 */
	public function actionDelete()
	{
		$this->requireAjaxRequest();

		$id = craft()->request->getRequiredPost('id');
		$result = craft()->reroute->deleteById($id);

		$this->returnJson(array('success' => $result));
	}
}
