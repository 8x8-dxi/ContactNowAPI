Please [read the token endpoint](https://github.com/8x8-dxi/ContactNowAPI/blob/master/TOKEN.md) if you haven't already done so.

In this part of the documentation I will aim to explain how you would consume data
generated from your Contact Centre using a list of exposed methods. 

Please note that all response from this endpoint will not include any customer information 
aside from the customer unique identifiers (UID) and the phone numbers of the customer record. 

This means that customer records stored on the [ECN Database](https://github.com/8x8-dxi/ContactNowAPI#high-level-api-diagram)
has to be requested using the [ecnow.php endpoint](https://github.com/8x8-dxi/ContactNowAPI/blob/master/ECNOW.md).


## Methods

### calls
    
The calls method is used for gathering "grouped" data for a specified date range.
For example if you wanted to get the total calls handled by a specific agent or queue (see below for full list of filters)

It comes with some filers and "grouping" (fields) which allows.

Requesting data requires the following parameters 

Mandatory Params | Definition
-----------------|-----------
fields  | List of fields to be returned. The list must be a string concatinated by comma (,) Syntax `&fields=qtable,qtype,qid,qnm,aid,anm,dsid,dsnm,urn,ddi,cli,tid`
range | UTC Start and Stop Time. Syntax `&range=range=1493593200:1509580799` Note the colon (:) separator
grouby | The grouping fields. See group list below. Syntax `&groupby=qtable,qnm` (Note all fields can be used)

#### Fields and definitions

Field Names  | Description | Can be used as grouping 
-------------|--------------------|------------
ccid| the call centre id.| True
ccnm| the call centre name.| True
cid| the campaign id.| True
cnm| the campaign name.| True
qtable| the queue's assigned campaign.| True
qtype| the queue type.| True
qid| the queue id.| True
qnm| the queue name.| True
aid| the agent id.| True
anm| the agent name.| True
dsid| the dataset id.| True
dsnm| the dataset name.| True
urn| the unique customer record number.| True
ddi| the destination phone number.| True
cli| the phone number displayed to the callee.| True
tid| the team id.| True
tnm| the team name.| True
has_aid| 1 if an agent is assigned, 0 otherwise.| True
PT10M| | True
PT15M| | True
PT30M| | True
PT1H| | True
P1D| | True
P1W| | True
P1M| | True
date| the date (YYYY-MM-DD).| True
day| the day of the week (monday, tuesday, etc).| True
hour| the hour of the day (24 hour).| True
min_10| the 10 minute period the call was placed within (00, 10, 20, 30, 40, 50).| True
dest| the outbound or inbound destination.| True
dcode| the prefix of the number dialed that was used to determine the destination.| True
ctype| Values (in, out).| True
dtype| Values (in, out, man, tpt, sms_out).| True
cres| the call result (Answered, Dead Line, No Answer, etc).| True
is_mob| 1 if DDI starts with 07, 0 otherwise.| True
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
nc_que| the number of calls placed in queuing system but not answered.| False
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
callid_max| | False


### Filters and definitions

The following filters can be when requesting data using the calls method.
The syntax for filtering `&fieldName=valueOfTheField` 

Filters | Description 
----------|---------|
**range** | Date range in UTS (Unix Time Stamp)
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
**team** | Team ID

## Grouping fields

Group Filters | Description 
----------|---------|
qtable |
qtype |
qid |
qnm |
aid |
anm |
dsid |
dsnm |
urn |
ddi |
cli |
tid |
tnm |
has_aid |
date |
day |
hour |
min_10 |
dest |
dcode |
ctype |
dtype |
cres |
is_mob |
ocid |
ocnm |
ocis_cmpl |
ocis_cmpli |
ocis_sale |
ocis_dmc  |


Examples: Constructing a request using NodeJS

```javascript
    var request = require("request"),
        endpoint = 'https://api-106.dxi.eu/reporting.php',
        token = '50f9ec11b35b113bf023314222444ea0bf6e7d76';
    
    var options = {
        method: 'GET',
        url: endpoint,
        qs:{
            action: 'fields',
            token: token,
            method: 'calls',
            format: 'json'
        }
    };

    /**
     * First we get the fields list from server. You probably only want to run
     * this once and then store the fields locally
     * @param Function callbackFn
     * @returns Function
     */
    var getFields = function (callbackFn){
        var fieldList = [];
        request(options, function (error, response, body) {
            if (error){
                return callbackFn(true, error);
                //throw new Error(error);
            }
            var Fields = JSON.parse(body);
            if (Fields && Fields.success && Fields.total > 0){
                for (let i=0, l = Fields.list.length; i < l; ++i){
                    fieldList.push(Fields[i].field);
                }
            }
            return callbackFn(false, fieldList);
        });
    };

```


## cdr

    The calls method is used for when you need to pull your entire call data within
    a given time range. It comes with some filers which allows for some calls filtering.
    
    Requesting data requires your to supply the fields you would like to return in you request.
    The reporting API allow to request field definition as shown below


// Response
{
    GET https://api-106.dxi.eu/reporting.php?token=4f2430df58c1e1875addafc7d41d661f33a2ea02&method=cdr&format=json&fields=callid,urn,qid,qnm,qtype,qtable,cid,cnm,cres,aid,anm,dsid,ocid,ocnm,ddi,cli,flags,carrier,ctag,tag,dest,dcode,ctype,dtype,sms_msg,sec_dur,sec_wait,sec_wrap,sec_ring,sec_que,tm_init,tm_answ,tm_disc,oc_sale,oc_cmpl,oc_cmpli,oc_ncmpl,oc_dmc,cost_cust,bill_cust,bill_dur,ivr_key,sec_key,orig_qnm,bk_last_ddi&range=1493593200:1509580799

    "success": true,
    "total": 45,
    "list": [
        {
            "field": "callid",
            "name": "Call ID",
            "grouping": 1,
            "numeric": 0,
            "description": "the individual call id."
        },
        {
            "field": "urn",
            "name": "URN",
            "grouping": 0,
            "numeric": 0,
            "description": "the unique customer record number."
        },
        {
            "field": "qid",
            "name": "Queue ID",
            "grouping": 0,
            "numeric": 0,
            "description": "the queue id."
        },
        {
            "field": "qnm",
            "name": "Queue Name",
            "grouping": 0,
            "numeric": 0,
            "description": "the queue name."
        },
        ...
        ]
}        

```