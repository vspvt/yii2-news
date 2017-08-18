<?php

namespace app\controllers;

use Yii;
use yii\web\Controller as BaseController;

/**
 * Class AbstractController
 * @package app\controllers
 */
abstract class AbstractController extends BaseController
{
    protected $defaultLimit = 5;

    /**
     * @return int
     */
    public function getLimit()
    {
        return (int)Yii::$app->request->get('per-page', $this->defaultLimit);
    }

    /**
     * @param int $limit
     * @return int
     */
    public function getOffest($limit = 0)
    {
        $limit > 0 or $limit = $this->defaultLimit;

        return (int)(Yii::$app->request->get('page', 1) - 1) * $limit;
    }

}
