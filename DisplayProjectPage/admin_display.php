<!DOCTYPE html>
<?php
        
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['UpdateProject'])){

    $error_messages = array();
    $error_messages = array_merge($error_messages, update_project($dbc, $user_id, $project_id));

    if(!empty($_POST['task-id'])){
        $task_id = $_POST['task-id'];
        $task_name = $_POST['task-title'];
        $task_desc = $_POST['task-description'];
        $due_date = $_POST['task-due-date'];
        $status = $_POST['task-status'];
        
        foreach($task_id AS $key => $value){

            $error = validate_task_id($dbc, $value);

            if(empty($error)){
                $error_messages = array_merge($error_messages, update_task($dbc, $project_id, $value, $task_name[$key], $task_desc[$key], $due_date[$key], $status[$key]));
            }

            if(!empty($error)){
                $error_messages = array_merge($error_messages, $error);
                break;
            }
        }

    }

    if (empty($error_messages)){
        $project_name = $_POST['project-title'];
        $project_desc = $_POST['project-description'];
    }
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['projectCode'])) {
    $projectCode = $_POST['projectCode'];
    $already_joined = join_project($dbc, $projectCode, $user_id);

    if (!$already_joined) {
        $redirect_url = '../DisplayProjectPage/display_project.php?id='. $projectCode;
        echo "<script>window.open('$redirect_url', '_blank');</script>";
    } else {
        $error_message = "You have already joined this project"; 
    }
}
?>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width" />
    <link rel="stylesheet" href="./admin_display.css" />
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
                                            $admin_project_name = $project['projectName'];
                                            $admin_project_id = $project['projectID'];
                                            echo "<option value='$admin_project_id'>$project_name - PID:$admin_project_id</option>";
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
                                            $member_project_name = $project['projectName'];
                                            $member_project_id = $project['projectID'];
                                            echo "<option value='$member_project_id'>$project_name - PID:$member_project_id</option>";
                                        }
                                    }    
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
                    <a class="logout-link" href="../logout.php">LOGOUT</a>
                </nav>
            </section>    
        </div>
        <section class="main-container">

        
            <!------------------------------------------------------------- HAU WEN PART ---------------------------------------------------------------------------->


    <form class="container" action="display_project.php?id=<?php echo $project_id ?>" method="post">
        <!-- Header section displaying project title and dates -->
        <header>
            <h1 id="project-title">Project One</h1>
            <div class="project-dates">
                <span id="project-start-date">Start Date: 2024-01-01</span> |
                <span id="project-end-date">End Date: 2024-12-31</span>
            </div>
        </header>
        
        <!-- Section for project details such as name and ID -->
        <section class="project-details">
            <div class="project-name">
                <label for="project-name">Project Name</label>
                <input name="project-title" type="text" id="project-name-input" value="<?php echo $project_name?>">
            </div>
            <div class="project-id">
                <label for="project-id">Project ID</label>
                <span id="project-id"><?php echo $project_id;?></span>
            </div>
        </section>

        <!-- Section for project description -->
        <section class="project-description">
            <label for="project-description">Project Description</label>
            <textarea name="project-description" id="project-description-input"><?php echo $project_desc;?></textarea>
        </section>

        <!-- Section for managing tasks -->
        <section class="errors">
            <?php

                if(!empty($error_messages)){
                    echo '<p class="errorclass">The following error(s) occurred:<br />';
                    foreach($error_messages as $msg){
                        echo " - $msg<br />\n";
                    }
                    echo '</p><p class="errorclass">Please try again.</p>';
                }

            ?>
        </section>
        <section class="tasks">
            <h2>Tasks</h2>
            <ul id="task-list">
                <!-- Tasks will be added here dynamically -->
                <?php

                    $data = return_task_list($dbc, $project_id, $user_id);

                    if($data != null){
                        while($task = mysqli_fetch_assoc($data)){

                            echo "
                            <li>
                                <input type='text' name='task-title[]' value='" . $task['taskName'] . "'>
                                <input type='hidden' name='task-id[]' value='" . $task['taskID'] . "'>
                                <input type='text' name='task-description[]' value='" . $task['description'] . "'>";
                                switch($task['status']){
                                    case 1:
                                        echo "
                                            <select name='task-status[]'>
                                                <option value='1' selected>Ongoing</option>
                                                <option value='2'>Completed</option>
                                                <option value='3'>Unassigned</option>
                                                <option value='0'>Expired</option>
                                            </select>";
                                        break;
                                    case 2:
                                        echo "
                                            <select name='task-status[]'>
                                                <option value='1'>Ongoing</option>
                                                <option value='2' selected>Completed</option>
                                                <option value='3'>Unassigned</option>
                                                <option value='0'>Expired</option>
                                            </select>";
                                        break;
                                    case 3:
                                        echo "
                                            <select name='task-status[]'>
                                                <option value='1'>Ongoing</option>
                                                <option value='2'>Completed</option>
                                                <option value='3' selected>Unassigned</option>
                                                <option value='0'>Expired</option>
                                            </select>";
                                        break;
                                    case 0:
                                        echo "
                                            <select name='task-status[]'>
                                                <option value='1'>Ongoing</option>
                                                <option value='2'>Completed</option>
                                                <option value='3'>Unassigned</option>
                                                <option value='0' selected>Expired</option>
                                            </select>";
                                        break;
                                    default:
                                        echo "
                                            <select name='task-status[]'>
                                                <option value='1'>Ongoing</option>
                                                <option value='2'>Completed</option>
                                                <option value='3'>Unassigned</option>
                                                <option value='0'>Expired</option>
                                            </select>";
                                }
                            echo "
                                <input type='date' name='task-due-date[]' value='" . $task['dueDate'] . "'>
                                <input type='text' readonly='' id='daysRemaining'>
                            </li>
                            ";

                        }
                    }

                ?>
            </ul>
            <div class="buttons">
                <button id="createTaskButton" form="none">Create Task</button>
                <button id="updateProjectButton" value="Submit" type="submit" name="UpdateProject">Update Project</button>
            </div>
        </section>
    </form>

    <!-- Task form container, initially hidden -->
    <div id="taskFormContainer" style="display: none;">
        <div class="task-form">
            <label for="task-name">Task Name</label>
            <input type="text" id="task-name">

            <label for="task-description">Task Description</label>
            <input type="text" id="task-description">

            <label for="task-status">Task Status</label>
            <select id="task-status">
                <option value="unassigned">Unassigned</option>
                <option value="ongoing">Ongoing</option>
                <option value="completed">Completed</option>
                <option value="expired">Expired</option>
            </select>

            <label for="task-due-date">Due Date</label>
            <input type="date" id="task-due-date">

            <label for="task-days-remaining">Days Remaining</label>
            <input type="text" id="task-days-remaining" readonly>

            <button id="saveTaskButton">Save Task</button>
        </div>
    </div>

             <!------------------------------------------------------------ HAU WEN PART ---------------------------------------------------------------------------->
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

        // Event listener to display task creation form
        document.getElementById('createTaskButton').addEventListener('click', function(e) {
            window.location = "../CreateTaskPage/create_task.php?id=<?php echo $project_id;?>";
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

        function updateTaskName(name) {
            console.log(`Updated task name to "${name}"`);
        }

        function updateTaskDescription(description) {
            console.log(`Updated task description to "${description}"`);
        }

        function updateTaskStatus(name, newStatus) {
            console.log(`Updated task "${name}" to status "${newStatus}"`);
        }

        function updateTaskDaysRemaining(name, newDaysRemaining) {
            console.log(`Updated task "${name}" to have ${newDaysRemaining} days remaining`);
        }

        // Event listener to calculate and display days remaining for a task
        document.getElementById('task-due-date').addEventListener('change', function() {
            const dueDate = new Date(document.getElementById('task-due-date').value);
            const currentDate = new Date();
            const timeDiff = dueDate - currentDate;
            const daysRemaining = Math.ceil(timeDiff / (1000 * 3600 * 24));
            document.getElementById('daysRemaining').value = daysRemaining;
        });
    </script>
<?php mysqli_close($dbc); // Close database connection ?>