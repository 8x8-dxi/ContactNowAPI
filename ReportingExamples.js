/*
    Created by Christian Augustine
    ----
    Reporting API NodeJS client script
*/


    var request = require("request");
    var BASE_API_URL = "",// Get this from https://github.com/8x8-dxi/ContactNowAPI#api-domains
    _APIUSERNAME = '', // Initialise your API Username
    _APIPASSWORD = ''; // You API Pawword

    /**
     * 
     * @param {Function} callbackFunction
     * @returns {undefined}
     */
    var getToken = function (callbackFunction) {
        var options = {
            method: 'GET',
            url: 'https://'+BASE_API_URL+'/token.php',
            qs: {
                action: 'get',
                username: _APIUSERNAME,
                password: _APIPASSWORD
            },
            headers:{
                'postman-token': 'e4e86887-7ee0-5daf-43b6-1da6dbbf70ea',
                'cache-control': 'no-cache'
            }
        };

        request(options, function (error, response, body) {
            if (error) {
                //throw new Error(error);
                return callbackFunction(true, error);
            }
            // You should really store the token on a local redis server to prevent
            // requesting token when it's not expired. See https://github.com/8x8-dxi/ContactNowAPI/blob/master/TOKEN.md for more info
            return callbackFunction(false, JSON.parse(body));
        });
    };


    var endpoint = 'https://api-106.dxi.eu/reporting.php',
        startDate = new Date("2017-11-14 00:00:00").getTime();
        stopDate = new Date("2017-11-14 23:59:59").getTime();

    var requestOptions = {
        method: 'GET',
        url: endpoint,
        qs: {
            action: 'fields',
            token: '',
            method: 'calls',
            format: 'json'
        }
    };

    /**
     * Fetch field list for calls method
     * @param {Function} callbackFn
     * @returns {undefined}
     */
    var getFields = function (callbackFn) {
        var fieldList = [];
        request(requestOptions, function (error, response, body) {
            if (error) {
                return callbackFn(true, error);
                //throw new Error(error);
            }
            var Fields = JSON.parse(body);
            if (Fields && Fields.success && Fields.total > 0) {
                for (let i = 0, l = Fields.list.length; i < l; ++i) {
                    fieldList.push(Fields[i].field);
                }
            }
            return callbackFn(false, fieldList);
        });
    };


    var fieldResponse = function (err, fields){
        if (err || !fields.lenght){
            throw new Error("Unable to retrieve fields");
        }
        requestOptions.qs.fields = fields.join();
        requestOptions.qs.range = startDate + :':'+stopDate;
    };

    var tokenResponse = function(err, tokenData){
        if(err || !tokenData.success || !tokenData.token){
            throw new Error("Unable to retrieve token data");
        }
        requestOptions.qs.token = tokenData.token;
        return getFields(fieldResponse);
    };

    var fetchCall = function () {
        getToken(tokenResponse);
    };


