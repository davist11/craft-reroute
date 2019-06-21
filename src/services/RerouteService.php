<?php
/**
 * Reroute plugin for Craft CMS 3.x
 *
 * Manage 301/302 redirects in the control panel.
 *
 * @link      https://www.trevor-davis.com
 * @copyright Copyright (c) 2018 Trevor Davis
 */

namespace davist11\reroute\services;

use davist11\reroute\Reroute;

use Craft;
use craft\base\Component;
use davist11\reroute\models\RerouteModel;
use davist11\reroute\records\RerouteRecord;

/**
 * @author    Trevor Davis
 * @package   Reroute
 * @since     2.0.0
 */
class RerouteService extends Component
{
    // Public Methods
    // =========================================================================

    /*
     * @return mixed
     */
    public function getAll()
    {
        $reroutes = RerouteRecord::find()
            ->select(['id', 'oldUrl', 'newUrl', 'method'])
            ->from('{{%reroute}}')
            ->all();

        return $reroutes;
    }

    /**
     * Get a Reroute by ID
     * @param  int $rerouteId
     * @return RerouteModel
     */
    public function getById($rerouteId)
    {
        $model = null;

        if ($record = $this->_getRecordById($rerouteId)) {
            $model = new RerouteModel();
            $model->setAttributes($record->getAttributes());
        }

        return $model;
    }

    /**
     * Determine whether we should redirect based on the current url
     */
    public function redirectByUrl()
    {
        $url = Craft::$app->request->url;
        $reroute = $this->getByUrl($url);

        if ($reroute) {
            $this->_redirect($reroute['newUrl'], $reroute['method']);
        } else {
            $urlParts = parse_url($url);
            $path = $urlParts['path'] ?? null;

            if (!$path) return;

            $rerouteWithoutQueryString = $this->getByUrl($path);

            if ($rerouteWithoutQueryString) {
                $glue = (strpos($rerouteWithoutQueryString['newUrl'], '?') === FALSE) ? '?' : '&';

                $redirectUrl = isset($urlParts['query']) ? $rerouteWithoutQueryString['newUrl'] . $glue . $urlParts['query'] : $rerouteWithoutQueryString['newUrl'];

                $this->_redirect($redirectUrl, $rerouteWithoutQueryString['method']);
            }
        }
    }

    /**
     * Get a Reroute by a URL
     * @param  string $url
     * @return array
     */
    public function getByUrl($url) {
        $reroute = RerouteRecord::find()
            ->select(['id', 'oldUrl', 'newUrl', 'method'])
            ->from('{{%reroute}}')
            ->where(['oldUrl' => $url])
            ->one();

        return $reroute;
    }

    /**
     * Add new Reroute or update existing
     * @param  RerouteModel $model
     * @return boolean
     */
    public function save(RerouteModel &$model)
    {
        if ($id = $model->id) {
            $record = $this->_getRecordById($id);
        } else {
            $record = new RerouteRecord();
        }

        $record->setAttributes($model->getAttributes());

        if ($record->save()) {
            // update id on model (for new records)
            $model->id = $record->id;

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
        $record = $this->_getRecordById($id);

        return $record->delete();
    }

    // Private Methods
    // =========================================================================

    /**
     * Get a Reroute Record by its ID
     * @param  integer $id
     * @return RerouteRecord
     */
    private function _getRecordById($id)
    {
        $record = RerouteRecord::find()
            ->where(['id' => $id])
            ->one();

        return $record;
    }

    /**
     * Redirect the browser
     * @param  string $url
     * @param  integer $method
     */
    private function _redirect($url, $method)
    {
        header('Location: ' . $url, true, $method);
        exit();
    }
}
