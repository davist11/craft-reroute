<?php

namespace Craft;

class RerouteController extends BaseController
{
	protected $valid_extensions = array('txt', 'csv');

	/**
	 * Constructor
	 */
	public function __construct()
	{
    	$this->requirePostRequest();
	}

	/**
	 * This action is called on import
	 * @return string json formatted
	 */
	public function actionUpload()
	{
        $messages = array();
        $files = array();

        foreach ($_FILES['files']['error'] as $key => $error)
        {
            if (!$error)
            {
                $filename = $_FILES['files']['name'][$key];
                $file = $_FILES['files']['tmp_name'][$key];
                $parts = explode('.', $filename);
                $extension = end($parts);

                if (!in_array($extension, $this->valid_extensions))
                {
                    $messages[] = "Error: " . $filename . " has an invalid extension.";
                    continue;
                }
                
                $uploadDir = dirname(__DIR__) . '/imports/';

                if (move_uploaded_file($file, $uploadDir . $filename))
                {
					IOHelper::deleteFile($file);
					$file = $uploadDir . $filename;
					$files[] = $file;
                }
                else
                {
                    $messages[] = "Error: " . $filename . " was unable to be saved.";
                    continue;
                }
            }
		}

		// send files to task
		if ( empty($messages) && $files )
		{
			$task = craft()->tasks->createTask('Reroute', 'Importing reroutes', array(
				'files' => $files
			));

			craft()->userSession->setNotice(Craft::t('Importing reroutes, check log for details'));
		}
		else
		{
			ReroutePlugin::log( implode(' ', $messages) );
			craft()->userSession->setError(Craft::t('Error importing reroutes, check reroute log'));
		}

		return $this->redirectToPostedUrl();
		craft()->end();
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
		$model->method = $data['method'];

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