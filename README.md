# ![8x8 ContactNow API](https://raw.githubusercontent.com/8x8-dxi/ContactNowAPI/master/images/8x8ContactNow.png) API

This technical documentation is aimed at the very technical minded person/developers willing
to use the ContactNow API v2 for data manipulation in and out of the 8x8 ContactNow Dialler (Dialer US!).

**It is worth noting that the use of 8x8 ContactNow API for manipulating data on your dialling list/Campaign
comes at your own risk! You MUST understand the national/local legal implications governing
the frequency/rate at which you dial a specific customer record**

### 8x8 ContactNow API v2
The version 2 of the API is built on a legacy RESTful web service which is still currently
being used by 99.9% of our clients. This is to assure you that it is still here to stay for
a long while! It is also still being heavily support by our product team hence why I have 
decided to spend some time to documenting it to the very best of my knowledge. 

That said, we have a new kid on the block and it's... API v3! Version 3 is the next big thing 
but it's currently geared towards Agent actions once logged in to the dialler. So, 
for the purpose of keeping things simple we will focus on API v2.

### Accessing the API
To get access to the 8x8 ContactNow API you will need an ContactNow account in the form of a Contact Centre (center of the US!).
Once you have a contact centre you can request for an API credentials which is tied to your contact centre.

### API Domains
There are various domains of the 8x8 ContactNow API depending on the base URL you use when logging
on to your Contact Centre. To help you determine your API URL refer to the table below

https://app.easycontactnow.com

Login URL | API URL | Location
----------|---------|---------
https://app.easycontactnow.com | [https://api-106.dxi.eu/](https://api-106.dxi.eu/token.php?action=get&username=&password=) | United Kingdom
https://app.contactnow.8x8.com | [https://api.contactnow.8x8.com/api/](https://api.contactnow.8x8.com/api/token.php?action=get&username=&password=) | United States




#Table of Contents
[Authentication & Token]()
Initialise the following API credentials in includes/api-wraper.php

define('API_H', 'https://api-106.dxi.eu');
define('API_U', 'API USER NAME');
define('API_P', 'API PASSORD');
define('CAMPAIGN', CID);