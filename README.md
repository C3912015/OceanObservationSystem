# OceanObservationSystem

####Install
Once downloaded from eclass, place the zip folder into the location of your PHP server and unzip.
When it has been unzipped, navigate into the folder titled code and open a terminal here. Run:

**script.sh**

which will change the permissions of all the files in this folder.
Once this is done, it can be accessed from a browser by going: 

**root/login.php**

where root is the php servers url. From here you can login and navigate the different pages.

####Login Module - James

####Sensor and User Management Module

An administrator can:  
**Remove a sensor** by entering ID of a sensor in the sensor management module under "Remove a Sensor".  
**Add a sensor** by entering the requested sensor information in the sensor management module under "Add a Sensor".  
**Add a user** by entering the requested user information under "Add User" in the user management module.  
**Remove a user** by entering the username of the user to be deleted under "Remove User" in the user management module.  
**Update a user** by entering the username of the user and then filling in any fields that are to be changed and leaving the unchanged fields empty under "Update User in the user management module"  
**Add a person** by entering the requested information under "Add Person" in the user management module.   
**Remove a person** by entering the person ID of the person to be deleted under "Remove Person" in the user management module.  
**Update a person** by entering the person ID of the person to be updated and then filling in any fields that are to be changed and leaving unchanged fields empty under "Update Person" in the user management module.  

####Subscribe Module:

Scientists are able to subscribe and unsubscribe to sensors with this module. At the top, two tables are displayed; The first being all the sensors, and the second being the sensors the current scientist is susbcribed to. To add a new sensor to your subscriptions type the sensor id of the sensor wanted underneath the heading Add a Subscription and click the button that says Add Subscription. To remove a subscription type the sensor id of the sensor you want to remove underneath the heading Remove a Subscription, and press the button that says Remove Subscription. 

####Uploading Module
A data curator can:  
**Upload audio data** by filling in the requested information under "Audio Information" in the upload module, then selecting "choose file" at the bottom, and selecting a .wav file, then clicking upload.  
**Upload image data** by filling in the requested information under "Image Information" in the upload module, then selecting "choose file" at the bottom, and selecting a .jpg or .jpeg file, the clicking upload.  
**Upload scalar data** by simply clicking "choose file" and choosing a .csv file, then clicking upload.  
All data IDs are generated by the upload process.  

####Search Module - James

####Data Analysis Module - Megan
Scientists are able to use this module to retrieve an analysis of the sensors they are currently subscribed to. 
There are two main parts to this module.
######1.	Generalization

The generalization is obtained by entering a Sensor ID and clicking Generalization(Roll Up)
When you hit the submit button, it will give you the Average, Maximum and Minimum respectively starting at the smallest time of day, 
then going to weeks, all the way up to years. The last Avg., Max, and Min it will give you will be over all the scalar data for that sensor. 
Note this will only work for scalar data, not images or audio.
######2.	Specialization

The specialization is obtained by entering a Sensor ID. From here you can choose how small you want to specialize the data to. 
You start by selecting a year, and then a quarter, and so on, until you are at the time type you want. 
Note that the times must decrease, and you cannot enter, for example, a day before entering a month. 

When you have the appropriate time level, click the Specialization (Drill Down) button.
This will display the Average, Maximum and Minimum respectivaley of your chosen sensor's scalar data.

