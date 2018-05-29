<?php
namespace frontend\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use app\models\Colleges;
use common\widgets\ApiHelper;

class CollegesController extends ActiveController {
    public $modelClass = 'app\models\Colleges';

    public function actionRegions(){
        $regionList = Yii::$app->db->createCommand(
            'SELECT r.region_id,r.region_name,p.province_id,p.province FROM tbl_regions AS r, tbl_provinces AS p WHERE r.region_id=p.region_id'
        )
        ->queryAll();
        var_dump($regionList);exit;
        foreach ($regionList as $region) {
            
        }
        return ;
    }

    public function actionColleges(){
        $collegeList = Yii::$app->db->createCommand(
            
        )
        ->queryAll();
        return ;
    }
}