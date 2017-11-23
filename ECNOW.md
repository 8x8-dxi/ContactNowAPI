This endpoint is the most widely used API. It allows dynamic data/record feed in/out
of a selected campaign and can manipulate the corresponding DXI record based on certain data properties.

Before delving deep into this API let's try to understand the important concept and 
components of the 8x8 ContactNow platform; without the overview knowledge you will
find it challenging.

## The terminologies
Some of the terms used are sometimes misconstrued and loosely used and I will explain!

### In order of relevance

1. ### Campaign (Not the actual __campaign__ notation)

    Is a database table holding one or multiple datasets of records. These datasets and their records 
    can only dial from this one campaign. 

2. ### Dataset

    Is as a subset of records within a Campaign. It is a child of a Campaign. 
    Datasets have three main properties which are used to control status and dial order of the its records as children.

    #### Properties

    1. Status

        Status defines the state of every record within a dataset.

        1. *HOLD*

            The dataset and all of its records will not be enlisted on the dialler for dialling.
        2. *LIVE*

            All records in the dataset are on the dialler ready to start dialling.
            [Agents](#queues) assigned to this dataset via [Queues](#queues) **MUST** be logged in and in "Wait"
            status for the records to start dialling.

        3. *EXPIRED*
            All records have been expired within a dataset and are no longer available to be dialled. 
            You will also not be able to edit or reopen the record once expired. 
            
    2. Priority
        
        This defines the order (0 to 90) in which records in a Campaign are dialled.
        A dataset with a priority of 90 (the highest possible priority) will take priority over
        any other dataset *WITHIN* the same Campaign.

        NOTE: 
            Dataset priorities do not affect other Datasets that are on a different Campaign regardless
            of its priority.
    
    3. Queues
        See [Queues](#queues)
    
    ![CampaignAndDatasetAnalogy](https://raw.githubusercontent.com/8x8-dxi/ContactNowAPI/master/images/CampaignsTables&Datasets.png)

3. ### Queues

    Queues are defined as type of communication channels, the types of queues you can use are 
    listed below.
    They are used to manage inflow and outflow of **Campaigns** be it Inbound, Outbound, ivr 
    or broadcast.

    There are various types of Queues with multiple inherited properties which defines
    the behaviours of a queue.
    ##### Queue Types
    *inbound*<br>
    *outbound*<br>
    *ivr*<br>
    *broadcast*<br>
    *message*<br>
    *sms_out*<br>
    *sms_broadcast*<br>

    Outbound Queues are tightly coupled with Agents and Datasets. 
    Inbound Queues are tightly coupled with Campaign Tables and Agents.
    Outbound Queues must be assigned to a specific Campaign.  

4. ### Outcomes / Outcomecodes

    Outcomes are a very important part of the dialler as they are not just used as flags for call outcomes but also play
    a very important role of deciding how records behaves when loaded into the dialler.
    
    Typically outcomes have two main property which are 

    **Complete** indicates if a records should be removed from the dialling list. and 
    **incomplete** is the opposite of **complete** 



##### List of Methods
*ecnow_datasets*<br>

    These methods are used to manipulate datasets within a specified campaign. You can call this method
    when you which to change the status 

*campaign_tables*<br>

    This method is used for 
*campaign_fields*<br>

    
*ecnow_records*<br>

    
*ecnow_outcomecodes*<br>

##### List of Actions
*create*<br>
*read*<br>
*update*<br>
*delete*<br>


## ![Back to Index](https://github.com/8x8-dxi/ContactNowAPI/wiki)