Please [read the token endpoint](https://github.com/8x8-dxi/ContactNowAPI/blob/master/TOKEN.md) if you haven't already done so.

In this part of the documentation I will aim to explain how you would pull down data
generated from your Contact Centre using a list of exposed methods. 

Please note that all request send to this endpoint will not include any customer information 
aside from the customer unique identifiers (UID) and the phone numbers of the customer record. 
This means that customers records stored on the [ECN Database](https://github.com/8x8-dxi/ContactNowAPI#api-endpoint-request-format)
has to be requested using the [ecnow.php endpoint](https://github.com/8x8-dxi/ContactNowAPI/blob/master/ECNOW.md).



