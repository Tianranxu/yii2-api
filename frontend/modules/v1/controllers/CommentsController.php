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
        $limit = $post['limit'] ? $post['limit'] : 5;
        $page = $post['page'] ? $post['page'] : 1;
        $comments = Comments::find()
        ->with('users')
        ->where(['status' => Comments::STATUS_ACTIVE, 'course_id' => $post['course_id']])
        ->offset($limit*($page-1))
        ->limit($limit)
        ->orderBy(['create_at' => SORT_DESC])->all();
        $return = [];
        foreach ($comments as $key => $comment) {
            $return[] = [
                'comments' => $comment,
                'user' => [
                    'nickname' => $comment->users->nickname,
                    'avatar' => $comment->users->avatarUrl
                ],
                'isUserLike' => $this->checkUserLike($comment->comment_id, $post['uid'])
            ];
        }
        return ApiHelper::callback($return);
    }

    protected function checkUserLike($commentId, $uid){
        $redis = Yii::$app->redis;
        return $redis->sismember('commentLike:'.$commentId, $uid) ? true : false;
    }

    public function actionUsercomment(){
        $post = Yii::$app->request->post();
        $comment = new Comments();
        $comment->uid = $post['uid'];
        $comment->course_id = $post['course_id'];
        $comment->content = $post['content'];
        $comment->create_at = time();
        if (!$comment->save()) {
            return ApiHelper::callback('', 106, 'db error');
        }
        return ApiHelper::callback();
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