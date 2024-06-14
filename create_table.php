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
// Lines 15 to 33 check if the table exists and if it does not, it creates the table
//Member table
$sqlCheckMember = "SHOW TABLES LIKE 'Account'";
$resultMember = $conn->query($sqlCheckMember);
if($resultMember->num_rows == 1) {
  echo "Table User already exists";
  echo "<br>";
} else {
  $sqlMember = "CREATE TABLE Account (userID INT(10) AUTO_INCREMENT PRIMARY KEY 
  ,username VARCHAR(25), firstName VARCHAR(25), 
  lastName VARCHAR(25), email VARCHAR(25), 
  password VARCHAR(20));";

  if ($conn->query($sqlMember) === TRUE) {
    echo "Table User created successfully";
  } else {
    echo "Error creating User table: " . $conn->error;
  }
echo "<br>";
}

// Lines 34 to 48 check if the table exists and if it does not, it creates the table
//Project table
$sqlCheckProject = "SHOW TABLES LIKE 'Project'";
$resultProject = $conn->query($sqlCheckProject);
if($resultProject->num_rows == 1) {
  echo "Table Project already exists";
  echo "<br>";
} else {
  $sqlProject = "CREATE TABLE Project (projectID VARCHAR(10) PRIMARY KEY, projectName VARCHAR(20), startDate DATE, dueDate DATE, projectDescription VARCHAR(50));";

  if ($conn->query($sqlProject) === TRUE) {
    echo "Table Project created successfully";
  } else {
    echo "Error creating Project table: " . $conn->error;
  }
echo "<br>";
}

// Lines 51 to 73 check if the table exists and if it does not, it creates the table
//userProject table
$sqlCheckuserProject = "SHOW TABLES LIKE 'userProject'";
$resultuserProject = $conn->query($sqlCheckuserProject);
if($resultuserProject->num_rows == 1) {
  echo "Table userProject already exists";
  echo "<br>";
} else {
    $sqluserProject= "CREATE TABLE userProject (
    userID INT(10) NOT NULL,
    projectID VARCHAR(10) NOT NULL,
    isadmin BOOLEAN,
    PRIMARY KEY (userID, projectID),
    FOREIGN KEY (userID) REFERENCES Account(userID),
    FOREIGN KEY (projectID) REFERENCES Project(projectID)
);";

  if ($conn->query($sqluserProject) === TRUE) {
    echo "Table User_Project created successfully";
  } else {
    echo "Error creating User_Project table: " . $conn->error;
  }
echo "<br>";
}

// Lines 79 to 97 check if the table exists and if it does not, it creates the table
//Task table
$sqlCheckTask = "SHOW TABLES LIKE 'Task'";
$resultTask = $conn->query($sqlCheckTask);
if($resultTask->num_rows == 1) {
  echo "Table Task already exists";
  echo "<br>";
} else {
  $sqlTask = "CREATE TABLE Task (
  taskID VARCHAR(10) PRIMARY KEY,
  taskName VARCHAR(30),
  description VARCHAR(50), 
  dueDate Date, 
  status Boolean, 
  projectID VARCHAR(10) NOT NULL,
  FOREIGN KEY (projectID) REFERENCES Project(projectID)
);";


  if ($conn->query($sqlTask) === TRUE) {
    echo "Table Task created successfully";
  } else {
    echo "Error creating Task table: " . $conn->error;
  }
echo "<br>";
}

// Lines 79 to 102 check if the table exists and if it does not, it creates the table
//userProject table
$sqlCheckuserProjectTask = "SHOW TABLES LIKE 'userProjectTask'";
$resultuserProjectTask = $conn->query($sqlCheckuserProjectTask);
if($resultuserProjectTask->num_rows == 1) {
  echo "Table userProjectTask already exists";
  echo "<br>";
} else {
  $sqluserProjectTask = "CREATE TABLE userProjectTask (
    userID INT(10),
    projectID VARCHAR(10),
    taskID VARCHAR(10),
    PRIMARY KEY (userID,projectID,taskID),
    FOREIGN KEY (userID, projectID) REFERENCES userProject(userID, projectID),
    FOREIGN KEY (taskID) REFERENCES Task(taskID)
  );";
  if ($conn->query($sqluserProjectTask) === TRUE) {
    echo "Table userProjectTask created successfully";
  } else {
    echo "Error creating sqluserProjectTask table: " . $conn->error;
  }
  echo "<br>";
}






$conn->close();
?>
