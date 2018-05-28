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
                'question' => $question->content,
                'options' => $this->setOptionData($question->option)
            ];
        }
        return ApiHelper::callback();
    }

    protected function setOptionData($options){
        $optionArr = json_decode($options, true);
        foreach ($optionArr as $value => $text) {
            $optionList[] = [
                'value' => $value,
                'text' => $text,
                'checked' => false
            ];
        }
        return $optionList;
    }

    public function actionAnswer(){
        return ApiHelper::callback();
    }
}