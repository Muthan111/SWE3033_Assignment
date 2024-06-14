<?php
    /*

    Description: Main html page to create project

    CHANGELOG:
    1. Initial version created (14/06/2024)

    TO DO:
    1. NEED START AND DUE DATE INPUT

    Created on 14/06/2024 by Sean
    */

    session_start(); // To get currently logged in user ID

    include ("project_functions.php");
    include ("mysqli_connect.php");

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        // Incomplete due to no start date and due date
        $errors = create_project($dbc, $_SESSION['user_id'], $_POST['project-title'], $_POST['project-description']);

        if(empty($errors)){

            redirect_user("Homepage.HTML"); // Might change to the specific project page

        }

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scorpio Task Manager</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            margin: 0;
            padding: 0;
        }
        .sidebar {
            width: 250px;
            background-color: #f4f4f4;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        .sidebar h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .sidebar nav a {
            display: block;
            padding: 10px;
            margin: 5px 0;
            color: #333;
            text-decoration: none;
        }
        .sidebar nav a:hover {
            background-color: #ddd;
        }
        .dropdown {
            margin: 20px 0;
        }
        .dropdown button {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #f4f4f4;
            border: 1px solid #ccc;
            cursor: pointer;
            text-align: left;
        }
        .dropdown-content {
            display: none;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
        }
        .dropdown-content a {
            padding: 10px;
            display: block;
            text-decoration: none;
            color: #333;
        }
        .dropdown-content a:hover {
            background-color: #ddd;
        }
        .main-content {
            flex-grow: 1;
            padding: 40px;
            background-color: #fff;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-size: 18px;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        .form-group textarea {
            resize: vertical;
            height: 150px;
        }
        .generate-id {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .generate-id input {
            flex-grow: 1;
            margin-right: 10px;
        }
        .generate-id button {
            padding: 15px;
            background-color: #777;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .generate-id button:hover {
            background-color: #555;
        }
        .create-task {
            display: flex;
            justify-content: center;
        }
        .create-task button {
            padding: 15px 30px;
            background-color: #777;
            color: #fff;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 16px;
        }
        .create-task button:hover {
            background-color: #555;
        }
        .errorclass{
            font-family: var(--small-text);
            color: red;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Scorpio Task Manager</h2>
        <nav>
            <a href="#">Home</a>
            <a href="#">Join by code</a>
            <div class="dropdown">
                <button onclick="toggleDropdown('participating-projects')">Participating Projects</button>
                <div id="participating-projects" class="dropdown-content">
                    <a href="#">Main Projects</a>
                    <a href="#">Add Task by code...</a>
                </div>
            </div>
            <div class="dropdown">
                <button onclick="toggleDropdown('hidden-projects')">Hidden Projects</button>
                <div id="hidden-projects" class="dropdown-content">
                    <a href="#">Hidden Projects</a>
                </div>
            </div>
            <a href="#">Project Chat</a>
        </nav>
    </div>
    <form class="main-content" action="CreateProject.php" method="post">
        <h1>Create a New Project</h1>
        <div class="generate-id form-group">
            <input type="text" id="project-id" name="project-id" value="<?php echo generate_id($dbc, 4) ?>">
            <button id="Auto-Generate" onclick>Auto Generate</button>
        </div>
        <div class="form-group">
            <label for="project-title">Project Title</label>
            <input type="text" id="project-title" name="project-title" placeholder="Project Title">
        </div>
        <div class="form-group">
            <label for="project-start-date">Project Start Date</label>
            <input type="date" id="project-start-date">
        </div>
        <div class="form-group">
            <label for="project-end-date">Project End Date</label>
            <input type="date" id="project-end-date">
        </div>
        <div class="form-group">
            <label for="project-description">Project Description</label>
            <textarea id="project-description" name="project-description" placeholder="Project Description"></textarea>
        </div>
        <!--
        !!!CREATE THE INPUTS FOR START DATE AND DUE DATE HERE!!!

        SET THE NAME ATTRIBUTE FOR START DATE INPUT AS "project-start-date"
        <input name="project-start-date" ...>
        SET THE NAME ATTRIBUTE FOR DUE DATE INPUT AS "project-due-date"
        <input name="project-due-date" ...>

        IF THE NAMES VALUES DOES NOT MATCH THE ABOVE, THE PHP WILL NOT WORK
        -->
        <div>
            <?php
            // PRINTS OUT THE ERRORS, NEED TO DESIGN THE OUTPUT SOON
            if (isset($errors) && !empty($errors)) {
                echo '<p class="errorclass">The following error(s) occurred:<br />';
                foreach ($errors as $msg) {
                    echo " - $msg<br />\n";
                }
                echo '</p><p class="errorclass">Please try again.</p>';
              } 
            ?>
        </div>
        <div class="create-task" type = "submit" form = "content" value = "Submit">
            <button>Create a New Task</button>
        </div>
    </form>

    <script>
        function toggleDropdown(id) {
            const content = document.getElementById(id);
            content.style.display = content.style.display === 'block' ? 'none' : 'block';
        }
        function generateID() {
            const content = document.getElementById("project-id");
            location.reload()
        }
    </script>
</body>
</html>
