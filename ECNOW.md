This endpoint is the most widely used API. It allows dynamic data/record feed in/out
of a selected campaign and can manipulate the corresponding DXI record based on certain data properties.

Before delving deep into this API let's try to understand the important concept and 
components of the 8x8 ContactNow platform; without the overview knowledge you will
find it challenging.

# Table of Contents
**[Terminologies](#terminologies)**<br>
**[List of Methods](#list-of-methods)**<br>
**[ecnow_datasets](#ecnow_datasets)**<br>
**[campaign_tables](#campaign_tables)**<br>
**[campaign_fields](#campaign_fields)**<br>
**[ecnow_records](#ecnow_records)**<br>


# Terminologies
Some of the terms used are sometimes misunderstood and loosely used, I will explain!

### In order of relevance

1. ### Campaign (Not the actual __campaign__ notation)

    Is a database table holding one or multiple dataset of records.

2. ### Dataset

    Is as a set of records grouped together by an identifier within a Campaign. Since it's a child of a Campaign, it is used to identify a Campaign table when reading, creating, or updating a record. 
    Datasets have three main properties which are used to control status and dial order of its records as children.

    #### Properties

    1. Status

        Status defines the state of every record within a dataset.

        1. *HOLD*

            In HOLD state, all records in the dataset are removed from the dialler.

        2. *LIVE*

            All records in the dataset are on the dialler ready to start dialling.
            [Agents](#queues) assigned to this dataset via [Queues](#queues) **MUST** be logged in and in "Wait"
            status for the records to start dialling.

        3. *EXPIRED*

            All records have been closed and are no longer available to be dialled. 
            You will also not be able to edit or reopen the record once expired. 
            
    2. Priority
        
        This defines the order (0 to 90) in which records in a Campaign are dialled.
        A dataset with a priority of 90 (the highest possible priority) will take priority over
        any other dataset *WITHIN* the same Campaign.

        NOTE: 
            Dataset priorities do not affect other Datasets that are on a different Campaign regardless
            of its priority.
    
    3. Queues
        See [Queues](#queues)
    
    ![CampaignAndDatasetAnalogy](https://raw.githubusercontent.com/8x8-dxi/ContactNowAPI/master/images/CampaignsTables&Datasets.png)

3. ### Queues

    Queues are defined as type of communication channels, the types of queues you can use are 
    listed below.
    They are used to manage inflow and outflow of **Campaigns** be it Inbound, Outbound, ivr 
    or broadcast.

    There are various types of Queues with multiple inherited properties which defines
    the behaviours of a queue.
    ##### Queue Types
    *inbound*<br>
    *outbound*<br>
    *ivr*<br>
    *broadcast*<br>
    *message*<br>
    *sms_out*<br>
    *sms_broadcast*<br>

    Outbound Queues are tightly coupled with Agents and Datasets. 
    Inbound Queues are tightly coupled with Campaign Tables and Agents.
    Outbound Queues must be assigned to a specific Campaign.  

4. ### Outcomes / Outcomecodes

    Outcomes are a very important part of the dialler as they are not just used as flags for call outcomes but also play
    a very important role of deciding how records behave when loaded into the dialler.
    
    Typically outcomes have many properties but one of those (Complete) is very important when loading leads to be dialled 

    **Complete** which is denoted as either __Y__ or __N__ to indicates if a records should be removed from the dialling list.
    Setting a record with an outcome code of complete = Y will push the record onto the dialler provided the dataset status == LIVE


# List of Methods


## ecnow_datasets

This methods is used to manipulate datasets within a specified campaign. You can call this method
when you which to change the status of a dataset and its respective records.

Allowed Actions

    create
    read
    update

Filters

	qid: Queue id or comma separated list of queue id's
	dataset: Dataset id
	state: d_status value (HOLD|LIVE|EXPIRED)
	table: the campaign table name datasets were imported into
        description : Dataset name

Defaults

```json
    {
        "qid" : 0,
        "dataset" : 0,
        "d_status" : "HOLD",
        "d_priority" : 0,
        "notes" : "",
        "reccount" : "",
        
    }
```


Examples using the  *php*  [api-wrappers](https://github.com/8x8-dxi/ContactNowAPI/blob/master/includes/api-wrappers.php) script.

```php

// Initialise token

getTokenValue();

// Read all LIVE datasets from campaign Willtest using dataset status (state)

$filters = array('table' => 'Willtest', 'state' => 'HOLD');

// To target a specific dataset
// $filters = array('table' => 'Willtest', 'dataset' => 3);

$Datasets = api_ecnow('ecnow_datasets', 'read', $filters);

print_r($Datasets);
/*
Array
(
    [success] => 1
    [total] => 6
    [list] => Array
        (
            [0] => Array
                (
                    [dataset] => 351
                    [qid] => 75674454
                    [d_status] => LIVE
                    [d_priority] => 0
                    [notes] => Loaded by Importer 2.1
                    [callbacks] => none
                    [crm_account] => 0
                    [crm_campaign] =>
                    [description] => MyNumber
                    [locked] => 0
                )
        )
)
*/

```


## campaign_tables

This method is only used for reading Campaigns Table metadata

Examples using the  *php*  [api-wrappers](https://github.com/8x8-dxi/ContactNowAPI/blob/master/includes/api-wrappers.php) script.


```php


// Get all campaign tables from my Contact Centre
$CampaignTables = api_ecnow('campaign_tables', 'read');

print_r($CampaignTables)
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

```


## campaign_fields

Get a list of fields from a specific campaign

Allowed Action 

    read

Filters

	table - the campaign table
	name - the field name

Fields

	name - the name of the field in the database campaign table
	standard_field - greater than zero if it was a standard field when created
	group_name - the group the field is in eg Phone Numbers, Name and Address, System etc
	size - the size of the field (usually number of allowed characters)
	order - can be used to sort the fields into a specific order
	picklist - if this field has a predefined set of values, references 'picklist' filter in 'picklist' method
	table - the campaign table this field is in
	type - the type of field ('smalltext'/'varchar', 'select', 'radio', 'checkbox', 'largetext')
	prompt - an alias for the field, shown on the agent screens
	readonly - disables the input on agent screens
	search - allows search on the field on the inbound screen
	hidden - does not show the field on agent screns

    
Examples using the  *php*  [api-wrappers](https://github.com/8x8-dxi/ContactNowAPI/blob/master/includes/api-wrappers.php) script.

```php

$filters = array('table' =>'Willtest' /*,'name' => 'id'*/);

//$myCampaignMetadata = api_ecnow('campaign_fields', 'read', $filters);

print_r($myCampaignMetadata);
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

```

## ecnow_records

This method allows for creating/reading/updating of records within a specified campaign/dataset

Action 

    create
    read
    update

Filters

    One of these is required:
    table: the campaign table name
    dataset: the dataset id

    id: match a record on its ID field
    outcome: match outcomecode or comma seperated list of outcomecodes
    agent: agent specific records
    ddi: match any phone numbers in the HomePhone, WorkPhone, MobilePhone
    search: array of search params eg `array("firstname" => "john", "lastname" => "c")`

Defaults

```javascript

    {
        "id": 1,// The record ID/URN
        "dataset": 1, // DatasetId
        "agentref":0,// Agent ID the records is assigned to
        "homephone": "",// String of phone number
        "mobilephone": "",// String of phone number
        "workphone": "", // String of phone number
        "callback": "",// datetime default "0000-00-00 00:00:00"
        "outcomecode": "",// Outcome code ID
        "agent_specific": NULL,// 1 if the record MUST only be dialled by the **agentref**
        "call_back_sametime": NULL,// 1 if the record MUST dialled the same time from the last time it was dialled.


    }
```
Examples using the  *php*  [api-wrappers](https://github.com/8x8-dxi/ContactNowAPI/blob/master/includes/api-wrappers.php) script.

### Reading data from ecnow_records
```php


// Filters includes all possible filters for reading records. All expect from 'table'
// are optional

$filters = array(
    // Mandatory
    'table' => 'Willtest',
    // Other Options
    'dataset' => 3,
    // If you need to return data by callback datetime or process date. This filter works with tstart & tstop
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
        'id' => 34,// supplying id will ignore every other search fields
        //'phone' => '01234567890' // Will search all phone fields (HomePhone, WorkPhone, MobilePhone)
        
    ),
    /*
     * This filter only applies when using the 'search' filter. Defaults to wildcard search
     * when supplied will search the exact values provided the you search list
     */
    'type' => 'exact',
    /*
     * Will search all phone fields (HomePhone, WorkPhone, MobilePhone)
     */
    'ddi' => '01234567890',
    
);

$Records = api_ecnow('ecnow_records', 'read', $filters);

print_r($Records);

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
                    [Call_Back_Sametime] => // Notes are callback notes added by the agent
                    [Customer_Email] =>
                    [datasetid] => 3
                    [FirstName] =>
                    [flgid] =>
                    [HomePhone] =>
                    [id] => 411
                    [LastName] =>
                    [loaddate] => 2015-01-23
                    [MobilePhone] => 01234567890
                    [notes] => // This is a system generated notes/log
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

```
### Creating new lead on ContactNow 
Using the  *php*  [api-wrappers](https://github.com/8x8-dxi/ContactNowAPI/blob/master/includes/api-wrappers.php) script.

```php
// Mandatory fields When compiling you data.

'dataset' => 1 // Dataset status MUST be NOT BE EXPIRED. Please read Dataset section of this page.

// ONE OF THESE PHONE FIELDS MUST BE SUPPLIED.
'HomePhone' => '',
'WorkPhone' => '',
'MobilePhone' => '01234567890',

// Build a List of leads to be pushed. 

$leads = array(
    array(
        // Mandatory fields
        'dataset' => 3, // Dataset status MUST be NOT BE EXPIRED
        // ONE OF THESE PHONE FIELDS MUST BE SUPPLIED.
        'HomePhone' => '',
        'WorkPhone' => '',
        'MobilePhone' => '01234567890',
        
        'Title' => 'Mr.',
        'FirstName' => 'Jon',
        'LastName' => 'Dow',
        'Address1' => '123 Street Name',
        'Address2' => 'Address line 2',
        'Address3' => 'Town',
        'Address4' => 'City',
        'Address5' => 'State',
        'Address6' => 'Country',
        'Postcode' => 'Postal Code',
        // Optional fields for deciding how the record should be dialled
        'callback' => '2017-12-10 10:00:00',
        'AgentRef' => 503314,// Pass a preferred agent id you want the call to be assigned to
        'outcomecode' => 109, // This can be any other outcome code with a non Complete flag set to 'N'
        'Call_Back_Notes' => 'This is a note for agent to see',
        'notes_append' => date('Y-m-d H:i:s'). ': Add a log'
    )
    //array(...),
    //array(...),
    //array(...)
);

// Can also POST a single lead/record like so
/*
$leads = array(
        // Mandatory fields
        'dataset' => 3, // Dataset status MUST be NOT BE EXPIRED
        // ONE OF THESE PHONE FIELDS MUST BE SUPPLIED.
        'HomePhone' => '',
        'WorkPhone' => '',
        'MobilePhone' => '01234567890',
        
        'Title' => 'Mr.',
        'FirstName' => 'Jon',
        'LastName' => 'Dow',
        'Address1' => '123 Street Name',
        'Address2' => 'Address line 2',
        'Address3' => 'Town',
        'Address4' => 'City',
        'Address5' => 'State',
        'Address6' => 'Country',
        'Postcode' => 'Postal Code',
);
*/

$Records = api_ecnow('ecnow_records', 'create', $leads);

print_r($Records);
/*
 * A returned response will include the following keys and values
    success => 1/0 (1=true, 0=false) 
    total => 0 to n. This is the total number of record that was successfully inserted 
    [bad] => 0 to n. Number of records that may have failed validation
    [key] => Record ID inserted
    [info] => Depending on the status of the dataset the returned information might either be 
             Record is LIVE on the dialer. OR Record NOT live on the dialer.

Array
(
    [success] => 1
    [total] => 1
    [bad] => 0
    [key] => 2499265
    [info] => Record NOT live on the dialer. 
)
*/

/*
 * Possible error that may occur which mean that your code did not POST a file
 * to the API. 
 * Make sure that your code have the right access to write the data to API_LOG_DIR
 * Array
(
    [success] =>
    [error] => No file uploaded. Missing 'easycall' section?
)
 */
 
```


### Updating lead on ContactNow
Using the *php* [api-wrappers](https://github.com/8x8-dxi/ContactNowAPI/blob/master/includes/api-wrappers.php) script.

The update action of the ecnow_records can be used to change a record/lead information.
It can also be used in conjunction with outcomecode to remove/add a record to the dialler
depending on the dataset status. Please see example below for updating a record and also removing a record
from the dialler.


Please see [Outcome code on the DATABASE endpoint](https://github.com/8x8-dxi/ContactNowAPI/blob/master/DATABASE.md#outcomecodes)
```php
/*
    Here is an example for updating a lead
    You must supply the record ID and the dataset you wish to update
*/

// This example explain how you would remove a record from the dialler by supplying a complete outcome code created by you.
$lead = array(
    // Mandatory fields
    'id' => 2499265,
    'dataset' => 3,
    // The record WILL BE removed from the dialler if the outcome code complete value is set to 'Y'
    'outcomecode' => 510456,
    'notes_append' => 'Setting record to Complete.'
);

$result = api_ecnow('ecnow_records', 'update', $lead);

print_r($result);
/*
Result of a successful update

Array
(
    [success] => 1
    [total] => 1
    [bad] => 0
    [info] => Record is NOT live on the dialer due having a complete outcome.
)


Result of a failed update for a mismatch record id and the dataset id


Array
(
    [success] => 1
    [total] => 0
    [bad] => 1
    [error] => Record id [2499265] does not exist.
)
*/


// Example for setting adjusting callback datetime.

$lead = array(
    // Mandatory fields
    'id' => 967849,
    'dataset' => 3,
    'callback' => '2017-12-01 10:00:00',
    'notes_append' => 'Setting record to Complete.'
);

$result = api_ecnow('ecnow_records', 'update', $lead);

print_r($result);



```

## ![Back to Index](https://github.com/8x8-dxi/ContactNowAPI/wiki)