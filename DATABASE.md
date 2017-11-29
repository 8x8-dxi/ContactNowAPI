
# Table of Contents
**[outcomecodes](#outcomecodes)**<br>


### outcomecodes
Outcome codes are used by Agents or the System for setting an outcome for a call.
They are also determine whether a record should remain on the dialler for dialling
depending on the *complete* value (Y/N). Below.


Manipulate disposition / outcome codes.

Filters<br>
	uid: match outcome<br>
	mode: set to system to return just the internal system outcome codes.<br>

Fields<br>
	outcome - The outcome id<br>
	description - Outcome label<br>
	dmc - Y | N<br>
	sale - Y | N<br>
	complete - Y | N<br>
	answerphone - Y | N<br>

defaults

	<outcome>0</outcome><br>
	<description /><br>
	<dmc /><br>
	<sale /><br>
	<complete /><br>
	<answerphone>0</answerphone /><br>

Example for reading Outcomecode<br>

Using the *php* [api-wrappers](https://github.com/8x8-dxi/ContactNowAPI/blob/master/includes/api-wrappers.php) script.

```php

$myOutcomecode= api_db('outcomecodes', 'read');
print_r($myOutcomecode);

/**
 Array
(
    [success] => 1
    [total] => 4
    [list] => Array
        (
            [0] => Array
                (
                    [outcome] => 510456
                    [description] => Sales Completed
                    [complete] => Y
                    [dmc] => Y
                    [sale] => Y
                    [do_not_call] => N
                    [answerphone] => 0
                )

            [1] => Array
                (
                    [outcome] => 501638
                    [description] => Need more information
                    [complete] => N
                    [dmc] => Y
                    [sale] => Y
                    [do_not_call] => N
                    [answerphone] => 0
                )
            //[2]...    
 */
```


## ![Back to Index](https://github.com/8x8-dxi/ContactNowAPI/wiki)