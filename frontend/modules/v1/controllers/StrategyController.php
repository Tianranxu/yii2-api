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
        $strategys = Strategy::find()
        ->offset($limit*($page-1))
        ->limit($limit)
        ->orderBy(['create_at' => SORT_DESC])->all();
        foreach ($strategys as $strategy) {
            $return['strategy'] = $strategy;
            if ($strategy->uid) {
                $return['user'] = [
                    'avatar' => $strategy->avatarUrl,
                    'nickname' => $strategy->nickname
                ];
            }
        }
        return ApiHelper::callback($return);
    }

    public function actionOne(){
        $strategy = Strategy::findOne(Yii::$app->request->post('strategy_id'));
        $return['strategy'] = $strategy;
        if ($strategy->uid) {
            $return['user'] = [
                'avatar' => $strategy->users->avatarUrl,
                'nickname' => $strategy->users->nickname,
            ];
        }
        return ApiHelper::callback($return);
    }

    public function actionUsershare(){
        return ;
    }

    public function actionSubscribe(){
        return ;
    }
}