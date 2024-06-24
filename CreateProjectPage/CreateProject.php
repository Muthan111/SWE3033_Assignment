<!-- 
Description: Main html page to create project

CHANGELOG:
1. Initial version created (14/06/2024)
2. Assign start date and due date input to form. Changed button to "Create a New Project"  (15/06/2024)
3. Fixed the Auto-generate button that triggered a POST request because it's type wasnt specified... (15/06/2024)
4. Added check if user is logged in, if not, the user will be redirected to the login page (24/06/2024)

TO DO:
1. TESTING

Created on 14/06/2024 by Sean -->

<!DOCTYPE html>
<html>

<?php
session_start(); // To get currently logged in user ID
if(!isset($_SESSION["user_id"]) || !isset($_SESSION["username"])){
    // Redirects the user to a page, temporary placeholder for now
    include('../PHP/redirect_function.php');
    redirect_user('../LoginPage/login.php');
} else{
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
}
include ("../PHP/project_functions.php");
include ("../PHP/mysqli_connect.php");

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    // Incomplete due to no start date and due date
    $errors = create_project($dbc, $user_id);

    if(empty($errors)){

        redirect_user("Homepage.HTML"); // Might change to the specific project page

    }
}?>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width" />
    <link rel="stylesheet" href="./CreateProject.css" />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Inter:wght@200;400;500;600;700&display=swap"
    />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Noto Sans:wght@400&display=swap"
    />
</head>
<body>
    <main class="mainpage" id="Main">
        <div class="background-fill" id="bg-fill">
            <section class="homepage-sidebar2" id="SidebarSection">
                <h1 class="app-title" id="ScorpioTaskManager">Scorpio Task Manager</h1>
                <img class="scorpio-icon" loading="lazy" alt="" src="scorpio-icon.png" />
    
                <nav class="sidebarui">
                    <nav class="navigation" id="JoinProjectNavigator">
                        <b class="menu-item13">
                            <b class="join-projects2">Join Projects</b>
                        </b>
                        <button class="homebutton" id="HomeButton">
                            <img class="home-icon3" alt="" src="home-icon.png" />
                            <div class="home2">Home</div>
                        </button>
                        <button class="joinbycodebutton" id="JoinByCodeButton">
                            <img class="add-icon3" alt="" src="add-icon.png" />
                            <div class="join-by-code2">Join by code</div>
                        </button>
                    </nav>
                    <nav class="list5" id="ProjectSelectNavigator">
                        <div class="title6">
                            <b class="participating-projects2">Admin Project List</b>
                        </div>
                        <div class="select-container">
                            <img src="select-icon.png" alt="Project Select Dropdown Icon" />
                            <select class="menu-item14">
                                <option value="" disabled selected>Select Project</option>
                                <?php
                                    list($check, $data) = return_project_list($dbc, $user_id, 1); // Is an admin

                                    if($check == 1){
                                        while($project = mysqli_fetch_assoc($data)){
                                            $project_name = $project['projectName'];
                                            $project_id = $project['projectID'];
                                            echo "<option value='$project_id'>$project_name - PID:$project_id</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </nav>
                    <nav class="list5" id="ProjectSelectNavigatorAdmin">
                        <div class="title6">
                            <b class="participating-projects2">Member Project List</b>
                        </div>
                        <div class="select-container">
                            <img src="select-icon.png" alt="Project Select Dropdown Icon" />
                            <select class="menu-item14">
                                <option value="" disabled selected>Select Project</option>
                                <?php
                                    list($check, $data) = return_project_list($dbc, $user_id, 0); // Not an admin

                                    if($check == 1){
                                        while($project = mysqli_fetch_assoc($data)){
                                            $project_name = $project['projectName'];
                                            $project_id = $project['projectID'];
                                            echo "<option value='$project_id'>$project_name - PID:$project_id</option>";
                                        }
                                    }

                                    mysqli_close($dbc); // Close database connection
                                ?>
                            </select>
                        </div>
                    </nav>
                    <nav class="list6" id="ChatSelectNavigation">
                        <b class="title7" id="ProjectForum">
                            <b class="project-forum2">Project Forum</b>
                        </b>
                        <div class="select-container">
                            <img src="chat-icon.png" alt="Project Chat Select Dropdown Icon" />
                            <select class="menu-item15" id="SelectChat">
                                <option value="" disabled selected>Select Chat</option>
                                <option value="chat1">- Chat 1</option>
                                <option value="chat2">- Chat 2</option>
                                <option value="chat3">- Chat 3</option>
                            </select>
                        </div>
                    </nav>
                </nav>
            </section>    
        </div>
        <section class="main-container">
        <form class="main-content" action="CreateProject.php" method="post">
        <h1>Create a New Project</h1>
        <div class="generate-id form-group">
            <input type="text" id="project-id" name="project-id" value="<?php echo generate_id($dbc, 4) ?>">
            <button id="Auto-Generate" onclick="refresh()" type="button">Auto Generate</button>
        </div>
        <div class="form-group">
            <label for="project-title">Project Title</label>
            <input type="text" id="project-title" name="project-title" placeholder="Project Title">
        </div>
        <div class="form-group">
            <label for="project-start-date">Project Start Date</label>
            <input name="project-start-date" type="date" id="project-start-date">
        </div>
        <div class="form-group">
            <label for="project-end-date">Project End Date</label>
            <input name="project-due-date" type="date" id="project-end-date">
        </div>
        <div class="form-group">
            <label for="project-description">Project Description</label>
            <textarea id="project-description" name="project-description" placeholder="Project Description"></textarea>
        </div>
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
            <button>Create a Project</button>
        </div>
    </form>
        </section>
    </main>
    <script>
        document.getElementById('adminProjectSelect').addEventListener('change', function(e){
            window.location.href = "../DisplayProjectPage/display_project_page.php?id="+ this.value;
            });
        
        document.getElementById('memberProjectSelect').addEventListener('change', function(e) {
            window.location.href = "../DisplayProjectPage/display_project_page.php?id="+ this.value;
            });
        function refresh() {
            window.top.location = window.top.location;
        }
    </script>
</body>
</html>
