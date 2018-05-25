<?php

namespace common\widgets;

class ApiHelper {
    public static function callback($data, $status = 200, $msg = 'ok'){
        return [
            'status' => $status,
            'msg' => $msg,
            'data' => $data
        ];
    }
}