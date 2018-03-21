
	# DSM
	
	Damn Small Memory. Warning: Not secure at all. Only for testing.
	
	## Usage
	
	Call the script with a GET parameter "query" (by default). The value must be in Base64. There is a "test.php" file to facilitate testing.
	
	### Create Database
	
	Exemple:
	
	```CREATE DATABASE mydatabase```
	
	### Create Table
	
	Exemple:
	
	```CREATE TABLE mytable IN mydatabase```
	
	### Insert
	
	Exemple:
	
	```INSERT INTO mytable IN mydatabase ({"key":"value"})```
	
	### Select
	
	Exemple:
	
	```SELECT mytable IN mydatabase (*)```
	OR
	```SELECT mytable IN mydatabase ({"key":"value"})```
	
	### Update
	
	Exemple:
	
	```UPDATE mytable IN mydatabase ({"UnidID":"UniqID_Value","key":"new_value"})```
	
	### Delete
	
	Exemple:
	
	```DELETE mytable IN mydatabase ({"UnidID":"UniqID_Value"})```
