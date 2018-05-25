<?php
namespace frontend\components;

use Yii;
use yii\base\ActionFilter;

class WxapiVeriyFilter extends ActionFilter {

    public function beforeAction($action) {
        $redis = Yii::$app->redis;
        $request = Yii::$app->request;
        $token = $request->post('token');
        if (empty($token)) {
            return false;
        }
        $loginUser = $redis->hget('loginUser', $token);
        if (empty($loginUser)) {
            return false;
        }
        $user = explode('`', $loginUser);
        if ($user[0] != $request->post('uid')) {
            return false;
        }
        return parent::beforeAction($action);
    }
}