<?php

namespace app\models;

use Yii;
use yii\base\Model;

class Report extends Model
{
    public $startDate;
    public $endDate;

    public function rules()
    {
        return [
            [['startDate'], 'required'],
            ['endDate','safe']
        ];
    }


}
