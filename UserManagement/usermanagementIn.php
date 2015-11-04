<html>
  <body>
     <!-- Check if Sys Admin -->
     <!-- Add/Delete/Update users and persons -->
     <!-- http://stackoverflow.com/questions/15318032/divide-html-page-in-half-with-no-overlapping -->
     <h2>User Account Management</h2>
     
     <h4><u>Add User</h4></u></p>
     <form name = "UserManagement" method = "post" action = "usermanagement.php">
       <table style = "width:30%">
         <tr>
           <td>Username:</td>
           <td><input type = "text" name = "userNameA"></td></p>
	 </tr>
	 <tr>
       	   <td>Password:</td>
       	   <td><input type = "text" name = "userPWA"></td></p>
	 </tr>
	 <tr>
       	   <td>Role:</td>
       	   <td><input type = "text" name = "userRoleA"></td></p>
	 </tr>
	 <tr>
       	   <td>Person ID:</td>
       	   <td><input type = "text" name = "userPersonA"></td></p>
	 </tr>
	 <tr>
       	   <td>Date Registered:</td>
       	   <td><input type = "text" name = "userDateA"></td></p>
	 </tr>
	</table>
	<input type = "submit" name = "userAdd" value = "Add User" >
     <h4>Remove User</h4> </p>
        Username: <input type = "text" name = "userNameR"></p>
        <input type = "submit" name = "userRemove" value = "Remove User"></p>
     <h4>Update User</h4> </p> 
               <table style = "width:30%">
         <tr>
           <td>Username:</td>
           <td><input type = "text" name = "userNameU"></td></p>
	 </tr>
	 <tr>
       	   <td>Password:</td>
       	   <td><input type = "text" name = "userPWU"></td></p>
	 </tr>
	 <tr>
       	   <td>Role:</td>
       	   <td><input type = "text" name = "userRoleU"></td></p>
	 </tr>
	 <tr>
       	   <td>Person ID:</td>
       	   <td><input type = "text" name = "userPersonU"></td></p>
	 </tr>
	 <tr>
       	   <td>Date Registered:</td>
       	   <td><input type = "text" name = "userDateU"></td></p>
	 </tr>
	</table>
        <input type = "submit" name = "userUpdate" value = "Update User" >
   <h2>Person Management</h2>
  <h4><u>Add Person</h4></u></p>
 <table style = "width:30%">
         <tr>
           <td>Person ID:</td>
           <td><input type = "text" name = "personIDA"></td></p>
	 </tr>
	 <tr>
       	   <td>First Name:</td>
       	   <td><input type = "text" name = "personFirstA"></td></p>
	 </tr>
	 <tr>
       	   <td>Last Name:</td>
       	   <td><input type = "text" name = "personLastA"></td></p>
	 </tr>
	 <tr>
       	   <td>Address:</td>
       	   <td><input type = "text" name = "personAddressA"></td></p>
	 </tr>
	 <tr>
       	   <td>Email:</td>
       	   <td><input type = "text" name = "personEmailA"></td></p>
	 </tr>
	 <tr>
       	   <td>Phone:</td>
       	   <td><input type = "text" name = "personPhoneA"></td></p>
	 </tr>
	</table>
        <input type = "submit" name = "addPerson" value = "Add Person" >
 <h4>Remove Person</h4> </p>
        Person ID: <input type = "text" name = "personR"></p>
        <input type = "submit" name = "personRemove" value = "Remove Person"></p>
<h4><u>Update Person</h4></u></p>
 <table style = "width:30%">
         <tr>
           <td>Person ID:</td>
           <td><input type = "text" name = "personIDU"></td></p>
	 </tr>
	 <tr>
       	   <td>First Name:</td>
       	   <td><input type = "text" name = "personFirstU"></td></p>
	 </tr>
	 <tr>
       	   <td>Last Name:</td>
       	   <td><input type = "text" name = "personLastU"></td></p>
	 </tr>
	 <tr>
       	   <td>Address:</td>
       	   <td><input type = "text" name = "personAddressU"></td></p>
	 </tr>
	 <tr>
       	   <td>Email:</td>
       	   <td><input type = "text" name = "personEmailU"></td></p>
	 </tr>
	 <tr>
       	   <td>Phone:</td>
       	   <td><input type = "text" name = "personPhoneU"></td></p>
	 </tr>
	</table>
        <input type = "submit" name = "updatePerson" value = "Update Person" >
	</form>
  </body>
</html>
