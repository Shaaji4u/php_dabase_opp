<?php

require_once 'Database.php';

/*

How to use the Database class

first make sure to require the file

example to insert data into the db

assuming we have a table_name Users
with field user_id, user_name, user_email, user_pass


*/

$db  = new Database();


$db->query("INSERT INTO users (user_name, user_email, user_pass) VALUES(?,?,?)");

// Init binding of values
$db->bind_each("Julie Shaaji");
$db->bind_each("juliet@gmail.com");
$db->bind_each("123456876"); 

// Finalize the binding of values
$db->bind_now();

//Execute the query
$db->execute();


// Check if the data was inserted

if($db->affected()>0){
	echo "User Added successfully";
}else{
	echo "Failed to Add User";
}



?>
