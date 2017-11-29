
# Table of Contents
**[outcomecodes](#outcomecodes)**<br>


### outcomecodes
Outcome codes are used by Agents or the System for setting an outcome for a call.
They are also determine whether a record should remain on the dialler for dialling
depending on the *complete* value (Y/N). Below.


Manipulate disposition / outcome codes.

Filters

	uid: match outcome<
	mode: set to system to return just the internal system outcome codes.

Fields

	outcome - The outcome id
	description - Outcome label
	dmc - Y | N
	sale - Y | N
	complete - Y | N
	answerphone - Y | N

Defaults fields

	<outcome>0</outcome>
	<description />
	<dmc />
	<sale />
	<complete />
	<answerphone>0</answerphone />

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