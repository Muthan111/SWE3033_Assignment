<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "managementToolDB";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// sql to create table
$sqlMember = "CREATE TABLE Member (userID CHAR(10) PRIMARY KEY 
,username VARCHAR(25), firstName VARCHAR(25), 
lastName VARCHAR(25), email VARCHAR(25), 
password CHAR(20));";

if ($conn->query($sqlMember) === TRUE) {
    echo "Table User created successfully";
  } else {
    echo "Error creating User table: " . $conn->error;
  }
echo "<br>";
$sqlProject = "CREATE TABLE Project (projectID CHAR(10) PRIMARY KEY , projectName VARCHAR(20), startDate DATE, dueDate DATE, projectDescription VARCHAR(25));";

if ($conn->query($sqlProject) === TRUE) {
    echo "Table Project created successfully";
  } else {
    echo "Error creating Project table: " . $conn->error;
  }
echo "<br>";
$sqluserProject= "CREATE TABLE userProject (
    userprojectID VARCHAR(10) PRIMARY KEY,
    userID VARCHAR(10) NOT NULL,
    projectID VARCHAR(10) NOT NULL,
    isadmin BOOLEAN,
    FOREIGN KEY (userID) REFERENCES Member(userID),
    FOREIGN KEY (projectID) REFERENCES Project(projectID)
);";

if ($conn->query($sqluserProject) === TRUE) {
    echo "Table User_Project created successfully";
  } else {
    echo "Error creating User_Project table: " . $conn->error;
  }
echo "<br>";
$sqlTask = "CREATE TABLE Task (taskID CHAR(10) PRIMARY KEY,
 userprojectID CHAR(10), taskName VARCHAR(30),
  description VARCHAR(50), dueDate Date, status Boolean,
   FOREIGN KEY (userprojectID) REFERENCES userProject(userprojectID));";


if ($conn->query($sqlTask) === TRUE) {
    echo "Table Task created successfully";
  } else {
    echo "Error creating Task table: " . $conn->error;
  }
echo "<br>";
$conn->close();
?>
