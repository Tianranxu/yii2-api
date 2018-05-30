<?php
namespace frontend\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use app\models\OpenCourses;
use common\widgets\ApiHelper;
use frontend\modules\v1\controllers\Comments;

class OpenCoursesController extends ActiveController {
    public $modelClass = 'app\models\OpenCourses';

    public function actionList(){
        $postData = Yii::$app->request->post();
        $limit = $postData['limit'] ? $postData['limit'] : 5;
        $page = $postData['page'] ? $postData['page'] : 1;
        $courses = OpenCourses::find()
        ->offset($limit*($page-1))
        ->limit($limit)
        ->orderBy(['create_at' => SORT_DESC])->all();
        $return = [];
        foreach ($courses as $key => $course) {
            $course->tag = explode('#', $course->tag);
            $courseList[$key]['course'] = $course;
            $courseList[$key]['isUserLike'] = $this->checkUserLike($course->course_id, $postData['uid']);
        }
        foreach ($courseList as $cour) {
            $return[] = $cour;
        }
        return ApiHelper::callback($return);
    }

    protected function checkUserLike($courseId, $uid){
        $redis = Yii::$app->redis;
        return $redis->sismember('courseLike:'.$courseId, $uid) ? true : false;
    }

    public function actionOne(){
        $postData = Yii::$app->request->post();
        $course = OpenCourses::findOne($postData['course_id']);
        $course->updateCounters(['view_count' => 1]);
        $course->tag = explode('#', $course->tag);
        $return = [
            'course' => $course,
            //'comments' => Comments::getCommentByPage($course->course_id, $postData['uid']),
            'isUserLike' => $this->checkUserLike($course->course_id, $postData['uid']) 
        ];
        return ApiHelper::callback($return);
    }

    public function actionLike(){
        $courseId = Yii::$app->request->post('course_id');
        $course = OpenCourses::findOne($courseId);
        $result = $course->updateCounters(['like_count' => 1]);
        $redis = Yii::$app->redis;
        $redis->sadd('courseLike:'.$courseId, Yii::$app->request->post('uid'));
        return ApiHelper::callback($result);
    }

    public function actionUnlike(){
        $courseId = Yii::$app->request->post('course_id');
        $course = OpenCourses::findOne($courseId);
        $result = $course->updateCounters(['like_count' => -1]);
        $redis = Yii::$app->redis;
        $redis->srem('courseLike:'.$courseId, Yii::$app->request->post('uid'));
        return ApiHelper::callback($result);
    }
}