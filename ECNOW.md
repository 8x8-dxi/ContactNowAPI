This endpoint is the most widely used API. It allows dynamic data/record feed in/out
of a selected campaign and can manipulate the corresponding DXI record based on certain data properties.

Before delving deep into this API let's try to understand the important concept and 
components of the 8x8 ContactNow platform, without the overview knowledge you will
find it challenging.

## The terminologies
Some of the terms used are sometimes construed and loosely used and I will explain!

### In order of relevance

1. ### Campaign (Not the actual __campaign__ notation)

    Is a database table holding a datasets of records grouped by Datasets

2. ### Dataset

    Is as a subset of records within a Campaign. It is a child of a Campaign. 
    Datasets have three main properties which are used to control status and dial order of the its records as children.

    #### Properties

    1. Status

        Status defines the state of every records within a dataset.

        1. *HOLD*

            The dataset and all its records will not be enlisted on the dialler for dialling
        2. *LIVE*

            All records in the dataset are on the dialler ready to start dialling.
            [Agents](#queues) assigned to this dataset via [Queues](#queues) **MUST** logged in and in "Wait"
            status for the records to start dialling.

        3. *EXPIRED*

    2. Priority
        
        This defines the order (0 to 90) in which records in a Campaign are dialled.
        A dataset with a priority of 90 being the highest will take priority over
        any other dataset *WITHIN* the same Campaign.

        NOTE: 
            Dataset priorities does not override other Datasets on a different Campaign regardless
            its priority.
    
    3. Queues
        See [Queues](#queues)
    
    ![CampaignAndDatasetAnalogy](https://raw.githubusercontent.com/8x8-dxi/ContactNowAPI/master/images/CampaignsTables&Datasets.png)

3. ### Queues

    Queues are defined as type of communication channels that can either be Inbound or Outbound.
    They are used to manage inflow and outflow of **Campaigns** be it Inbound or Outbound.

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

    Outbound Queues type is tightly coupled with Agents and Datasets. 
    Inbound Queue type is tightly coupled with Campaign Tables and Agents.
    Outbound Queues must be assigned to a Campaign if 

4. ### Outcomes / Outcomecodes
4. ### Agents



##### List of Methods
*ecnow_datasets*<br>

    This methods is used to manipulate datasets within a specified campaign

*ecnow_records*<br>
*campaign_tables*<br>
*campaign_fields*<br>
*ecnow_outcomecodes*<br>

##### List of Action
*create*<br>
*read*<br>
*update*<br>
*delete*<br>


## ![Back to Index](https://github.com/8x8-dxi/ContactNowAPI/wiki)