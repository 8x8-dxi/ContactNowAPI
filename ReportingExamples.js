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


    var endpoint = 'https://api-106.dxi.eu/reporting.php';
    
    // Change the datetime
    // Get the epoch time from the start and stop time
    var tstartObject = new Date("2017-11-14 00:00:00"), 
        tStopObject = new Date("2017-11-14 23:59:59"),
        tstart = tstartObject.getTime()/1000,
        tstop = tStopObject.getTime()/1000;

    var fetchCDR = function () {

        var options = {
            method: 'GET',
            url: endpoint,
            qs: {
                token: '',// Toke will be intitialised in getToken Function
                method: 'calls',
                fields: 'qtable,qtype,qid,qnm,aid,anm,dsid,dsnm,urn,ddi,cli,tid,tnm,has_aid,PT10M,PT15M,PT30M,PT1H,P1D,P1W,P1M,date,day,hour,min_10,dest,dcode,ctype,dtype,cres,is_mob,nc_all,nc_in,nc_out,nc_out_all,nc_sms_out,nc_man,nc_tpt,nc_dtpt,nc_wait,nc_wrap,nc_con,nc_ans,nc_ans_in,nc_ans_man,nc_que,nc_ans_le,nc_ans_gt,nc_que_le,nc_que_gt,sec_dur,sec_talk_all,sec_talk,sec_tpt,sec_wait,sec_wrap,sec_call,sec_ans,ocid,ocnm,ocis_cmpl,ocis_cmpli,ocis_sale,ocis_dmc,oc_abdn,oc_cbck,oc_ama,oc_amd,oc_dead,oc_noansw,oc_sale,oc_cmpl,oc_cmpli,oc_ncmpl,oc_dmc,cost_cust,bill_cust,bill_dur,callid_max',
                range: tstart + ':'+tstop, // Start and stop time in UTC
                groupby: 'qtable,qnm', // Group the data by Campaign table and queue name
                agent: 503314,// Notice I am filtering by agent ID which should return every call handled by this agent only
                format: 'json'
            },
            headers:
                    {'postman-token': '3dc507a9-b14a-7b91-b103-eafc74b6a85e',
                        'cache-control': 'no-cache'}
            };

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
              console.info(JSON.parse(body));
              
              // Console log
              /*
                    {
                    "success": true,
                            "total": 7,
                            "list": [
                            {
                            "ccid": "315",
                                    "ccnm": "ECONStaff",
                                    "cid": "500650",
                                    "cnm": "Willtest",
                                    "qtable": "APItestcampaign",
                                    "qtype": "outbound",
                                    "qid": "719718",
                                    "qnm": "API-DDI",
                                    "aid": "503314",
                                    "anm": "Christian Davies",
                                    "dsid": "0",
                                    "dsnm": null,
                                    "urn": "0",
                                    "ddi": "01234567890",
                                    "cli": "01234567890",
                                    "tid": "2000059307",
                                    "tnm": "Team Coco",
                                    "has_aid": "1",
                                    "PT10M": "2017-08-21_10-30-00",
                                    "PT15M": "2017-08-21_10-30-00",
                                    "PT30M": "2017-08-21_10-30-00",
                                    "PT1H": "2017-08-21_10-00-00",
                                    "P1D": "2017-08-21",
                                    "P1W": "2017-08-20",
                                    "P1M": "2017-08",
                                    "date": "2017-08-21",
                                    "day": "Monday",
                                    "hour": "10",
                                    "min_10": "30",
                                    "dest": "Landline Standard",
                                    "dcode": "442",
                                    "ctype": "out",
                                    "dtype": "man",
                                    "cres": "TPT",
                                    "is_mob": "0",
                                    "nc_all": 10,
                                    "nc_in": 0,
                                    "nc_out": 0,
                                    "nc_out_all": 0,
                                    "nc_sms_out": 0,
                                    "nc_man": 10,
                                    "nc_tpt": 0,
                                    "nc_dtpt": 0,
                                    "nc_wait": 10,
                                    "nc_wrap": 10,
                                    "nc_con": 8,
                                    "nc_ans": 0,
                                    "nc_ans_in": 0,
                                    "nc_ans_man": 8,
                                    "nc_que": 0,
                                    "nc_ans_le": 0,
                                    "nc_ans_gt": 0,
                                    "nc_que_le": 0,
                                    "nc_que_gt": 0,
                                    "sec_dur": 25.597082138062,
                                    "sec_talk_all": 25.59,
                                    "sec_talk": 0,
                                    "sec_tpt": 25.59,
                                    "sec_wait": 0,
                                    "sec_wrap": 0,
                                    "sec_call": 28,
                                    "sec_ans": 0,
                                    "ocid": "101",
                                    "ocnm": "Answer Machine (Agent)",
                                    "ocis_cmpl": "0",
                                    "ocis_cmpli": "0",
                                    "ocis_sale": "0",
                                    "ocis_dmc": "0",
                                    "oc_abdn": 0,
                                    "oc_cbck": 0,
                                    "oc_ama": 4,
                                    "oc_amd": 0,
                                    "oc_dead": 0,
                                    "oc_noansw": 2,
                                    "oc_sale": 0,
                                    "oc_cmpl": 0,
                                    "oc_cmpli": 0,
                                    "oc_ncmpl": 10,
                                    "oc_dmc": 0,
                                    "cost_cust": 0.008,
                                    "bill_cust": 0.008,
                                    "bill_dur": 30,
                                    "callid_max": 7612257798
                            },
                            ...
                            ]
                    }
                    */
            });
        });
    };


