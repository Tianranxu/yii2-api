<?php
namespace frontend\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use app\models\Doubts;
use common\widgets\ApiHelper;

class DoubtsController extends ActiveController {
    public $modelClass = 'app\models\Doubts';

    public function actionList(){
        $request = Yii::$app->request;
        $limit = $request->post('limit') ? $request->post('limit') : 5;
        $page = $request->post('page') ? $request->post('page') : 1;
        $doubts = Doubts::find()
        ->offset($limit*($page-1))
        ->limit($limit)
        ->orderBy(['create_at' => SORT_DESC])->all();
        return ApiHelper::callback($doubts);
    }

    public function actionOne(){
        return ApiHelper::callback(Doubts::findOne(Yii::$app->request->post('doubt_id')));
    }

    public function actionLike(){
        $doubt = Doubts::findOne(Yii::$app->request->post('doubt_id'));
        $result = $doubt->updateCounters(['like_count' => 1]);
        return ApiHelper::callback($result);
    }

    public function actionEncourage(){
        $doubt = Doubts::findOne(Yii::$app->request->post('doubt_id'));
        $result = $doubt->updateCounters(['encourge_count' => 1]);
        return ApiHelper::callback($result);
    }
}