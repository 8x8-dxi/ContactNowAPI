Please [read the token endpoint](https://github.com/8x8-dxi/ContactNowAPI/blob/master/TOKEN.md) if you haven't already done so.

In this part of the documentation I will aim to explain how you would consume raw CDR data
generated from your Contact Centre using a list of exposed methods within the reporting API.

Please note that all responses from this endpoint will not include any customer information 
aside from the customer Unique Reference Number (URN), Phone Number of the contact and the Campaign Table 
where the full contact information is stored. 

This means that customer records stored on the [ECN Database](https://github.com/8x8-dxi/ContactNowAPI#high-level-api-diagram)
have to be requested using the [ecnow.php endpoint](https://github.com/8x8-dxi/ContactNowAPI/blob/master/ECNOW.md).

# Method
**[calls](#calls)**<br>
**[cdr](#cdr)**<br>
**[Requesting full customer data from cdr data](#requesting-full-customer-data-from-cdr-data)**<br>
![NodeJS Reporting Client Script](https://github.com/8x8-dxi/ContactNowAPI/blob/master/ReportingExamples.js)


## Methods

# calls
    
The calls method is used for gathering "grouped" data for a specified date range.
For example if you wanted to get the total calls handled by a specific agent or queue (see below for full list of filters).

It comes with some filters and "grouping" (fields) which allows you to manipulate what 
data is returned.

Requesting data requires the following mandatory parameters 

### Mandatory Params

Params | Definition
-----------------|-----------
fields  | List of fields to be returned. The list must be a string concatenated by a comma (,) Syntax `&fields=qtable,qtype,qid,qnm,aid,anm,dsid,dsnm,urn,ddi,cli,tid`
range | UTC Start and Stop Time. Syntax `&range=range=1493593200:1509580799` Note the colon (:) separator
grouby | The grouping fields. See group list below. Syntax `&groupby=qtable,qnm` (Note all fields can be used)

#### Fields and definitions

Field Names  | Description | Can be used as grouping 
-------------|--------------------|------------
qtable| campaign name of the assigned queue | True
qtype| The queue type. See ![Queues](https://github.com/8x8-dxi/ContactNowAPI/blob/master/ECNOW.md#queues) | True
qid| Queue id.| True
qnm| Queue description.| True
aid| Agent ID who made or took the call (default is 0 for unanswered calls)| True
anm| Agent name who made or took the call (if any| True
dsid| The dataset ID (if any. Default is 0) | True
dsnm| Dataset description/name.| True
urn| The contact/customer record unique record number.| True
ddi| The destination phone number.| True
cli| The phone number displayed to the callee know as Caller ID/Automatic Number Identification (ANI/CLI)| True
tid| Agent team id if used. (Default to 0)| True
tnm| Team name.| True
has_aid| Analogous to *aid*. 1 if an agent was assigned to the call, 0 otherwise.| True
PT1H| | True
P1D| | True
P1W| | True
P1M| | True
date| the date (YYYY-MM-DD).| True
day| the day of the week (Monday, Tuesday, etc).| True
hour| the hour of the day (24 hour).| True
min_10| the 10 minute period the call was placed within (00, 10, 20, 30, 40, 50).| True
dest| the outbound or inbound destination.| True
dcode| the prefix of the number dialled that was used to determine the destination.| True
ctype| Values (in, out).| True
dtype| Values (in, out, man, tpt, sms_out).| True
cres| the call result (Answered, Dead Line, No Answer, etc).| True
i~~is_mob~~| 1 if DDI starts with 07, 0 otherwise. (This rule is only relevant to the UK number matching)| True
nc_all| the total number of calls.| False
nc_in| the number of inbound calls.| False
nc_out| the number of outbound calls made by the dialler.| False
nc_out_all| the number of outbound calls.| False
nc_sms_out| the number of outbound sms messages sent.| False
nc_man| the number of manual calls.| False
nc_tpt| the number of third-party calls.| False
nc_dtpt| the number of non-agent transfer calls.| False
nc_wait| the number of calls with a wait time greater than zero| False
nc_wrap| the number of calls with a wrap time greater than zero| False
nc_con| the number of connected calls - excluding those dispositioned with Answer Machine (agent).| False
nc_ans| the number of answered calls.| False
nc_ans_in| | False
nc_ans_man| the number of answered manual calls.| False
nc_que| the number of calls placed in queueing system but not answered.| False
nc_ans_le| the number of inbound calls answered before cutoff X seconds.| False
nc_ans_gt| the number of inbound calls answered after cutoff X seconds.| False
nc_que_le| the number of inbound calls abandoned before cutoff X seconds.| False
nc_que_gt| the number of inbound calls abandoned after cutoff X seconds.| False
sec_dur| the call duration in seconds.| False
sec_call| sum of agent call time.| False
sec_ans| | False
ocid| the call outcome id.| True
ocnm| the call outcome name.| True
ocis_cmpl| boolean flag if the outcome is a complete (excluding sales).| True
ocis_cmpli| boolean flag if the outcome is a complete (including sales).| True
ocis_sale| boolean flag if the outcome is a sale.| True
ocis_dmc| boolean flag if the outcome is marked as a DMC (decision maker contact).| True
oc_abdn| number of outcomes - abandoned calls.| False
oc_ama| number of outcomes - answer machine (agent).| False
oc_amd| number of outcomes - answer machine (dialler).| False
oc_dead| number of outcomes - dead lines.| False
oc_noansw| the number of outcomes - no answers.| False
oc_sale| number of outcomes - sales| False
oc_cmpl| number of outcomes - completes (excluding sales)| False
oc_cmpli| number of outcomes - completes (including sales)| False
oc_ncmpl| number of outcomes - incompletes| False
oc_dmc| number of outcomes - DMC's| False
cost_cust| the call cost| False
bill_cust| the call cost | False
bill_dur| the duration used to calculate the cost| False


### Filters and definitions

The following filters can be used when requesting data using the calls method.
The syntax for filtering `&fieldName=valueOfTheField` 

Filters | Description 
----------|---------|
**range** | Date range in UTC (Unix Time Stamp)
**campaign** | The Campaign table see ![The terminologies](https://github.com/8x8-dxi/ContactNowAPI/blob/master/ECNOW.md#the-terminologies) section of the ECNOW endpoint
**queue** | Queue ID 
**qtype** | Queue type. See ![Queues](https://github.com/8x8-dxi/ContactNowAPI/blob/master/ECNOW.md#queues) schema
**ctype** |Call direction. Can either be "in" OR "out"
**agent** | Agent ID
**dataset** | Dataset ID
**outcome** | Outcome code ID
**cutoff** | SLA figure in seconds for call cutoff times.
**ddi** | This should be the customer Phone number depending on the call direction.
**cli** | Caller ID/Display Number. For incoming call the CLI will be the customers phone number.
**urn** | Customer ID reference the ECN Database. See ![Dataset](https://github.com/8x8-dxi/ContactNowAPI/blob/master/ECNOW.md#dataset) schema
**team** | Team ID



Examples: Constructing a request using NodeJS

```javascript
/*
    Created by Christian Augustine
    ----
    Reporting API NodeJS client script

    Simple GET request Sample

    https://[BASE_API_URL]/reporting.php?token=YOUR-TOKEN&method=calls&format=json&fields=qtable,qtype,qid,qnm,aid,anm,dsid,dsnm,urn,ddi,cli,tid,tnm,has_aid,PT1H,P1D,P1W,P1M,date,day,hour,min_10,dest,dcode,ctype,dtype,cres,is_mob,nc_all,nc_in,nc_out,nc_out_all,nc_sms_out,nc_man,nc_tpt,nc_dtpt,nc_wait,nc_wrap,nc_con,nc_ans,nc_ans_in,nc_ans_man,nc_que,nc_ans_le,nc_ans_gt,nc_que_le,nc_que_gt,sec_dur,sec_talk_all,sec_talk,sec_tpt,sec_call,sec_ans,ocid,ocnm,ocis_cmpl,ocis_cmpli,ocis_sale,ocis_dmc,oc_abdn,oc_cbck,oc_ama,oc_amd,oc_dead,oc_noansw,oc_sale,oc_cmpl,oc_cmpli,oc_ncmpl,oc_dmc,cost_cust,bill_cust,bill_dur&range=1493593200:1509580799&groupby=qtable,qnm&agent=503314
*/


var request = require("request");

var BASE_API_URL = "",// Get this from https://github.com/8x8-dxi/ContactNowAPI#api-domains
    _TOKEN=null,
    _APIUSERNAME = '', // Initialise your API Username
    _APIPASSWORD = ''; // You API Password



var reportingEndpoint = 'https://'+BASE_API_URL+'/reporting.php',
    ECNOWEndpoint = 'https://'+BASE_API_URL+'/ecnow.php';

// Change the datetime
// Get the epoch time from the start and stop time
var tstartObject = new Date("2017-11-14 00:00:00"), 
    tStopObject = new Date("2017-11-14 23:59:59"),
    tstart = tstartObject.getTime()/1000,
    tstop = tStopObject.getTime()/1000;


/**
 * 
 * @param {Function} callbackFunction
 * @returns {undefined}
 */
var getToken = function (callbackFunction) {
    if (_TOKEN !== '') return callbackFunction(false, _TOKEN);;
    var options = {
        method: 'GET',
        url: 'https://'+BASE_API_URL+'/token.php',
        qs: {
            action: 'get',
            username: _APIUSERNAME,
            password: _APIPASSWORD
        }
    };

    request(options, function (error, response, body) {
        if (error) {
            //throw new Error(error);
            return callbackFunction(true, error);
        }
        // You should really store the token on a local redis server to prevent
        // requesting token when it's not expired. See https://github.com/8x8-dxi/ContactNowAPI/blob/master/TOKEN.md for more info
        _TOKEN = JSON.parse(body);
        return callbackFunction(false, _TOKEN);
    });
};

/**
 * 
 * @param {Function} callBackFunction
 * @returns {undefined}
 */
var fetchCalls = function (callBackFunction) {
    if (typeof callBackFunction !== 'function'){
        throw new Error("Please provide a callback function as parameter");
    }

    var options = {
        method: 'GET',
        url: reportingEndpoint,
        qs: {
            token: '',// Toke will be intitialised in getToken Function
            method: 'calls',
            fields: 'qtable,qtype,qid,qnm,aid,anm,dsid,dsnm,urn,ddi,cli,tid,tnm,has_aid,PT1H,P1D,P1W,P1M,date,day,hour,min_10,dest,dcode,ctype,dtype,cres,is_mob,nc_all,nc_in,nc_out,nc_out_all,nc_sms_out,nc_man,nc_tpt,nc_dtpt,nc_wait,nc_wrap,nc_con,nc_ans,nc_ans_in,nc_ans_man,nc_que,nc_ans_le,nc_ans_gt,nc_que_le,nc_que_gt,sec_dur,sec_talk_all,sec_talk,sec_tpt,sec_call,sec_ans,ocid,ocnm,ocis_cmpl,ocis_cmpli,ocis_sale,ocis_dmc,oc_abdn,oc_cbck,oc_ama,oc_amd,oc_dead,oc_noansw,oc_sale,oc_cmpl,oc_cmpli,oc_ncmpl,oc_dmc,cost_cust,bill_cust,bill_dur,callid_max',
            range: tstart + ':'+tstop, // Start and stop time in UTC
            groupby: 'qtable,qnm', // Group the data by Campaign table and queue name
            agent: 503314,// Notice I am filtering by agent ID which should return every call handled by this agent only
            format: 'json'
            //apply any other filters
            //campaign:"",
            //queue:"",
           //qtype:"",
           //ctype:"",
           //agent:"",
           //dataset:"",
           //outcome:"",
           //ddi:"",
           //cli:"",
           //urn:"",
        }
    };
    // Get token and then attempt fetching the calls data
    getToken(function(err, tokenData){
        if(err || !tokenData.success || !tokenData.token){
            throw new Error("Unable to retrieve token data");
        }
        options.qs.token = tokenData.token;
        request(options, function (error, response, body) {
          if (error){
              throw new Error(error);
          }
          // print result to console.
          // print result to console.
          var data = JSON.parse(body);
          console.info(data);
          return callBackFunction(data);

          // Console log
          /*
            {
            "success": true,
                "total": 7,
                "list": [{
                    "ccid": "315",
                    "ccnm": "ECONStaff",
                    "qtable": "APItestcampaign",
                    "qtype": "outbound",
                    "qid": "719718",
                    "qnm": "API-DDI",
                    "aid": "503314",
                    "anm": "Christian Davies",
                    ...
                },
                ...
                ]
            }
            */
        });
    });
};
```


# cdr

The calls method is used for when you need to pull your entire calls data within
a given time range. It comes with filters allowing you to define what data you 
would like to return.

Note that the cdr method does not return the entire customer record. See example for 
pulling related customer records.

Requesting data requires your to supply the fields you would like to return in your request.

### Mandatory Params

Params | Definition
-----------------|-----------
fields  | List of fields to be returned. The list must be a string concatenated by comma (,) Syntax `&fields=callid,urn,qid,qnm,qtype,qtable,`
range | UTC Start and Stop Time. Syntax `&range=range=1493593200:1509580799` Note the colon (:) separator

#### Fields and definitions

Field Names  | Description | Can be used as grouping 
-------------|-------------|------------------------
callid| the individual call id.| True
qtable| campaign name of the assigned queue | False
qtype| The queue type. See ![Queues](https://github.com/8x8-dxi/ContactNowAPI/blob/master/ECNOW.md#queues) | False
qid| Queue id.| False
qnm| Queue description.| False
aid| Agent ID who made or took the call (default is 0 for unanswered calls)| False
anm| Agent name who made or took the call (if any| False
dsid| The dataset ID (if any. Default is 0) | False
dsnm| Dataset description/name.| True
urn| The contact/customer record unique record number.| False
ddi| The destination phone number.| False
cli| The phone number displayed to the callee know as Caller ID/Automatic Number Identification (ANI/CLI)| False
cres| the call result (Answered, Dead Line, No Answer, etc).| False
ocid| the call outcome id.| False
ocnm| the call outcome name.| False
flags| Identifies if the call was recorded or not. (recorded,processed)| False
carrier| the telecoms carrier the call was connected through.| False
ctag| The Tag ID of the tag| False
tag| The Tag Name of the Tag| False
dest| the outbound or inbound destination.| False
dcode| the prefix of the number dialled that was used to determine the destination.| False
ctype| Values (in, out).| False
dtype| Call Values (in, out, man, tpt, sms_out).| False
sms_msg| | True
sec_dur| the call duration in seconds.| False
sec_ring| the time between the call being acknowledged and answered/disconnected.| False
sec_que| the time between the call connecting to the queueing system and answered/disconnected.| False
tm_init| the time the call was initialised.| False
tm_answ| the time the call was answered.| False
tm_disc| the time the call was disconnected.| False
oc_sale| number of outcomes - sales| False
oc_cmpl| number of outcomes - completes (excluding sales)| False
oc_cmpli| number of outcomes - completes (including sales)| False
oc_ncmpl| number of outcomes - incompletes| False
oc_dmc| number of outcomes - DMC's| False
cost_cust| the cost| False
bill_cust| the cost| False
bill_dur| the duration used to calculate the cost| False
ivr_key| the last valid key pressed if the call was in an IVR.| False
sec_key| the time between the call connecting to the queueing system and the customer pressing their last IVR key.| False
orig_qnm| | False



### Filters and definitions

The following filters can be used when requesting data using the cdr method.
The syntax for filtering `&fieldName=valueOfTheField` 

Filters | Description 
----------|---------|
**range** | Date range in UTC (Unix Time Stamp)
**callid** | Call ID 
**campaign** | The Campaign table see ![The terminologies](https://github.com/8x8-dxi/ContactNowAPI/blob/master/ECNOW.md#the-terminologies) section of the ECNOW endpoint
**queue** | Queue ID 
**qtype** | Queue type. See ![Queues](https://github.com/8x8-dxi/ContactNowAPI/blob/master/ECNOW.md#queues) schema
**ctype** |Call direction. Can either be "in" OR "out"
**agent** | Agent ID
**dataset** | Dataset ID
**outcome** | Outcome code ID
**cutoff** | SLA figure in seconds for call cutoff times.
**ddi** | This should be the customer Phone number depending on the call direction.
**cli** | Caller ID/Display Number. For incoming call the CLI will be the customer phone number.
**urn** | Customer ID reference the ECN Database. See ![Dataset](https://github.com/8x8-dxi/ContactNowAPI/blob/master/ECNOW.md#dataset) schema
**sort** | When set the returned data will be return in a particular order of the fields you passed
**limit** | limits size of data returned
**start** | works together with limit


### Fork the !
```javascript
// Simple GET request
GET https://[BASE_API_URL]/reporting.php?token=YOUR-TOKEN&method=cdr&format=json&fields=callid,urn,qid,qnm,qtype,qtable,cres,aid,anm,dsid,ocid,ocnm,ddi,cli,flags,carrier,ctag,tag,dest,dcode,ctype,dtype,sms_msg,sec_dur,sec_ring,sec_que,tm_init,tm_answ,tm_disc,oc_sale,oc_cmpl,oc_cmpli,oc_ncmpl,oc_dmc,cost_cust,bill_cust,bill_dur,ivr_key,sec_key,orig_qnm&range=1493593200:1509580799       

/**
 *  Pull cdr methods
 * @param {type} callBackFunction
 * @returns {undefined}
 */
var fetchCDR = function (callBackFunction) {
    if (typeof callBackFunction !== 'function'){
        throw new Error("Please provide a callback function as parameter");
    }
    var options = {
        method: 'GET',
        url: reportingEndpoint,
        qs:{
            token: '',// Toke will be intitialised in getToken Function
            method: 'cdr',
            fields: 'callid,urn,qid,qnm,qtype,qtable,cres,aid,anm,dsid,ocid,ocnm,ddi,cli,flags,carrier,ctag,tag,dest,dcode,ctype,dtype,sms_msg,sec_dur,sec_ring,sec_que,tm_init,tm_answ,tm_disc,oc_sale,oc_cmpl,oc_cmpli,oc_ncmpl,oc_dmc,cost_cust,bill_cust,bill_dur,ivr_key,sec_key,orig_qnm',
            range: tstart + ':'+tstop, // Start and stop time in UTC
            format: 'json'
            //apply any other filters
            //campaign:"",
            //queue:"",
            //qtype:"",
            //ctype:"",
            //agent:"",
            //dataset:"",
            //outcome:"",
            //ddi:"",
            //cli:"",
            //urn:"",
            //callid:"",
            //sort:"",
            //start:"",
            //limit:"",
        }
    };
    // Get token and then attempt fetching the cdr data
    getToken(function(err, tokenData){
        if(err || !tokenData.success || !tokenData.token){
            throw new Error("Unable to retrieve token data");
        }
        options.qs.token = tokenData.token;
        request(options, function (error, response, body) {
          if (error){
              throw new Error(error);
          }
          // print result to console.
          var data = JSON.parse(body);
          console.info(data);
          return callBackFunction(data);

          // Console log
          /*
          {
            "success": true,
            "total": 68,
            "list": [
                {
                    "callid": "707228882",
                    "urn": "2428765",
                    "qid": "504016",
                    "qnm": "Christian Inbound Testing - Do not unassign",
                    "qtype": "inbound",
                    "qtable": "SomeTest",
                    "cres": "Answered",
                    "aid": "503314",
                    ...
                },
                ...
                ]
            }
            */
        });
    });
};
```

# Requesting full customer data from cdr data

This section demonstrates how you would request the corresponding customer record 
from the payload returned from either the cdr or calls method.

First we get the cdr data as explained in [cdr](#cdr) or using the [calls](#calls) method. The request
returns a list of calls data as described above, we then loop through the list of data requesting each
customer record using the cdr.urn and cdr.qtable.

Sample code for pulling customer records using the cdr method. 

Fork the NodeJs Reporting client script ![here](https://github.com/8x8-dxi/ContactNowAPI/blob/master/ReportingExamples.js)
```javascript

 // GET request URI sample to retrieve full customer record
 // GET https://[BASE_API_URL]/ecnow.php?token=YOUR-TOKEN&method=ecnow_records&action=read&urn=THE-URN&table=THE-qTable&format=json&

/**
 * This function read customer record from ecnow endpoint. 
 * It is called by getDialledCustomerRecords function (below) and takes a CDR data 
 * @param {Object} cdr
 * @returns {undefined}
 */
var getcustomerRecordByCDR = function(cdr){
    var options = {
        method: 'GET',
        url: ECNOWEndpoint,
        qs: {
            token: TOKEN,
            method: 'ecnow_records',
            format: 'json',
            action: 'read',
            urn: cdr.urn,
            table: cdr.qtable// This is required for targeting the table holding the record
        }
    };

    request(options, function (error, response, body) {
          if (error){
              throw new Error(error);
          }
          // print result to console.
          var customer = JSON.parse(body);
          if (customer && customer.success && customer.total > 0){
              // Do stuff with the record
          }
          console.info("customer: ", customer);
          /*
          {
            "success": true,
            "total": 1,
            "list": [
                {
                    "Address1": "121B London Road",
                    "Address2": "",
                    "Address3": "Reading",
                    "Address4": "Berkshire",
                    "Address5": "",
                    "Address6": "",
                    "AgentRef": "503314",
                    ...
                }
            ]
           */
    });
};
    

/**
 * Get customer records using data from the CDR log.
 * @returns {undefined}
 */
var getDialledCustomerRecords = function (){
    fetchCDR(function (cdrResponse) {
        if (!cdrResponse || !cdrResponse.success || !cdrResponse.list){
            throw new Error(cdrResponse);
        }
        // loop through the cdr data whilst requesting each record.
        for (var i = 0, l = cdrResponse.list.length; i < l; ++i) {
            var cdr = cdrResponse.list[i];
            // Notice we are checking the urn value and the table holding the record.
            if (cdr.urn > 0 && cdr.qtable !== ''){
                getcustomerRecordByCDR(cdr);
            }
        }
    });
};

```
