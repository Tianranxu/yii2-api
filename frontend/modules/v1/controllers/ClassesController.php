<?php
namespace frontend\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use app\models\Classes;
use common\widgets\ApiHelper;

class ClassesController extends ActiveController {
    public $modelClass = 'app\models\Classes';

    public function actionList(){
        $post = Yii::$app->request->post();
        $limit = $post['limit'] ? $post['limit'] : 5;
        $page = $post['page'] ? $post['page'] : 1;
        $classes = Classes::find()
        ->offset($limit*($page-1))
        ->limit($limit)->all();
        return ApiHelper::callback($classes);
    }
}