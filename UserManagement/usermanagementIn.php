<html>
  <body>
     <!-- Check if Sys Admin -->
     <!-- Add/Delete/Update users and persons -->
     <!-- http://stackoverflow.com/questions/15318032/divide-html-page-in-half-with-no-overlapping -->
     <h2>User Account Management</h2>
     
     <h4><u>Add User</h4></u></p>
     <form name = "addUser" method = "post" action = "usermanagement.php">
       <table style = "width:30%">
         <tr>
           <td>Username:</td>
           <td><input type = "text" name = "userName"></td></p>
	 </tr>
	 <tr>
       	   <td>Password:</td>
       	   <td><input type = "text" name = "userPW"></td></p>
	 </tr>
	 <tr>
       	   <td>Role:</td>
       	   <td><input type = "text" name = "userRole"></td></p>
	 </tr>
	 <tr>
       	   <td>Person ID:</td>
       	   <td><input type = "text" name = "userPerson"></td></p>
	 </tr>
	 <tr>
       	   <td>Date Registered:</td>
       	   <td><input type = "text" name = "userDate"></td></p>
	 </tr>
	</table>
	<input type = "submit" name = "userAdd" value = "Add User" >
     </form>     
     <h4>Remove User</h4> </p>

     <h4>Update User</h4> </p> 
  </body>
</html>