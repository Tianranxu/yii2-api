<?php
namespace frontend\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use app\models\Colleges;
use common\widgets\ApiHelper;

class CollegesController extends ActiveController {
    public $modelClass = 'app\models\Colleges';

    public function actionList(){
        $collegeList = Yii::$app->db->createCommand(
            'SELECT c.college_id,c.college_name,s.subject_id,s.subject_name
             FROM tbl_colleges AS c,tbl_college_subject AS cs tbl_subjects AS s 
             WHERE c.college_id=cs.college_id AND s.subject_id=cs.subject_id AND c.province_id='.Yii::$app->request->post('province_id')
        )
        ->queryAll();
        var_dump($collegeList);exit;
        return ;
    }

    public function actionRegions(){
        $regionList = Yii::$app->db->createCommand(
            'SELECT r.region_id,r.region_name,p.province_id,p.province FROM tbl_regions AS r, tbl_provinces AS p WHERE r.region_id=p.region_id'
        )
        ->queryAll();
        foreach ($regionList as $reg) {
            $regions[$reg['region_id']]['region_id'] = $reg['region_id'];
            $regions[$reg['region_id']]['region_name'] = $reg['region_name'];
            $regions[$reg['region_id']]['provinces'][] = [
                'province_id' => $reg['province_id'],
                'province' => $reg['province']
            ];
        }
        foreach ($regions as $region) {
            //format data
            $return[] = $region;
        }
        return ApiHelper::callback($return);
    }
}