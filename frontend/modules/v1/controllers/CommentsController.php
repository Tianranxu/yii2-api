<?php
namespace frontend\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use app\models\Comments;
use common\widgets\ApiHelper;

class CommentsController extends ActiveController {
    public $modelClass = 'app\models\Comments';

    public function actionList(){
        
    }

    public function actionLike(){

    }

    public function actionUnlike(){

    }
}