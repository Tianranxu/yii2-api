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
                'questoin' => $question->content,
                'options' => $this->setOptionData($question->option)
            ];
        }
        return ApiHelper::callback();
    }

    protected function setOptionData($options){
        
    }

    public function actionAnswer(){
        return ApiHelper::callback();
    }
}