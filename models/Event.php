<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $params
 */
class Event extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'event';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'string', 'max' => 32],
            [['params'], 'string', 'max' => 64],
            [['code', 'name', 'params'], 'required'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'code' => 'Код',
            'name' => 'Название события',
            'params' => 'Параметры',
        ];
    }
}
