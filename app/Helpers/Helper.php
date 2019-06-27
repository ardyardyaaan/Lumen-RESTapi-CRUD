<?php

if (!function_exists('responses')) {
    /**
     * Returns response json with 200 status code
     *
     * @param integer $status
     * Status that will returned to response
     *
     * @param array $data
     * Data or Message that will returned to response
     *
     * @return json a json response for API
     *
     * */

     function responses($data, $status) {
        $resultPrint = [];

        $resultPrint['error'] = false;
        if ($status == null) {
            $resultPrint['code'] = 200;
        } else {
            $resultPrint['code'] = $status;
        }
        if (isset($data['message']) == false) {
            $resultPrint['message'] = 'Success';
        } else {
            $resultPrint['message'] = $data['message'];
        }
        $resultPrint['result'] = [];

        $data = json_decode($data);
        if(is_array($data) == true) {
            $total = count($data);
            $resultPrint['result']['num_found'] = $total;
            $resultPrint['result']['start_index'] = 0;
            $resultPrint['result']['end_index'] = $total-1;
            $resultPrint['result']['rows'] = $data;
        } else {
            $resultPrint['result'] = $data;
            unset($resultPrint['result']->message);
        }

        return response()->json($resultPrint)->setStatusCode($resultPrint['code']);
     }
}

if (!function_exists('errorCustomStatus')) {
        /**
     * Returns response json with 400 or other error status code
     *
     * @param string $message
     * Message that will returned to response
     *
     * @param integer $status
     * Status that will returned to response
     *
     * @return json a json response for API
     *
     * */

     function errorCustomStatus($status, $message) {
        $resultPrint = [];
        $resultPrint['status'] = $status;

        switch ($status) {
            case 404:
                $resultPrint['message'] = "Halaman tidak ditemukan";
                break;
            case 403:
                $resultPrint['message'] = "Tidak memiliki izin untuk mengakses halaman ini";
                break;
            case 422:
                $resultPrint['message'] = "Data Belum Lengkap Cek Kembali Inputan Data Anda";
                break;
            case 504:
                $resultPrint['message'] = "Server sibuk";
                break;
            case 503:
                $resultPrint['message'] = "Layanan server tidak tersedia untuk saat ini";
                break;
            default:
                $resultPrint['message'] = $message;
                break;
        }
        return response()->json($resultPrint)->setStatusCode($status);
     }
}

if (!function_exists('errorQuery')) {
      /**
    *
    * @param string $message
    * Message when query error
    *
    * Returns response json with data from json
    *
    * @return json a json response for API
    *
    **/
    function errorQuery($message) {
        $resultPrint = [];
        $resultPrint['status'] = 500;
        $resultPrint['message'] = $message;

        return response()->json($resultPrint)->setStatusCode(500);
    }
}



// REDIS
if (!function_exists('getCache')) {

    /**
    *
    * @param string $key
    * Key for check redis key and return data if exist
    *
    * Returns response json with data from json
    *
    * @return json a json response for API
    *
    **/
   function getCache($key) {
       if (app('redis')->get($key) != null) {
           $data = app('redis')->get($key);
           return $data;
       }
   }
}

if (!function_exists('setCache')) {
     /**
    *
    * @param string $key
    * Set key for redis
    *
    * @param array $data
    * Data for redis
    *
    **/
   function setCache($key, $data) {
       app('redis')->set($key, $data);
       app('redis')->expire($key, 600);
   }
}

if (!function_exists('deleteCache')) {
    /**
   *
   * @param string $key
   * Delete key for redis
   *
   **/
   function deleteCache($key) {
       app('redis')->del($key);
   }
}
if (!function_exists('deleteAll')) {
   /**
  *
  * @param string $key
  * Delete key for redis
  *
  **/
  function deleteAll($key) {
      $allKeyapp = app('redis')->keys($key);
      app('redis')->del($allKeyapp);
  }
}



