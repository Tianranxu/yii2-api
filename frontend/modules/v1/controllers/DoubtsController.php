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
        $doubtId = Yii::$app->request->post('doubt_id');
        $uid = Yii::$app->request->post('uid');
        $doubt = ApiHelper::callback(Doubts::findOne($doubtId));
        $doubt['isLike'] = $redis->sismember('doubtLike:'.$doubtId, $uid);
        $doubt['isEncourage'] = $redis->sismember('doubtEncourage:'.$doubtId, $uid);
        return $doubt;
    }

    public function actionLike(){
        $doubtId = Yii::$app->request->post('doubt_id');
        $doubt = Doubts::findOne($doubtId);
        $result = $doubt->updateCounters(['like_count' => 1]);
        $redis = Yii::$app->redis;
        $redis->sadd('doubtLike:'.$doubtId, Yii::$app->request->post('uid'));
        return ApiHelper::callback($result);
    }

    public function actionUnlike(){
        $doubtId = Yii::$app->request->post('doubt_id');
        $doubt = Doubts::findOne($doubtId);
        $result = $doubt->updateCounters(['like_count' => -1]);
        $redis = Yii::$app->redis;
        $redis->srem('doubtLike:'.$doubtId, Yii::$app->request->post('uid'));
        return ApiHelper::callback($result);
    }

    public function actionEncourage(){
        $doubtId = Yii::$app->request->post('doubt_id');
        $doubt = Doubts::findOne($doubtId);
        $result = $doubt->updateCounters(['encourge_count' => 1]);
        $redis = Yii::$app->redis;
        $redis->sadd('doubtEncourage:'.$doubtId, Yii::$app->request->post('uid'));
        return ApiHelper::callback($result);
    }

    public function actionDiscourage(){
        $doubtId = Yii::$app->request->post('doubt_id');
        $doubt = Doubts::findOne($doubtId);
        $result = $doubt->updateCounters(['encourge_count' => -1]);
        $redis = Yii::$app->redis;
        $redis->srem('doubtEncourage:'.$doubtId, Yii::$app->request->post('uid'));
        return ApiHelper::callback($result);
    }    
}