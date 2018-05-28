<?php
namespace frontend\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use app\models\Questions;
use app\models\Users;
use common\widgets\ApiHelper;

class QuestionsController extends ActiveController {
    public $modelClass = 'app\models\Questions';

    public function actionList(){
        $questions = Questions::find()
        ->where(['status' => Questions::STATUS_ACTIVE])
        ->orderBy(['sort' => SORT_ASC])->all();
        foreach ($questions as $question) {
            $questionList[] = [
                'question_id' => $questions->question_id,
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
        $redo = Yii::$app->request->post('redo');
        $result = $redo
                ? $this->updateUserAnswer($userAnswers) 
                : $this->insertUserAnswer($userAnswers);
        if (!$result) {
            return ApiHelper::callback('', 106, 'db error');    
        }

        if (!$redo) {
            //only in first time
            $this->setUserStatus($uid);   
        }
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

    protected function setUserStatus($uid){
        $user = Users::findOne($uid);
        $user->is_question_done = 1;
        return $user->save();
    }

    protected function setUserAnswer($answers, $uid){
        $time = time();
        foreach ($answers as $answer) {
            foreach ($answer as $qustionId => $select) {
                $userAnswers[] = [
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