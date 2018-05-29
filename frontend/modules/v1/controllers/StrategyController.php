<?php
namespace frontend\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use app\models\Strategy;
use common\widgets\ApiHelper;

class StrategyController extends ActiveController {
    public $modelClass = 'app\models\Strategy';

    public function actionList(){
        $request = Yii::$app->request;
        $limit = $request->post('limit') ? $request->post('limit') : 5;
        $page = $request->post('page') ? $request->post('page') : 1;
        $strategy = Strategy::find()
        ->offset($limit*($page-1))
        ->limit($limit)
        ->orderBy(['create_at' => SORT_DESC])->all();
        return ApiHelper::callback($strategy);
    }

    public function actionOne(){
        $strategyId = Yii::$app->request->post('strategy_id');
        $strategy = Strategy::findOne($strategy);
        return ApiHelper::callback(['doubt' => $doubt, 'interact' => $interact]);
    }

    public function actionUserstrategy(){
        return ;
    }

    public function actionSubscribe(){
        return ;
    }
}