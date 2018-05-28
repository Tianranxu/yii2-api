<?php
namespace frontend\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use app\models\Questions;
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
        $time = time();
        if (empty($answers)) {
            return ApiHelper::callback('', 103, 'data error');
        }

        $field = ['uid', 'question_id', 'answer', 'create_at'];
        $userAnswers = $this->setUserAnswer($answers);
        $insertCount = Yii::$app->db->createCommand()
                ->batchInsert('tbl_user_answer', $field, $userAnswers)
                ->execute();
        if (!$insertCount) {
            return ApiHelper::callback('', 106, 'insert error');    
        }
        return ApiHelper::callback();
    }

    protected function setUserAnswer($answers){
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