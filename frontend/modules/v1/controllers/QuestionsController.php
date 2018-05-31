<?php
namespace frontend\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use app\models\Questions;
use app\models\Users;
use common\widgets\ApiHelper;
use common\widgets\HttpSender;

class QuestionsController extends ActiveController {
    public $modelClass = 'app\models\Questions';

    public function actionList(){
        $questions = Questions::find()
        ->where(['status' => Questions::STATUS_ACTIVE])
        ->orderBy(['sort' => SORT_ASC])->all();
        foreach ($questions as $question) {
            $questionList[] = [
                'question_id' => $question->question_id,
                'question' => $question->content,
                'options' => $this->setOptionData($question->option)
            ];
        }
        return ApiHelper::callback($questionList);
    }

    protected function setOptionData($options){
        $optionArr = json_decode($options, true);
        foreach ($optionArr as $option) {
            foreach ($option as $value => $text) {
                $optionList[] = [
                    'value' => $value,
                    'text' => $text,
                    'checked' => false
                ];
            }
        }
        return $optionList;
    }

    public function actionAnswer(){
        $answers = Yii::$app->request->post('answer');
        $uid = Yii::$app->request->post('uid');
        if (empty($answers)) {
            return ApiHelper::callback('', 103, 'data error');
        }

        $userAnswers = $this->setUserAnswer($answers, $uid);
        $isQuestionDone = Yii::$app->request->post('is_question_done');
        $result = $isQuestionDone
                ? $this->updateUserAnswer($userAnswers['options']) 
                : $this->insertUserAnswer($userAnswers['options']);
        if (!$result) {
            return ApiHelper::callback('', 106, 'db error');    
        }

        if (!$isQuestionDone) {
            //only in first time
            $this->setUserStatus($uid);
        }
        $this->updateUserToQiyu($userAnswers['contents'], $uid);
        return ApiHelper::callback();
    }

    protected function insertUserAnswer($userAnswers) {
        $field = ['uid', 'question_id', 'answer', 'create_at'];
        return Yii::$app->db->createCommand()
                ->batchInsert('tbl_user_answer', $field, $userAnswers)
                ->execute();
    }

    protected function updateUserAnswer($userAnswers) {
        foreach ($userAnswers as $answer) {
            Yii::$app->db->createCommand()
                ->update('tbl_user_answer', 
                    ['answer' => $answer['answer']], 
                    [
                        'uid' => $answer['uid'], 
                        'question_id' => $answer['question_id']
                    ])
                ->execute();
        }
        return true;
    }

    protected function updateUserToQiyu($contents, $uid){
        $msgContent = $this->setMsgContent($contents, $uid);
        $url = $this->getQiyuUrl($msgContent);
        $result = HttpSender::http_post($url, $msgContent);
        return ;
    }

    protected function getQiyuUrl($msgContent){
        $time = time();
        $appid = 'e8df10a44977d49e025a3542361e5a06';
        $appsecret = '985D2A3AC62942AA894CD9B8FDE1FEEF';
        $frontUrl = 'https://qiyukf.com/openapi/event/updateUInfo';
        return $frontUrl.'?appKey='.$appid.'&time='.$time.'&checksum='.sha1($appsecret . strtolower(md5(json_encode($msgContent))) . $time);
    }

    protected function setMsgContent($contents, $uid){
        $msgContent['uid'] = $uid;
        $i = 0;
        foreach ($contents as $content) {
            $msgContent['userinfo'][$i] = ['index' => $i, 'key' => 'question_'.($i+1), 'label' => '问题'.($i+1),'value' => $content];
            $i++;
        }
        $msgContent['userinfo'][$i] = ['index' => $i, 'key' => 'user_code', 'label' => '用户码','value' => $uid];
        return $msgContent;
    }

    protected function setUserStatus($uid){
        $user = Users::findOne($uid);
        $user->is_question_done = Users::QUESTION_DONE;
        return $user->save();
    }

    protected function setUserAnswer($answers, $uid){
        $time = time();
        foreach ($answers as $answer) {
            foreach ($answer as $qustionId => $select) {
                if ($qustionId == 'text') {
                    $userAnswers['contents'][] = $select;    
                    continue;
                }
                $userAnswers['options'][] = [
                    'uid' => $uid,
                    'question_id' => $qustionId,
                    'answer' => $select,
                    'create_at' => $time
                ];
            }
        }
        return $userAnswers;
    }
}