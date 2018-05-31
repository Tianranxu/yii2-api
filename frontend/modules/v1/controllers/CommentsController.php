<?php
namespace frontend\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use app\models\Comments;
use common\widgets\ApiHelper;

class CommentsController extends ActiveController {
    public $modelClass = 'app\models\Comments';

    public function actionList(){
        $post = Yii::$app->request->post();
        $comments = Comments::find()
        ->with('users')
        ->where(['status' => Comments::STATUS_ACTIVE, 'comment_id' => $post['comment_id']])
        ->offset($post['limit']*($post['page']-1))
        ->limit($post['limit'])
        ->orderBy(['create_at' => SORT_DESC])->all();
        var_dump($comments);
    }

    public function actionLike(){
        $commentId = Yii::$app->request->post('comment_id');
        $comment = Comments::findOne($commentId);
        $result = $comment->updateCounters(['like_count' => 1]);
        $redis = Yii::$app->redis;
        $redis->sadd('commentLike:'.$commentId, Yii::$app->request->post('uid'));
        return ApiHelper::callback($result);
    }

    public function actionUnlike(){
        $commentId = Yii::$app->request->post('comment_id');
        $comment = Comments::findOne($commentId);
        $result = $comment->updateCounters(['like_count' => -1]);
        $redis = Yii::$app->redis;
        $redis->srem('commentLike:'.$commentId, Yii::$app->request->post('uid'));
        return ApiHelper::callback($result);
    }
}