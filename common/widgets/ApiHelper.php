<?php

namespace common\widgets;

class ApiHelper {
    public static function callback($data, $status = 200, $code = 'ok'){
        return [
            'status' => $status,
            'code' => $code,
            'data' => $data
        ];
    }
}