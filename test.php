<?php
require_once './includes/api-wrappers.php';

define('API_H', 'https://api-106.dxi.eu');
define('API_U', 'Willtest');
define('API_P', 'DIeOYiZf9Fp506bzQB');
define('CCID', 0);
$API_TOKEN = "";

echo getTokenValue();

// Get agents
$CampaignTables = api_ecnow('campaign_tables', 'read');
/*
 Array
(
    [success] => 1
    [total] => 4
    [list] => Array
        (
            [0] => Array
                (
                    [name] => APItestcampaign
                )

            [2] => Array
                (
                    [name] => BradOutbound
                )
            [3] => Array
                (
                    [name] => Willtest
                )

            [4]...
        )
 */

$myCampaignSchema = api_ecnow('campaign_fields', 'read', array('table' =>'Willtest'));
/*
 Array
(
    [success] => 1
    [total] => 33
    [list] => Array
        (
            [0] => Array
                (
                    [position] => 1
                    [table] => Willtest
                    [name] => id
                    [size] => 200
                    [standard_field] => 1
                    [group_name] => system
                    [type] => int
                    [prompt] => id
                    [readonly] => Y
                    [search] => yes
                    [hidden] => N
                    [hidden_on_script] => N
                    [order] => 0
                    [picklist] => 
                    [required] => N
                )
            [1]...    
        )
 */

// Get all Queues assigned to specific Campaign by setting a filter.
// Note that we are calling the database.php endpoint (api_db()
$filters = array('table' =>'Willtest', 'type' => 'outbound,inbound', 'filter'=>'!= 999');
$myCampaignQueues = api_db('queues', 'read', $filters);
print_r($myCampaignQueues);
/*
Array
(
    [success] => 1
    [total] => 134
    [list] => Array
        (
            [0] => Array
                (
                    [qid] => 504016
                    [type] => inbound
                    [description] => William Inbound Testing - Do not unassign
                    [callerid] => 08452866546
                    [campaignid] => 500650
                    [announce] => 15
                    [monitor] => 1
                    [amd] => 1
                    [level] => 100
                    [key] => 0
                    [sequential] => 0
                    [redirect] => voicemail
                    [max_wait] => 0
                    [sametime] => 0
                    [autohangup] => 0
                    [tod] => 0
                    [announce_jump] => 0
                    [max_wait_redirect] => 
                    [filter] => 0
                    [tpt_record] => 1
                    [alt_redirect] => 
                    [redirect_id] => 0
                    [announce_vm] => 0
                    [aod_ringtime] => 30
                    [aod_maxdial] => 1
                    [aod_passthru] => 1
                    [stop_monitor] => 0
                    [numbers] => 08452866546,02034766183
                )

            [1] => Array
                (
                    [qid] => 504025
                    [type] => outbound
                    [description] => William Outbound
                    [callerid] => 08452866546
                    [campaignid] => 500650
                    [announce] => 0
                    [monitor] => 1
                    [amd] => 1
                    [level] => 100
                    [key] => 0
                    [sequential] => 0
                    [redirect] => 
                    [max_wait] => 0
                    [sametime] => 0
                    [autohangup] => 0
                    [tod] => 0
                    [announce_jump] => 0
                    [max_wait_redirect] => 
                    [filter] => 0
                    [tpt_record] => 1
                    [alt_redirect] => 
                    [redirect_id] => 0
                    [announce_vm] => 0
                    [aod_ringtime] => 30
                    [aod_maxdial] => 1
                    [aod_passthru] => 1
                    [stop_monitor] => 1
                    [numbers] => 08452866645
                )

            [2]...
        )
 */