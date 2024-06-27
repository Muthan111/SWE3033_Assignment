<!DOCTYPE html>
<?php
session_start();

if(!isset($_SESSION["user_id"]) || !isset($_SESSION["username"])){
    include('../PHP/redirect_function.php');
    redirect_user('../LoginPage/login.php');
} else{
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
}

include ('../PHP/project_functions.php');
include ('../PHP/mysqli_connect.php');

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['projectCode'])) {
    $projectCode = $_POST['projectCode'];
    $already_joined = join_project($dbc, $projectCode, $user_id);

    if (!$already_joined) {
        $redirect_url = '../DisplayProjectPage/display_project.php?id='. $projectCode;
        echo "<script>window.open('$redirect_url', '_blank');</script>";
        exit();
    } else {
        $error_message = "You have already joined this project"; 
    }
}
?>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width" />
    <link rel="stylesheet" href="./member_display.css" />
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
                            <select class="menu-item14" id="adminProjectSelect">
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
                            <select class="menu-item14" id="memberProjectSelect">
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
                    <!------------------------------------------------------------------- HAU WEN PART ----------------------------------------------------------------------------------->
        <header>
            <h1 id="project-title">Project One</h1>
            <div class="project-dates">
                <span id="project-start-date">Start Date: 2024-01-01</span> |
                <span id="project-end-date">End Date: 2024-12-31</span>
        </header>
        <div class="project-details">
            <div class="project-name">
                <label for="project-name">Project Name</label>
                <span id="project-name">Example Project Name</span>
            </div>
            <div class="project-id">
                <label for="project-id">Project ID</label>
                <span id="project-id">12345</span>
            </div>
        </div>
        <div class="project-description">
            <label for="project-description">Project Description</label>
            <span id="project-description">This is an example project description.</span>
        </div>
        <div class="tasks">
            <h2>
                Tasks
            </h2>
            <?php
                //NEEDS TESTING

                if($_SERVER['REQUEST_METHOD'] === 'POST'){
                    $errors = join_task($dbc, $project_id, $_POST['taskIDInput'], $user_id);

                    if(!empty($errors)){

                        echo '<p class="errorclass">The following error(s) occurred:<br />';
                        foreach ($errors as $msg) {
                            echo " - $msg<br />\n";
                        }
                        echo '</p><p class="errorclass">Please try again.</p>';

                    }
                }

            ?>
            <form class="task-input-container" action="display_project.php?id=<?php echo $project_id ?>" method="post">
                <input type="text" id="taskIDInput" name="taskIDInput" placeholder="Task ID" oninput="enableAddButton()" >
                <button id="addTaskButton" disabled type="submit" value="Submit">Add Task</button>
            </form>
            
            <table>
                <thead>
                    <tr>
                        <th>Task Name</th>
                        <th>Task Description</th>
                        <th>Task Status</th>
                        <th>Days Remaining</th>
                    </tr>
                </thead>
                <tbody id="task-list">
                    <!-- Tasks will be added here dynamically -->
                </tbody>
            </table>
        </div>
    </div>
                    <!-------------------------------------------------------------- HAU WEN PART -------------------------------------------------------------->
            </section>   
            <div id="joinCodePopup" class="popup">
            <div class="popup-content">
                <span class="close">&times;</span>
                <form id="joinCodeForm" method="post">
                    <input type="text" id="projectCode" placeholder="Enter Project Code" class="popupInputField" name="projectCode" required>
                    <button type="submit" value="Submit">Join Project</button>
                    <div id="error-message" style="color: red;"><?php echo $error_message; ?></div>
                    <input type="hidden" id="phpErrorMessage" value="<?php echo $error_message; ?>" />
                </form>
            </div>
        </div>
    </main>
    <script>
        var goHome = document.getElementById("HomeButton");
        if (goHome) {
            goHome.addEventListener("click", function (e) {
                window.location.href = "../index.php";
                }
            );
        }

        document.getElementById('adminProjectSelect').addEventListener('change', function(e){
            window.location.href = "../DisplayProjectPage/display_project.php?id="+ this.value;
            });
        
        document.getElementById('memberProjectSelect').addEventListener('change', function(e) {
            window.location.href = "../DisplayProjectPage/display_project.php?id="+ this.value;
            });

        // Get the popup
        var popup = document.getElementById("joinCodePopup");

        // Get the button that opens the popup
        var btn = document.getElementById("JoinByCodeButton");

        // Get the <span> element that closes the popup
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks the button, open the popup 
        btn.onclick = function() {
            popup.style.display = "block";
        }

        // When the user clicks on <span> (x), close the popup
        span.onclick = function() {
            popup.style.display = "none";
        }

        // When the user clicks anywhere outside of the popup, close it
        window.onclick = function(event) {
            if (event.target == popup) {
                popup.style.display = "none";
            }
        }

        // Show error message if exists
        var phpErrorMessage = document.getElementById("phpErrorMessage").value;
        if (phpErrorMessage) {
            var errorMessage = document.getElementById("error-message");
            errorMessage.style.display = "block";
            errorMessage.innerText = phpErrorMessage;
            popup.style.display = "block";
        }
    </script>