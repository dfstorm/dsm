
# DSM

Damn Small Memory; A file based database engine. Warning: Not secure at all. Only for testing.

## Usage

Call the script with a GET parameter "query" (by default). The value must be in Base64. There is a "test.php" file to facilitate testing.

### Create Database

```
CREATE DATABASE mydatabase
```

### Create Table

```
CREATE TABLE mytable IN mydatabase (field1,field2,[...])
```

### Insert

```
INSERT INTO mytable IN mydatabase ({"key":"value"})
```

### Select

```
SELECT mytable IN mydatabase (*)
```

OR

```
SELECT mytable IN mydatabase ({"key":"value"})
```

### Update

```
UPDATE mytable IN mydatabase ({"UnidID":"UniqID_Value","key":"new_value"})
```

### Delete

```
DELETE mytable IN mydatabase ({"UnidID":"UniqID_Value"})
```
