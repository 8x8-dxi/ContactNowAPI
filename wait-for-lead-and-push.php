<?php

/**
 * Created by Christian Augustine
 * Date: 29/04/2017
 * Time: 16:32

  This script receives a POST request from Easycontact Advance Outcome. Its purpose
  is solely to move record fom one Campaign to another using a target_dataset_id 
    set in the request url.

  Required Parameters
  target_dataset_id : The destination dataset ID the record should be pushed
  data : The post data to be pushed

  Optional Parameters
  outcomecode : A Complete outcomecode for suppressing the original record. If
  provided the original record will be completed to prevent it from further dialling.

    Exampale URL request
    http://server2.aspinmedia.net/app/wait-for-lead-and-push.php?target_dataset_id=21&outcomecode=73543
 */
include_once('./includes/api-wraper.php');


$date = date('Y-m-d h:i:s');

$request = $_REQUEST;

if (isset($request['target_dataset_id']) && isset($request['data']) && !empty($request['data'])) {

    $API_TOKEN = get_auth_token();
    error_log("Token: " . $API_TOKEN);

    $target_dataset_id = intval($request['target_dataset_id']);
    $data = json_decode($request['data'], true);
    $originalId = $data['id'];
    $originalDataset = $data['datasetid'];

    $data['outcomecode'] = 109;
    $data['ProcessType'] = 'NEEDSMOREWORK';
    $data['note'] = $date . " This record was moved From another Campaign with Dataset ID " . $data['datasetid'];
    unset($data['sourcefile'], $data['loaddate'], $data['id']);
    $data['dataset'] = $data['datasetid'] = $target_dataset_id;

    //error_log(print_r($data, true));
    $response = api_ecnow('ecnow_records', 'create', $data);
    error_log(print_r($response, true));
    if ($response['success'] && $response['total'] > 0) {
        if (isset($request['outcomecode'])) {
            // Complete the old record.
            $response = api_ecnow('ecnow_records', 'update', array(
                'id' => $originalId,
                'dataset' => $originalDataset,
                'outcomecode' => intval($request['outcomecode'])
            ));
            error_log(print_r($response, true));
        }
    }
}
exit;
// 
