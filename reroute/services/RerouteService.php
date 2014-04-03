<?php

namespace Craft;

class RerouteService extends BaseApplicationComponent
{
	protected $record;

	/**
	 * Constructor
	 * @param RerouteRecord $record
	 */
	public function __construct($record = null)
    {
        $this->record = $record;
        if (is_null($this->record)) {
            $this->record = RerouteRecord::model();
        }
    }


    /**
     * Get all the Reroutes
     * @return array
     */
	public function getAll()
	{
		$reroutes = craft()->db->createCommand()
					->select('id, oldUrl, newUrl, method')
					->from('reroute')
					->queryAll();

		return $reroutes;
	}


	/**
	 * Get a Reroute by ID
	 * @param  int $rerouteId
	 * @return RerouteModel
	 */
	public function getById($rerouteId)
	{
		if ($record = $this->record->findByPk($rerouteId)) {
            return RerouteModel::populateModel($record);
        }
	}


	/**
	 * Get a Reroute by a URL
	 * @param  string $url
	 * @return array
	 */
	public function getByUrl($url) {
		$reroute = craft()->db->createCommand()
					->select('id, oldUrl, newUrl, method')
					->from('reroute')
					->where('oldUrl = :url', array(':url' => $url))
					->limit(1)
					->queryRow();

		return $reroute;
	}


	/**
	 * Create a new Reroute object and set the attributes
	 * @param  array  $attributes
	 * @return RerouteModel
	 */
	public function create($attributes = array())
	{
		$model = new RerouteModel();
		$model->setAttributes($attributes);

		return $model;
	}


	/**
	 * Add new Reroute or update existing
	 * @param  RerouteModel $model
	 * @return boolean
	 */
	public function save(RerouteModel &$model)
	{
		if ($id = $model->getAttribute('id')) {
			if (null === ($record = $this->record->findByPk($id))) {
				throw new Exception(Craft::t('Can\'t find reroute with ID "{id}"', array('id' => $id)));
			}
		} else {
			$record = new RerouteRecord();
		}

		$record->setAttributes($model->getAttributes());

		if ($record->save()) {
			// update id on model (for new records)
			$model->setAttribute('id', $record->getAttribute('id'));

			return true;
		} else {
			$model->addErrors($record->getErrors());

			return false;
		}
	}


	/**
	 * Delete Reroute record by id
	 * @param  int $id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		return $this->record->deleteByPk($id);
	}
}