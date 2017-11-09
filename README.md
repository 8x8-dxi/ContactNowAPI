
# ![ContactNow Logo Here](https://github.com/8x8-dxi/ContactNowAPI/tree/master/images/ContactNow-Contact-Centre-Software.svg) API

This technical document is intended for CRUD*ing* the ContactNow API. The document
will fucus on using a PHP wrapper to describe how you can achieve posting/reading
data from the ContactNow API

A ContactNow account is required for using the API. Once you have a contact centre
you can request for an API credentials which is tied to your contact centre.

The API credential comes with a username and password as it required for POST*ing*
and GET*ing* data.

#Table of Contents
[Generating a authentication token]()
Initialise the following API credentials in includes/api-wraper.php

define('API_H', 'https://api-106.dxi.eu');
define('API_U', 'API USER NAME');
define('API_P', 'API PASSORD');
define('CAMPAIGN', CID);