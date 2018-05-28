<?php
namespace frontend\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use app\models\Users;
use common\widgets\HttpSender;
use common\widgets\ApiHelper;

class LoginController extends ActiveController {
    public $modelClass = 'app\models\Users';
    protected $appid = 'wx5685e2ad262dc005';
    protected $appsecret = 'a95d09b34d9df6a7fc31a926e88874d4';

    public function actionLogin() {
        $code = Yii::$app->request->post('code');
        if (empty($code)) {
            return ApiHelper::callback('', 101, 'empty code');
        }

        $httpResult = $this->getUserInfoByCode($code);
        if (isset($httpResult['errcode'])) {
            return ApiHelper::callback('', $httpResult['errcode'], $httpResult['errmsg']);
        }

        $userInfo = $this->getUserInfoFromDB($httpResult);
        $this->setUserLoginInfo($userInfo, $httpResult['session_key']);
        return ApiHelper::callback($userInfo);
    }

    public function actionPushuser(){
        $request = Yii::$app->request;
        $redis = Yii::$app->redis;
        $postData = $request->post();
        $sessionKey = explode('`', $redis->hget('loginUser', $postData['token']))[2];
        if ($postData['signature'] != sha1($postData['rawData'].$sessionKey)) {
            return ApiHelper::callback('', 102, 'signature failed');
        }

        $decryptData = $this->decryptData($postData, $sessionKey);
        if (empty($decryptData) 
        || $decryptData['watermark']['appid'] != $this->appid) {
            return ApiHelper::callback('', 103, 'data error');
        }

        $user = Users::findOne($postData['uid']);
        if (isset($decryptData['unionId'])) {
            $user->unionid = $decryptData['unionId'];    
        }
        $user->nickname = $decryptData['nickName'];
        $user->gender = $decryptData['gender'];
        $user->city = $decryptData['city'];
        $user->province = $decryptData['province'];
        $user->country = $decryptData['country'];
        $user->avatarUrl = $decryptData['avatarUrl'];
        if (!$user->save()) {
            return ApiHelper::callback('', 104, 'save error');
        };
        return ApiHelper::callback();
    }

    public function decryptData($postData, $sessionKey){
        $aesKey = base64_decode($sessionKey);
        $aesIV = base64_decode($postData['iv']);
        $aesCipher = base64_decode($postData['encryptedData']);
        $result = openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
        return json_decode($result, true);
    }

    public function getUserInfoFromDB($wxUserInfo){
        $user = Users::find()->where(['openid' => $wxUserInfo['openid']])->one();
        if (empty($user)) {
            $user = new Users();
            $user->openid = $wxUserInfo['openid'];
            $user->session_key = $wxUserInfo['openid'];
        }else{
            $user->session_key = $wxUserInfo['session_key'];
            $user->openid = $wxUserInfo['openid'];
        }
        $user->save();
        return [
            'uid' => $user->uid,
            'nickname' => empty($user->nickname) ? '' : $user->nickname,
            'avatar' => empty($user->avatarUrl) ? '' : $user->avatarUrl,
            'token' => md5($wxUserInfo['openid'].$wxUserInfo['session_key']),
        ];
    }

    public function setUserLoginInfo($userInfo, $sessionKey){
        $redis = Yii::$app->redis;
        $redis->hset('loginUser', $userInfo['token'], $userInfo['uid'].'`'.$userInfo['openid'].'`'.$sessionKey);
        $redis->zadd('recentUser', time(), $userInfo['token']);
        return ;
    }

    public function getUserInfoByCode($code){
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$this->appid.'&secret='.$this->appsecret.'&js_code='.$code.'&grant_type=authorization_code';
        return HttpSender::http_get($url);
    }
}