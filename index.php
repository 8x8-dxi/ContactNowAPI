<?php
require_once './includes/api-wrappers.php';

/*
 * Supply the API BASE URL
 */
define('API_H', '[API BASE URL]');//'[API BASE URL]'
/*
 * Supply your API username
 */
define('API_U', '');
/*
 * Supply your API password
 */
define('API_P', '');
/*
 * Supply your Contact Centre ID 
 */
define('CCID', 0);
/*
 * Dyanamic mutable variable.
 */
$API_TOKEN = "";



// Get token
getTokenValue();

// Read ecnow_datasets
$filters = array(
    // Mandatory
    'table' => 'Willtest',
    // Other Options
    'dataset' => 3,
    // If you need to return data by callback datettime or process date. This filter works with tstart & tstop
    'timetype' => 'callback', // 'callback' OR 'processdate'
    'tstart' => '2017-11-01',
    'tstart' => '2017-11-26 23:59:59',
    'tstart' => '2017-11-26 23:59:59',
    /*
     * Records modes can be (active, live, callbacks, live-callbacks, unexpired
     * active => all records with ProcessType IN ('HOLD: New Prospect', 'New Prospect', 'HOLD: NEEDSMOREWORK', 'NEEDSMOREWORK');
     * live => ProcessType IN ('NEEDSMOREWORK', 'New Prospect')
     * callbacks => ProcessType` IN ('HOLD: NEEDSMOREWORK', 'NEEDSMOREWORK')
     * live-callbacks => ProcessType IN ('NEEDSMOREWORK')
     * unexpired => ProcessType != 'EXPIRED'
     */
    'mode' => 'live',
    /*
     * You can search records using the search filter. Note that search is an array of field names and values
     * you wish to search for
     */
    'search' => array(
        'id' => 34,// suppying id will ignore every other search fields
        //'phone' => '01234567890' // Will search all phone fields (HomePhone, WorkPhone, MobilePhone)
        
    ),
    /*
     * This filter only applies when using the 'search' filter. Defaults to wildecard search
     * when supplied will search the exact values provided the you search list
     */
    'type' => 'exact',
    /*
     * Will search all phone fields (HomePhone, WorkPhone, MobilePhone)
     */
    'ddi' => '01234567890',
    
);

//$Records = api_ecnow('ecnow_records', 'read', $filters);
//
//print_r($Records);

/*
Array
(
    [success] => 1
    [total] => 3
    [list] => Array
        (
            [0] => Array
                (
                    [Address1] =>
                    [Address2] =>
                    [Address3] =>
                    [Address4] =>
                    [Address5] =>
                    [Address6] =>
                    [AgentRef] => 0
                    [Agent_Specific] =>
                    [appointment] =>
                    [callback] => 0000-00-00 00:00:00
                    [Call_Back_Notes] =>
                    [Call_Back_Sametime] =>
                    [Customer_Email] =>
                    [datasetid] => 3
                    [FirstName] =>
                    [flgid] =>
                    [HomePhone] =>
                    [id] => 411
                    [LastName] =>
                    [loaddate] => 2015-01-23
                    [MobilePhone] => 01234567890
                    [notes] => // Notes are callback notes.
                    [outcomecode] => 100
                    [Postcode] =>
                    [ProcessDate] => 0000-00-00 00:00:00
                    [ProcessType] => EXPIRED
                    [sourcefile] => 01234567890 SMS Broadcast test.csv
                    [Testy] =>
                    [ThisIsATimeField] =>
                    [Title] =>
                    [URN] =>
                    [VENDOR_URN] =>
                    [WorkPhone] =>
                )
    )   
)
*/


$filters = array('table' => 'Willtest', 'state' => 'LIVE');
//
//$Datasets = api_ecnow('ecnow_datasets', 'read', $filters);
//
//print_r($Datasets);

// Get agents
//$CampaignTables = api_ecnow('campaign_tables', 'read');
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

//$myCampaignSchema = api_ecnow('campaign_fields', 'read', array('table' =>'Willtest'));
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
//$filters = array('table' =>'Willtest', 'type' => 'outbound,inbound', 'filter'=>'!= 999');
//$myCampaignQueues = api_db('queues', 'read', $filters);
//print_r($myCampaignQueues);
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
                    [numbers] => 01234567890,01234567892
                )

            [1] => Array
                (
                    [qid] => 504025
                    [type] => outbound
                    [description] => William Outbound
                    [callerid] => 01234567890
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
                    [numbers] => 01234567890
                )

            [2]...
        )
 */