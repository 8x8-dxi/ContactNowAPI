The token endpoint provides access to the 8x8 ContactNow API interface.
This endpoint does not implement any separate methods but comes with two exposed actions.

##### Actions
**get** : Get a new token if one does not already exist.<br>
**validate** : check if a token is valid or expired.

##### Example
For the EU region
```javascript
GET https://api-106.dxi.eu/token.php?action=get&username=YOUR-API-USERNAME&password=YOUR-API-PASSWORD
```
For the US region
```javascript
// get a token
GET https://api.contactnow.8x8.com/api/token.php?action=get&username=YOUR-API-USERNAME&password=YOUR-API-PASSWORD
```

## Token Response
```json

// A failed API response will return the following JSON object
{
    "success": false,
    "error": "Authentication failure"
}

// A successful token response
{
    "success": true,
    "token": "13036ea7949b5f5bea79ca1d4c76cc2d56e2d9e8",
    "expire": 1510514548
}
```
**Note**<br>
**A token is bound to the source IP. Attempting to use a token from a different IP will return the "Invalid token" error !**<br>
**A token lifespan is 11 hours and 59 seconds**<br>
The *expire* key which is a Unix timestamp indicates when the token will expire.
Your returned token should be stored locally until you need to fetch a new one <br>


```javascript
// Validate token request URL

GET token.php?action=validate&token=TOKEN-VALUE
```

```json
// A validation token request returns the following JSON Object
{
    "success": true,
    "expire": 1510515149
}

// An invalid token response
{
    "success":false,
    "error":"Invalid token"
}

// Expired token will return the following response.
{
    "success": false,
    "error": "Expired token",
    "expire": -1
}
```

## ![Back to Index](https://github.com/8x8-dxi/ContactNowAPI/wiki)