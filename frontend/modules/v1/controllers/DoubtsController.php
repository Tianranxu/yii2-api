<?php
namespace frontend\modules\v1\controllers;

use yii\rest\ActiveController;

class DoubtsController extends ActiveController {
    public $modelClass = 'app\models\Doubts';

    public function actionList(){
        $request = Yii::$app->request;
        $doubts = Doubts::find()
        ->offset($request->post('limit')*($request->post('page')-1))
        ->limit($request->post('limit'))
        ->orderBy(['create_time' => SORT_DESC])->all();
        return $doubts;
    }

    public function actionOne(){
        return Doubts::findOne(Yii::$app->request->post('doubt_id'));
    }

    public function actionLike(){
        
    }

    public function actionEncourage(){

    }
}