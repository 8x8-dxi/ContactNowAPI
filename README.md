# ![8x8 ContactNow API](https://raw.githubusercontent.com/8x8-dxi/ContactNowAPI/master/images/8x8ContactNow.png) API

This technical documentation is aimed at the very technical minded person/developers willing
to use the ContactNow API v2 for data manipulation in and out of the 8x8 ContactNow Dialler (Dialer *for the US*!).

**It is worth noting that the use of 8x8 ContactNow API for manipulating data on your dialling list/Campaign
comes at your very own risk! You MUST understand the national/local legal implications governing
the frequency/rate at which you dial a specific customer record**

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

### Accessing the API
To get access to the 8x8 ContactNow API you will need an 8x8 ContactNow Contact Centre (center *for the US*!) account.
Once you have a contact centre, you can request for an API credentials from our support team.


### API Domains
There are various API domains of the 8x8 ContactNow API depending on the login URL you use when logging
on to your Contact Centre. To help you determine your API BASE URL refer to the table below. Using the 
wrong API base URL will result o invalid result.

Web Login URL | API BASE URL | Location
----------|---------|---------
https://app.easycontactnow.com | [https://api-106.dxi.eu/](https://api-106.dxi.eu/token.php?action=get&username=YOUR-API-USERNAME&password=YOUR-API-PASSWORD) | United Kingdom
https://app.contactnow.8x8.com | [https://api.contactnow.8x8.com/api/](https://api.contactnow.8x8.com/api/token.php?action=get&username=YOUR-API-USERNAME&password=YOUR-API-PASSWORD) | United States


### API Endpoints/Scripts
As earlier stated the version 2 of the API does not fully implement the concept of
a REST*ful* web service in the sense that you would have to append a script to the base url which defines
a certain set of methods and actions.

###### Request format 
`API BASE URL/scriptName.php?`

This section aims to list the various endpoints and scripts that can be used for certain
type of data manipulation. 

#### Scripts (in other of importance)
1. token.php
    The token endpoint provides access to the 8x8 ContactNow API interface.
2. ecnow.php
    This endpoint is the mostly widely used API which allows dynamic data feed in/out of
    the dialler.
3. database.php
    The database endpoint designed for dialler and logic
4. ajax.php
5. reporting.php
6. recording.php
7. click2dial.php
8. ~~agent.php~~






#Table of Contents
[Authentication & Token]()
Initialise the following API credentials in includes/api-wraper.php

define('API_H', 'https://api-106.dxi.eu');
define('API_U', 'API USER NAME');
define('API_P', 'API PASSORD');
define('CAMPAIGN', CID);