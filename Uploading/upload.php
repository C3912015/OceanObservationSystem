<!--This module will be used by data curators to upload images, 
audio recordings, and scalar measurements in batches,
after possible processing and annotations in the description field. 
Upload all the data stored in the user's local file system to the database. 
Curator can: 
  -upload scalar data in batches by csv file
  -upload image/audio
-->
<!-- http://www.htmlgoodies.com/beyond/php/article.php/3877766/Web-Developer-How-To-Upload-Images-Using-PHP.htm -->

<?php session_start(); ?>
<html>
  <body>
    <!-- If scalar data -->
    <!-- scalar_data (id, sensor_id, date_created, value) -->
	<buton> Hello </button>


    <!-- If image .jpg -->
    <!-- images (image_id, sensor_id, date_created , recorded_data, thumbnail, description) -->
    
    <!-- If audio .wav-->
    <!-- audio_recordings (recording_id, sensor_id, date_created, length, recorded_data, description) -->
  </body>
</html>
