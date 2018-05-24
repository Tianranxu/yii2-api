<?php
namespace frontend\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use app\models\Doubts;

class DoubtsController extends ActiveController {
    public $modelClass = 'app\models\Doubts';

    public function actionList(){
        $request = Yii::$app->request;
        $doubts = Doubts::find()
        ->offset($request->post('limit')*($request->post('page')-1))
        ->limit($request->post('limit'))
        ->orderBy(['create_at' => SORT_DESC])->all();
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