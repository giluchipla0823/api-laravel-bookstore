<?php

namespace App\Libraries;

class Api {
    CONST CODE_SUCCESS = 1;
    CONST CODE_ALERT = 2;
    CONST CODE_ERROR = 3;
    CONST CODE_ERROR_DB = 4;

    CONST IDX_STR_API_JSON = 'jsonapi';
    CONST IDX_STR_API_VERSION = 'version';
    CONST IDX_STR_API_NAME = 'name';
    CONST IDX_STR_API_SUMMARY = 'summary';
    CONST IDX_STR_JSON_STATUS = "status";
    CONST IDX_STR_JSON_CODE = "code";
    CONST IDX_STR_JSON_MESSAGE = "message";
    CONST IDX_STR_JSON_MESSAGE_DETAIL = "description";
    CONST IDX_STR_JSON_ERRORS = "errors";
    CONST IDX_STR_JSON_DATA = "data";
    CONST IDX_STR_JSON_CACHE = "cache";

    CONST RESPONSE_SUCCESSFUL_OPERATION = 'Successful operation';

    public static $response = array(
        self::IDX_STR_API_JSON => array(
            self::IDX_STR_API_VERSION => '1.0.0',
            self::IDX_STR_API_NAME => 'Bookstore',
            self::IDX_STR_API_SUMMARY => 'Api for obtain information about books, authors, publishers and genres',
        )
    );

    public function makeResponse($message, $status, $code, $extra = array()){
        $response = self::$response;

        // $response[self::IDX_STR_JSON_STATUS] = $status;
        $response[self::IDX_STR_JSON_CODE] = $code;
        $response[self::IDX_STR_JSON_MESSAGE] = $message;

        foreach ($extra as $key => $value){
            $response[$key] = $value;
        }

        return $response;
    }
}