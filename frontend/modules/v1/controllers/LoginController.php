<?php
namespace frontend\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use app\models\Users;
use common\widgets\HttpSender;
use common\widgets\ApiHelper;

class LoginController extends ActiveController {
    protected $appid = 'wx5685e2ad262dc005';
    protected $appsecret = 'a95d09b34d9df6a7fc31a926e88874d4';

    public function actionIndex() {
        $code = Yii::$app->request->get('code');
        if (empty($code)) {
            return ApiHelper::callback('', 101, 'empty code');
        }

        $http_result = $this->getUserInfoByCode($code);
        if (isset($http_result['errcode'])) {
            return ApiHelper::callback('', $http_result['errcode'], $http_result['errmsg']);
        }

        $userInfo = $this->getUserInfoFromDB($http_result);
        $this->setUserLoginInfo($userInfo);
        return ApiHelper::callback($userInfo);
    }

    public function getUserInfoFromDB($wxUserInfo){
        $user = Users::find()->where(['openid' => $wxUserInfo['openid']])->one();
        $user->session_key = $wxUserInfo['session_key'];
        $user->openid = $wxUserInfo['openid'];
        $user->save();
        return [
            'uid' => $user->uid,
            'openid' => $wxUserInfo['openid'],
            'token' => md5($wxUserInfo['openid'].$wxUserInfo['session_key']),
            'nickname' => isset($user->nickname) ? $user->nickname : '',
            'avatar' => isset($user->avatarUrl) ? $user->avatarUrl : '',
        ];
    }

    public function setUserLoginInfo($userInfo){
        $redis = Yii::$app->redis;
        $redis->hset('loginUser', $userInfo['token'], $http_result['openid'].':'.$http_result['session_key']);
        $redis->zadd('recentUser', time(), $userInfo['token']);
        return ;
    }

    public function getUserInfoByCode($code){
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$this->appid.'&secret='.$this->appsecret.'&js_code='.$code.'&grant_type=authorization_code';
        return HttpSender::http_get($url);
    }
}