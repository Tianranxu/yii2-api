<?php
namespace frontend\modules\v1\controllers;

use yii\rest\ActiveController;

class DoubtsController extends ActiveController {
    public $modelClass = 'app\models\Doubts';

    public function actionDoubts(){
        return ['msg' => 'hehe'];
    }

    /*public function actions() {
        $actions = parent::actions();
        unset($actions['delete'], $actions['create'], $actions['patch'], $actions['put']);
        return $actions;
    }*/
}