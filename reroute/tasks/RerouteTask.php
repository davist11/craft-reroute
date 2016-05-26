<?php
namespace Craft;

/**
 * Reroute task
 */
class RerouteTask extends BaseTask
{
	private $delimiter;
	private $merged = 0;
	private $imported = 0;
	private $failed = 0;

	public function __construct()
	{
		ini_set('auto_detect_line_endings', true);
		$this->delimiter = craft()->config->get('delimiter', 'reroute');
	}

	protected function defineSettings()
	{
		return array(
			'files' => AttributeType::Mixed,
		);
	}

	/**
	 * Gets the total number of steps for this task.
	 *
	 * @return int
	 */
	public function getTotalSteps()
	{
		return count( $this->getSettings()->files );
	}

	/**
	 * Runs a task step.
	 *
	 * @param int $step
	 * @return bool
	 */
	public function runStep($step)
	{
		$file = $this->getSettings()->files[$step];
		$merged = $this->merged;
		$failed = $this->failed;
		$imported = $this->imported;

		if ( ($handle = fopen($file, "r")) !== FALSE )
		{
			while ( ($data = fgetcsv($handle, 0, $this->delimiter)) !== FALSE )
			{
				// simple beforehand validation
				if ( count($data) == 3 )
				{
					// Is this an existing entry or a new entry?	
					if ( $route = craft()->reroute->getByUrl($data[0]) )
					{
						$state = 'merged';
						$model = craft()->reroute->getById($route['id']);
					}
					else
					{
						$state = 'import';
						$model = craft()->reroute->create();
					}

					// store imported data
					$model->oldUrl = $data[0];
					$model->newUrl = $data[1];
					$model->method = $data[2];

					// Did we pass validation?
					if ( $model->validate() )
					{
						if ($state == 'merged') {
							$this->merged++;
						} else {
							$this->imported++;
						}

						craft()->reroute->save($model);
					}
					else
					{
						$this->failed++;
					}
				}
			}
			fclose($handle);
		}

		ReroutePlugin::log($file . ' imported, total imports: ' . ($this->imported - $imported) . ' total failed: ' . ($this->failed - $failed) . ' total merged: ' . ($this->merged - $merged));

		// delete temp file
		IOHelper::deleteFile($file);

		// last step
		if ( $step == $this->getTotalSteps() - 1 )
		{
			ReroutePlugin::log('Reroute imports finished, total imports: ' . $this->imported . ' total failed: ' . $this->failed . ' total merged: ' . $this->merged);
		}

		return true;
	}
}