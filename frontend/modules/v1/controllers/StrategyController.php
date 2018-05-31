<?php
namespace frontend\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use app\models\Strategy;
use app\models\Users;
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
        $return = [];
        foreach ($strategys as $key => $strategy) {
            $strategy->content = htmlspecialchars_decode($strategy->content);
            $return[$key]['strategy'] = $strategy;
            if ($strategy->uid) {
                $return[$key]['user'] = [
                    'avatar' => $strategy->users->avatarUrl,
                    'nickname' => $strategy->users->nickname
                ];
            }
        }
        return ApiHelper::callback($return);
    }

    public function actionOne(){
        $strategy = Strategy::findOne(Yii::$app->request->post('strategy_id'));
        $strategy->updateCounters(['view_count' => 1]);//add view count
        $strategy->content = htmlspecialchars_decode($strategy->content);
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
        $postData = Yii::$app->request->post();
        if (empty($postData['title']) 
        || empty($postData['content'])) {
            return ApiHelper::callback('', 100, 'empty pramater');
        }
        $strategy = new Strategy();
        $strategy->title = $postData['title'];
        $strategy->content = $postData['content'];
        $strategy->uid = $postData['uid'];
        $strategy->share_type = Strategy::USER_SHARE;
        $strategy->create_at = time();
        if (!$strategy->save()) {
            return ApiHelper::callback('', 106, 'db error');
        }
        return ApiHelper::callback();
    }

    public function actionSubscribe(){
        $user = Users::findOne(Yii::$app->request->post('uid'));
        $user->form_id = Yii::$app->request->post('form_id');
        if (!$user->save()) {
            return ApiHelper::callback('', 106, 'db error');
        }
        return ApiHelper::callback();
    }
}