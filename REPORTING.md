Please [read the token endpoint](https://github.com/8x8-dxi/ContactNowAPI/blob/master/TOKEN.md) if you haven't already done so.

In this part of the documentation I will aim to explain how you would consume data
generated from your Contact Centre using a list of exposed methods. 

Please note that all response from this endpoint will not include any customer information 
aside from the customer unique identifiers (UID) and the phone numbers of the customer record. 

This means that customer records stored on the [ECN Database](https://github.com/8x8-dxi/ContactNowAPI#high-level-api-diagram)
has to be requested using the [ecnow.php endpoint](https://github.com/8x8-dxi/ContactNowAPI/blob/master/ECNOW.md).


## Methods

### calls
    
    The CDR method is used for when you need to pull your entire call data within
    a given time range. It comes with some filers which allows for some calls filtering.
    
    Requesting data requires your to supply the fields you would like to return in you request.
    The reporting API allow to request field definition as shown below

#### Fields and definitions

Use a simple get request to get the list of fields

```javascript

GET https://[API BASE URL]/reporting.php?action=fields&method=calls&format=json&token=TOKEN-HERE

// Response
{
    "success": true,
    "total": 83,
    "list": [
        {
            "field": "ccid",
            "name": "Call Centre ID",
            "grouping": 1,
            "numeric": 0,
            "description": "the call centre id."
        },
        {
            "field": "ccnm",
            "name": "Call Centre Name",
            "grouping": 1,
            "numeric": 0,
            "description": "the call centre name."
        },
        {
            "field": "cid",
            "name": "Campaign ID",
            "grouping": 1,
            "numeric": 0,
            "description": "the campaign id."
        }
        ...
        ]
}        

```
 
### Filters and definitions

The following filters can be when requesting data using the calls method.

names | Description 
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





## cdr
