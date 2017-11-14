This endpoint is the most widely used API. It allows dynamic data/record feed in/out
of a selected campaign and can persist the DXI database based on certain properties.

Before delving deep into this API let's try to understand the important concept and 
components of the 8x8 ContactNow platform, without the overview knowledge you will
find it challenging.

## The terminologies
Some of the terms used are sometimes construed and loosely used and I will explain!

##### In order of relevance

1. Campaign (Not the actual __campaign__ notation)

    Is a database table holding a datasets of records grouped by Datasets


2. Dataset

    Is as a subset of records within a Campaign
    ![CampaignAndDatasetAnalogy](https://raw.githubusercontent.com/8x8-dxi/ContactNowAPI/master/images/CampaignsTables&Datasets.png)

3. Queues

    Queues are defined as type of communication channels that can either be Inbound or Outbound.
    They are used to manage inflow and outflow of **Campaigns** be it Inbound or Outbound.

    There are various types of Queues with multiple inherited properties which defines
    the behaviours of a queue.
    ##### Queue Type
    **Inbound**<br>
    **Outbound**<br>
    **IVR**<br>
    **Broadcast**<br>
    **Message**<br>
    **SMS Outbound**<br>
    **SMS Broadcast**<br>

    Outbound Queues type is tightly coupled with Agents and Datasets. 
    Inbound Queue type is tightly coupled with Campaign Tables and Agents.
    Outbound Queues must be assigned to a Campaign if 

4. Outcomes / Outcomecodes
4. Agent



##### List of Methods
*ecnow_datasets*<br>

    This methods is used read and manipulate datasets within a specified campaign
    The 
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