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
        ->where(['status' => Customer::STATUS_ACTIVE])
        ->orderBy(['sort' => SORT_ASC])->all();
        return ApiHelper::callback();
    }

    public function actionAnswer(){
        return ApiHelper::callback();
    }
}