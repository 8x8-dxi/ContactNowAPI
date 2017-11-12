# ![8x8 ContactNow API](https://raw.githubusercontent.com/8x8-dxi/ContactNowAPI/master/images/8x8ContactNow.png) API

This technical documentation is aimed at the very technical minded person/developers willing
to use the ContactNow API v2 for data manipulation in and out of the 8x8 ContactNow Dialler (Dialer *for the US*!).

**It is worth noting that the use of 8x8 ContactNow API for manipulating data on your dialling list/Campaign
comes at your very own risk! You MUST understand the national/local legal implications governing
the frequency/rate at which you dial a specific customer record**

# Table of Contents
**[8x8 ContactNow API v2](#8x8-contactnow-api-v2)**<br>
**[API Domains](#api-domains)**<br>
**[API Endpoints Scripts](#api-endpoints-scripts)**<br>
**[API Endpoint Request Format](#api-endpoint-request-format)**<br>
**[API Scripts Name](#api-scripts-name)**<br>
**[A high level API diagram](#a-high-level-api-diagram)**<br>
**[PHP Client Script](#php-client-script)**<br>

### 8x8 ContactNow API v2
The version 2 of the API is built on a legacy web service which is still currently
being used by 99.9% of our clients. The API v2 is not that REST*ful* as you would explicitly need to 
pass the point as script names, actions and methods as url params in order to send you request. But
you don't have to worry too much about that as this documentation comes with a PHP and Javascript/NodeJS
client script to help you CRUD the API.

This version of the API is here to stay for a long while as its still in production and heavily supported 
by our support and product team.

That said, we have a new kid on the block and it's... API v3! Version 3 is the next big thing 
but it's currently geared towards Agent actions once logged in to the dialler. So, for the purpose of keeping 
things simple we will focus on API v2.

## Accessing the API
To get access to the 8x8 ContactNow API you will need an 8x8 ContactNow Contact Centre (center *for the US*!) account.
Once you have a contact centre, you can request for an API credentials from our support team.


## API Domains
There are various API domains of the 8x8 ContactNow API depending on the login URL you use when logging
on to your Contact Centre. To help you determine your API BASE URL refer to the table below. Using the 
wrong API base URL will result o invalid result.

Web Login URL | API BASE URL | Location
----------|---------|---------
https://app.easycontactnow.com | [https://api-106.dxi.eu/](https://api-106.dxi.eu/token.php?action=get&username=YOUR-API-USERNAME&password=YOUR-API-PASSWORD) | EU and the rest of the world
https://app.contactnow.8x8.com | [https://api.contactnow.8x8.com/api/](https://api.contactnow.8x8.com/api/token.php?action=get&username=YOUR-API-USERNAME&password=YOUR-API-PASSWORD) | United States


## API Endpoints Scripts
As earlier stated the version 2 of the API does not fully implement the concept of
a REST*ful* web service in the sense that you would have to append a script to the base url which defines
a certain set of methods and actions.

### API Endpoint Request Format 
`API BASE URL/scriptName.php?`

The API will by default return a JSON response for every request. If you speak XML and wish to consume the
API in XML then you need to append `&formart=xml` to your requests.   

This section aims to list the various endpoints and scripts that can be used for certain
type of data manipulation. 

### API Scripts Name

1. #### token.php

    The token endpoint provides access to the 8x8 ContactNow API interface.
    This endpoint does not implement any methods but come with two exposed actions

    ##### Actions
    **get** : Get a new token if one does not already exist.<br>
    **validate** : check if a token is valid or expired.

    ##### Example
    For the EU region
    ```javascript
    GET https://api-106.dxi.eu/token.php?action=get&username=YOUR-API-USERNAME&password=YOUR-API-PASSWORD

    // Validate a token
    GET https://api-106.dxi.eu/api/token.php?action=validate&username=YOUR-API-USERNAME&password=YOUR-API-PASSWORD
    ```
    For the US region
    ```javascript
    // get a token
    GET https://api.contactnow.8x8.com/api/token.php?action=get&username=YOUR-API-USERNAME&password=YOUR-API-PASSWORD

    // Validate a token
    GET https://api.contactnow.8x8.com/api/token.php?action=validate&username=YOUR-API-USERNAME&password=YOUR-API-PASSWORD
    ```

    ```javascript
        
    // A failed API response will return the following JSON object
    {
        success: false,
        error: "Authentication failure"
    }

    // A successful token request

    {
        success: true,
        token: "13036ea7949b5f5bea79ca1d4c76cc2d56e2d9e8",
        expire: 1510514548
    }
    
    **Note**
    **A token lifespan is 11 hours and 59 seconds**
    The *expire* key of which value is the Unix timestamp the token will expire.
    Your returned token should be stored locally until you need to fetch a new one
    ```


2. #### ecnow.php

    This endpoint is the most widely used API. It allows dynamic data/record feed in/out of
    the campaign database and the dialler.
    
    ##### Methods
        **ecnow_datasets**
        **ecnow_records**
        **campaign_tables**
        **campaign_fields**
        **ecnow_outcomecodes**


3. #### database.php

    The database endpoint enable a user to dynamically configure various components of the contact centre.
    This include the ability to manipulate campaign settings, Campaign/Queue assignments to Agents and many more.

4. #### ajax.php

    The Ajax API is used for retrieving live statistics of your contact centre activities such as
    status board agents, outgoing/incoming calls and more.

5. #### reporting.php

    This endpoint handles your reporting need

6. #### recording.php

    This endpoint allow you to pull down your call recording (if any)

7. #### click2dial.php

    For customers wanting to initiate a call from a web site to an online agent on the dialler.

8. ~~agent.php~~ 
    
    The agent endpoint allows an agent to manipulate live calls and status change. To use this endpoint 
    you would have to constantly poll to receive any state change. This is where API v3 comes in as its implement
    a WebSocket layer to aid a push notification mechanism for when it detect a change in status.



## A high level API diagram
![API Diagram](https://raw.githubusercontent.com/8x8-dxi/ContactNowAPI/master/images/High-level-API-diagram.png)


## PHP Client Script
This part of the documentation will aim to simplify CRUD*ing* the API. The php client script
contains functions which act as API wrappers functions. The aim is to save you some valuable
time of trying to understand the semantics of the core API.


Initialise the following API credentials in includes/api-wraper.php

define('API_H', 'https://api-106.dxi.eu');
define('API_U', 'API USER NAME');
define('API_P', 'API PASSORD');
define('CAMPAIGN', CID);