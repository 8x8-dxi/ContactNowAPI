# ![8x8 ContactNow API](https://raw.githubusercontent.com/8x8-dxi/ContactNowAPI/master/images/8x8ContactNow.png) API

This technical documentation is aimed at the very technical minded person/developers willing
to use the ContactNow API v2 for data manipulation in and out of the 8x8 ContactNow Dialler (Dialer *for the US*!).

**It is worth noting that the use of 8x8 ContactNow API for manipulating data on your dialling list/Campaign
comes at your own risk! You MUST understand the national/local legal implications governing
the frequency/rate at which you dial a specific customer record**

# Table of Contents
**[8x8 ContactNow API v2](#8x8-contactnow-api-v2)**<br>
**[API Domains](#api-domains)**<br>
**[API Endpoints Scripts](#api-endpoints-scripts)**<br>
**[High level API diagram](#high-level-api-diagram)**<br>
**[API Endpoint Request Format](#api-endpoint-request-format)**<br>
**[High level API diagram](#high-level-api-diagram)**<br>
**[APIs](#apis)**<br>
**[PHP Client Script](#php-client-script)**<br>

### 8x8 ContactNow API v2
Version 2 of the API is built on a legacy web service which is still currently
being used by 99.9% of our clients. API v2 is not that REST*ful* as you would explicitly need to 
pass the endpoint as script names, actions and methods as url params in order to send your request. But
you don't have to worry too much about that as this documentation comes with a PHP and Javascript/NodeJS
client script to help you CRUD the API.

This version of the API is here to stay for the foreseeable future as its still in production and heavily supported 
by our support and product team.

That said, we have a new kid on the block and it's... API v3! Version 3 is the next big thing 
but it's currently geared towards Agent actions once logged in to the dialler. So, for the purpose of keeping 
things simple we will focus on API v2.

## Accessing the API
To get access to the 8x8 ContactNow API you will need an 8x8 ContactNow Contact Centre (center *for the US*!) account.
Once you have a contact centre, you can request API credentials from our support team.


## API Domains
There are various API domains of the 8x8 ContactNow API depending on the login URL you use when logging
on to your Contact Centre. Please refer to the table below to help you determine your API BASE URL. Using the 
wrong API base URL will result to invalid result.

Web Login URL | API BASE URL | Location
----------|---------|---------
https://app.easycontactnow.com | [https://api-106.dxi.eu/](https://api-106.dxi.eu/token.php?action=get&username=YOUR-API-USERNAME&password=YOUR-API-PASSWORD) | EU and the rest of the world
https://app.contactnow.8x8.com | [https://api.contactnow.8x8.com/api/](https://api.contactnow.8x8.com/api/token.php?action=get&username=YOUR-API-USERNAME&password=YOUR-API-PASSWORD) | United States


## API Endpoints Scripts
As earlier stated, version 2 of the API does not fully implement the concept of
a REST*ful* web service in the sense that you would have to append a script to the base url which defines
a certain set of methods and actions.

### API Endpoint Request Format 
`https://[API BASE URL]/scriptName.php?token=MY-TOKEN&method=method&format=json&...`

The API will by default return a JSON response for some request. If you speak XML and wish to consume the
API in XML format then you need to supply `&formart=xml` to your requests.   


## High level API diagram
The diagram gives you some understanding of the high level design of the various endpoint and how they interact with 
some element of the system.
![API Diagram](https://raw.githubusercontent.com/8x8-dxi/ContactNowAPI/master/images/High-level-API-diagram.png)

### APIs

1. #### [token.php](https://github.com/8x8-dxi/ContactNowAPI/blob/master/TOKEN.md) See Token documentation

    The token endpoint provides access to the 8x8 ContactNow API interface.
    This endpoint does not implement any methods but comes with two exposed actions.
    The token generated using this endpoint will be required when running any other API requests 
    using other endpoints.

2. #### [ecnow.php](https://github.com/8x8-dxi/ContactNowAPI/blob/master/ECNOW.md) See ecnow documentation

    This endpoint is the most widely used API. It allows dynamic data/record feed in/out of
    the campaign database. In addition, it can pass records to the dialler depending on your request instructions.

3. #### [database.php](https://github.com/8x8-dxi/ContactNowAPI/blob/master/DATABASE.md) See database documentation

    The database endpoint allows a user to dynamically configure various components of the contact centre.
    This include the ability to manipulate campaign settings, Campaign, Queue, Agents and assignments between different entities.

4. #### ajax.php

    The Ajax API is used for retrieving live statistics of your contact centre activities such as
    status board for agents, outgoing, incoming calls and more.

5. #### [reporting.php](https://github.com/8x8-dxi/ContactNowAPI/blob/master/REPORTING.md)

    Please click [here](https://github.com/8x8-dxi/ContactNowAPI/blob/master/REPORTING.md) for reporting
    documentation.

6. #### recording.php

    This endpoint allows you to pull down (download) your call recordings (if any are available).

7. #### click2dial.php

    For customers wanting to initiate a call from a web site to an online agent on the dialler.

8. ~~agent.php~~ 
    
    The agent endpoint allows an agent to manipulate live calls and and make status changes. To use this endpoint 
    you would have to constantly poll to receive any state change. This is where API v3 comes in as it implements
    a WebSocket layer to aid a push notification mechanism for when it detects a change in status.

## PHP Client Script
This part of the documentation aims to simplify CRUD*ing* the API. The php client script
contains functions which act as API wrapper functions. The aim is to save you some valuable
time when trying to understand the semantics of the core API.

The wrapper function which can be found in [include/api-wrappers.php](https://github.com/8x8-dxi/ContactNowAPI/blob/master/includes/api-wrappers.php) of this repo.

The script requires initialisation of the following Global Variable which are parsed in 
wrapped functions;

#### Mandatory Variables
```php
/*
 * Supply the API BASE URL see https://github.com/8x8-dxi/ContactNowAPI#api-domains
 */
define('API_H', '[API BASE URL]');
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

```

Some of the functions in the script are helper functions hence we will focus on the 
main ones you are likely to use

Wrapper Functions | Description | Output
------------------|-------------|---------------
get_auth_token() | Request a token from direct the API. Data returned is dynamically stored in a local file for lookup. | Returns array of token data. See all possible return data [here](https://github.com/8x8-dxi/ContactNowAPI/blob/master/TOKEN.md). None parameter is explicitly passed but requires the global API_H, API_U, API_P variables
getTokenValue() | The function is directly called by the main wrapper functions before posting request. It first check if a token was already generated and then tried to validate the expiration time with your server local time. It decided whether or not to request a fresh to by calling `get_auth_token()`| Returns the token string
post_request() | Used by all function to make a curl request to the APIs. | Array data returned from the API
api_ecnow($method, $action, $data = array()) | ecnow api alias |n
api_db($method, $action, $data = array()) | Alias for database API |
api_reporting($method, $options = array()) | Alias for reporting |
api_agent($action, $options = array()) | Alias for agent api | 
api_ajax($method, $options = array()) | ajax api alias |





## ![Back to Index](https://github.com/8x8-dxi/ContactNowAPI/wiki)