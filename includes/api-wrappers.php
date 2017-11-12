<?php
/**
 * Created by Christian Augustine
 * Date: 06/01/17
 * Time: 21:32

 */

define('LOG_PATH', '/tmp/api_imports/');
define('LOG_FILE', '/tmp/contactNowAPI.log');
define('TOKEN_FILE', '/tmp/contactNowToken.log');

/**
 * Make a curl request to a url with any request types
 * @param type $url API Base URL including the script name
 * @param type $post POST data
 * @param type $import
 * @return type
 */
function post_request($url, $post = array(), $import = "") {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
    curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');


    if (!empty($import)) {
        $id = uniqid();
        $filename = "import-$id.json";
        $dir_name = LOG_PATH;

        if (!is_dir($dir_name)) {
            mkdir($dir_name);
        }
        $uploadfile = "$dir_name/$filename";
        $fh = fopen($uploadfile, "w");
        fputs($fh, $import);
        fclose($fh);

        $post['easycall'] = "@$uploadfile";
        $post['filename'] = $filename;
    }

    foreach ($post as $field => $value) {
        if (is_array($value)) {
            $post[$field] = json_encode($value);
        }
    }
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $postResult = curl_exec($ch);
    curl_close($ch);
    if (isset($uploadfile)) {
        @unlink($uploadfile);
    }
    return $postResult;
}

/**
 * Get authentication token
 * @global url API_H
 * @global string API_U
 * @global string API_P
 * @return string
 */
function get_auth_token() {
    $url = API_H.'/token.php?action=get&format=json&username='.API_U.'&password='.API_P;
    $post = http_build_query(array('username' => API_U, 'password' => API_P));
    $context  = stream_context_create(array('http' =>
        array(
            'method'  => 'GET',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
        )
    ));

    $response = file_get_contents($url, false, $context);
    // decode the token response.
    $tokenArray = json_decode($response, true);

    if (!$tokenArray || empty($tokenArray['success'])) {
        $msg  = "API Token Call: \nURL: $url\n" . print_r($post, true);
        $msg .= "\nAPI Token Response:\n$response\n\n";
        file_put_contents(LOG_FILE, $msg, FILE_APPEND);
    }
    // store the token data in a local file. Ideally, this should be stored in 
    // in a local redis db
    file_put_contents(TOKEN_FILE, print_r($response, true));
    return $tokenArray;
}

/**
 * 
 * @return type
 */
function getTokenValue (){
    if(file_exists(TOKEN_FILE)){
        $response = file_get_contents(TOKEN_FILE);
        $tokenArray = json_decode($response, true);
        if (!$tokenArray || (!isset($tokenArray['token']) && !isset($tokenArray['expire']))){
            $tokenArray = get_auth_token();
        }
        // Check if the expire time has elapse
        $expireAt = new DateTime('@'.$tokenArray['expire']);
        $now = new DateTime(date("Y-m-d H:i:s"));
        $timeLapse = $now->diff($expireAt);
        if ($timeLapse->h === 0 && $timeLapse->i <= 1){
            $tokenArray = get_auth_token();
        }
    } else {
        $tokenArray = get_auth_token();
    }
    if (empty($tokenArray['token'])) {
        throw new Exception(print_r($tokenArray, true));
    }
    return $tokenArray['token'];
}

/**
 * Call the DXI APIs, 
 * @global url API_H
 * @global string $API_TOKEN
 * @global link $logFile
 * @global bool $LOGGING_ENABLED
 * @global bool $Debug
 * @param string $script
 * @param array $get
 * @param array $post
 * @param array $import
 * @return array
 */
function dxi($script, $get = array(), $post = array(), $import = array()) {
    global $API_TOKEN;
    global $logFile, $LOGGING_ENABLED, $Debug;

    // Check we have a valid token or get a new one
    if (!isset($API_TOKEN)) {
        $API_TOKEN = getTokenValue();
    }
    $get['token'] = $API_TOKEN;
    $get['format'] = 'json';
    $get['campaign'] = CCID;

    $url = API_H."/$script.php?" . http_build_query($get);
    log_debug("API Call: \nURL: $url\n" . print_r($post, true) . print_r($import, true));
    $import = json_encode($import);
    if ($import[0] != "[") {
        $import = "[$import]";
    }
    $t = microtime(true);
    log_debug("--URL: $url\n ".print_r($post, true). "\n ".print_r($import, true));
    $response = post_request($url, $post, $import);
    $dur = round(microtime(true) - $t, 3);

    $json = json_decode($response, true);

    //var_dump($json);

    if (!$json || empty($json['success'])) {
        $msg  = "API Call: \nURL: $url\n" . print_r($post, true) . print_r($import, true);
        $msg .= "\nAPI Call Response:\n$response\n\n";
        file_put_contents(LOG_FILE, $msg, FILE_APPEND);
    }

    // If token has expired get a new one and re-send the API request
    if (isset($json['error']) && $json['error'] == 'Expired token' && isset($json['expire']) && $json['expire'] == -1) {
        $API_TOKEN = getTokenValue();
        $get['token'] = $API_TOKEN;
        $url = API_H."/$script.php?" . http_build_query($get);
        $response = post_request($url, $post, $import);

        $json = json_decode($response, true);
    }

//    arrayHtmlEntities($json);
    if (!empty($Debug) || !empty($LOGGING_ENABLED) && !empty($logFile)) {
        log_debug("\tAPI Call Response JSON ($dur seconds): \n" . print_r($json ? $json : $response, true));
    }

    if (!$json || empty($json['success'])) {
        $msg  = "API Call: \nURL: $url\n" . print_r($post, true) . print_r($import, true);
        $msg .= "\nAPI Call Response:\n$response\n\n";
        file_put_contents(LOG_FILE, $msg, FILE_APPEND);
    }

    return $json;
}

/**
 * Useful for ecnow and database APIs, will place data into import for create / update / delete
 * Note the memory reference on the parameters
 * @param string $action
 * @param array $get
 * @param array $post
 * @param array $import
 * @param array $data
 */
function build_request_data(&$action, &$get, &$post, &$import, &$data) {
    $get = $post = $import = array();
    if (in_array($action, array('create', 'update', 'delete'))) {
        if (is_array($data)) {
            $values = array_values($data);
            if (sizeof($values) > 0 && !is_array($values[0])) {
                $data = array(0 => $data);
            }
        }
        $import = $data;
    } else {
        $post = $data;
    }
}


/**
 * A general function should be avoided, an alias for each type is better, used by api_db and api_ecnow
 * @param string $script
 * @param string $method
 * @param string $action
 * @param array $data
 * @return array
 */
function api($script, $method, $action, $data = array()) {
    build_request_data($action, $get, $post, $import, $data);
    $get['method'] = $method;
    $get['action'] = $action;
    return dxi($script, $get, $post, $import);
}

/**
 * Pull extra configuration from related components
 * @param type $result
 * @param type $method
 * @return type
 */
function pullExtraComponents($result, $method){
    $ecnow = array(); $uids = ''; $sep = '';
    $filters = array('queues' => 'qid', 'agents' => 'agent', 'outcomecodes' => 'outcome');
    foreach ($result['list'] as $obj) {
        $uids .= $sep . $obj[$keys[$method]];
        $sep = ',';
    }
    $export = api_ecnow("ecnow_$method", 'read', array($filters[$method] => $uids));
    if (!empty($export['success']) && !empty($export['list']) ) {
        foreach ($export['list'] as $item) {
            $ecnow[$item[$keys[$method]]] = $item;
        }
    }
    foreach ($result['list'] as &$obj) {
        $key = $obj[$keys[$method]];
        if (!isset($ecnow[$key])) continue;
        foreach ($ecnow[$key] as $field => $value) {
            if (!isset($obj[$field])) {
                $obj[$field] = $value;
            }
        }
    }
    return $result;
}
/**
 * Helper function
 * @param type $result
 * @param type $method
 * @param type $action
 * @return type
 */
function persistECNExtraObjects($result, $method, $action){
    // We expect result to contain success (boolean) and list (array
    if (!empty($result['success']) || !empty($result['list'])){
        return $result;
    }
    $keys = array('queues' => 'qid', 'agents' => 'agentid', 'outcomecodes' => 'outcome');
    if ($action == 'read') {
        return pullExtraComponents($result, $method);
    } else if ($action == 'update' || $action == 'create') {
        if (!empty($result['key'])) {
            $data[$keys[$method]] = $result['key'];
        }
        $result2 = api_ecnow("ecnow_$method", $action, $data);
        if (!empty($result2['success']) && $result2['total'] > $result['total']) {
            $result['total'] = $result2['total'];
        }
    }
    return $result;
}

// Alias for database API
function api_db($method, $action, $data = array()) {
    // call the api
    $result = api("database", $method, $action, $data);
    // merge ecnow queues and agents with dxi, on update send same data to both
    if (!empty($result['success']) && in_array($method, array('queues', 'agents', 'outcomecodes'))) {
        return persistECNExtraObjects($result, $method, $action);
    }
    return $result;
}

// Alias for reporting API (use this for all reporting when implementing new features)
function api_reporting($method, $post = array()) {
    $get['method'] = $method;
    return dxi("reporting", $get, $post, null);
}

// Alias for agent api
function api_agent($action, $get = array()) {
    $get['action'] = $action;
    return dxi("agent", $get);
}

// ecnow api alias
function api_ecnow($method, $action, $data = array()) {
    return api("ecnow", $method, $action, $data);
}

// ajax api alias
function api_ajax($method, $get = array()) {
    $get['method'] = $method;
    $get['action'] = 'read';
    return dxi("ajax", $get, null, null);
}

/**
 * Upload a file on disk.
 * @param type $fname
 * @param type $url
 * @return type
 */
function api_upload_raw_file($fname, $url) {
    if (preg_match('/(^$|\s)/', $fname)) {
        return array(false, "The filename cannot contain whitespaces.");
    }
    if (!file_exists($fname)) {
        return array(false, "Did not find file to upload: $fname");
    }
    // Send file to API
    log_debug("API Upload: \n\tURL: $url\n\tFilename: $fname");
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array('easycall' => "@{$fname}"));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $return = curl_exec($ch);
    curl_close($ch);
    log_debug("API Response: \n$return");

    return array(true, $return);
}

// Upload an audio file to the api
function api_upload_audio_file($wave_file_path, $wave_file_name, $ccid = 0, $transcript = "") {
    global $API_TOKEN;
    $url = API_H.'/database.php?method=audio_files';
    if (!empty($transcript)) {
        $url .= "&transcript=" . urlencode($transcript);
    }
    $url .= "&format=json";
    if (!isset($API_TOKEN)) {
        $API_TOKEN = getTokenValue();
    }
    $url .= "&token={$API_TOKEN}";
    if (empty($ccid) && !empty($_SESSION['ccid'])) {
        $ccid = $_SESSION['ccid'];
    }
    if (!empty($ccid)) {
        $url .= "&campaign=$ccid";
    }
    $fname = "$wave_file_path/$wave_file_name";
    list ($rc, $return) = api_upload_raw_file($fname, $url . '&action=create');

    $key = 0;
    $result = json_decode($return, true);
    if ($rc && !empty($result['key'])) {
        $key = $result['key'];
    } elseif (isset($result['error'])
        && strpos($result['error'], 'Filename already exists') === 0
    ) {
        // Do the overwrite
        list($rc, $return) = api_upload_raw_file($fname, $url . '&action=update');
        $result = json_decode($return, true);
        if ($rc && !empty($result['key'])) {
            $key = $result['key'];
        }
    }

    return array($rc, $key);
}

/**
 * HtmlEntities for all arrays and sub array values
 * The scope is to apply it for all API responses
 *
 * @param array or string $values by reference
 *
 */
function arrayHtmlEntities(&$values) {
    if (is_array($values)) {
        foreach ($values as $key1 => $value1) {
            arrayHtmlEntities($values[$key1]);
        }
    } else {
        $values = htmlentities($values);
    }
}

function log_debug($msg) {
    global $logFile, $LOGGING_ENABLED;
    if (!empty($LOGGING_ENABLED) && !empty($logFile)) {
        $date_time = date('Y-m-d H:i:s');
        fputs($logFile, $date_time . " DEBUG:" . $msg . "\n");
        fflush($logFile);
    }
    log_show($msg);
}

function log_show($msg) {
    global $Debug;
    $date = date("H:i:s");
    if (!empty($Debug)) {
        if ($Debug == "pre") {
            $msg = str_replace("<", "&lt;", $msg);
            $msg = str_replace(">", "&gt;", $msg);
            echo "<pre>$date - ".time_diff()." - $msg</pre>";
        }
        else if ($Debug == true) {
            echo "<br/>$date - ".time_diff()." - $msg";
        }
    }
}
function log_time($description) {
    log_debug( time_diff()." - $description");
}

function time_diff() {
    global $_TIME;
    $t = microtime(true);
    $dur = round($t - $_TIME, 3);
    $_TIME = $t;
    return $dur;
}

?>