<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width" />
    <link rel="stylesheet" href="./create_task.CSS" />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Inter:wght@200;400;500;600;700&display=swap"
    />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Noto Sans:wght@400&display=swap"
    />
</head>
    <main class="mainpage" id="Main">
        <div class="background-fill" id="bg-fill">
            <section class="homepage-sidebar2" id="SidebarSection">
                <h1 class="app-title" id="ScorpioTaskManager">Scorpio Task Manager</h1>
                <img class="scorpio-icon" loading="lazy" alt="" src="../images/scorpio-icon.png" />
    
                <nav class="sidebarui">
                    <nav class="navigation" id="JoinProjectNavigator">
                        <b class="menu-item13">
                            <b class="join-projects2">Join Projects</b>
                        </b>
                        <button class="homebutton" id="HomeButton">
                            <img class="home-icon3" alt="" src="../images/home-icon.png" />
                            <div class="home2">Home</div>
                        </button>
                        <button class="joinbycodebutton" id="JoinByCodeButton">
                            <img class="add-icon3" alt="" src="../images/add-icon.png" />
                            <div class="join-by-code2">Join by code</div>
                        </button>
                    </nav>
                    <nav class="list5" id="ProjectSelectNavigator">
                        <div class="title6">
                            <b class="participating-projects2">Admin Project List</b>
                        </div>
                        <div class="select-container">
                            <img src="../images/select-icon.png" alt="Project Select Dropdown Icon" />
                            <select class="menu-item14" id="memberProjectSelect">
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
                            <img src="../images/select-icon.png" alt="Project Select Dropdown Icon" />
                            <select class="menu-item14" id="adminProjectSelect">
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

                                    
                                ?>
                            </select>
                        </div>
                    </nav>
                    <nav class="list6" id="ChatSelectNavigation">
                        <b class="title7" id="ProjectForum">
                            <b class="project-forum2">Project Forum</b>
                        </b>
                        <div class="select-container">
                            <img src="../images/chat-icon.png" alt="Project Chat Select Dropdown Icon" />
                            <select class="menu-item15" id="SelectChat">
                                <option value="" disabled selected>Select Chat</option>
                                <option value="chat1">- ScorpioSpeak</option>
                            </select>
                        </div>
                    </nav>
                    <a class="logout-link" href="../logout.php">LOGOUT</a>
                </nav>
            </section>
        </div>
            <section class="main-container">
                <form id="myForm" class="main-content" action="create_task.php?id=<?php echo $current_project_id?>" method="POST">
                    <div class="header">
                        <div class="header-title">Create a New Task</div>
                        <div class="project-id-box">
                            <div class="project-id-title"><?php echo $current_project_id?></div>
                        </div>
                    </div>
                    <div class="description-box">
                        <div class="description-title">Task Description</div>
                        <div class="description-inner-box">
                            <textarea id="projectDescription" name="task-description" placeholder="Enter task description..."></textarea>
                        </div>
                    </div>
                    <div class="task-inputs">
                        <div class="task-name">
                            <input type="text" placeholder="Task Name" id="taskName" name="task-title">
                        </div>
                        <div class="project-id-box">
                            <input type="text" class="project-id-value" value="<?php echo generate_task_id($dbc, 4) ?>" id="taskId" name="task-id">
                            <div class="project-id-value" onclick="generateTaskID()">Auto Generate</div>
                        </div>
                        <div class="task-date">
                            <input name="task-due-date" type="date" id="project-end-date" placeholder="DD/MM/YYYY">
                        </div>
                        <button name ="task-submit" class="task-button" value="Submit">
                            <div class="task-button-inner" onclick="createNewTask()">
                                <div class="icon">+</div>
                                <div>Create a New Task</div>
                            </div>
                        </button>
                    </div>
                    <div class="task-list" id="taskList">
                        <h2>Task List</h2>
                        <?php

                                $data = return_task_list($dbc, $current_project_id, $user_id);

                                while($task = mysqli_fetch_assoc($data)){

                                    echo "
                                    <div class='task-item'>
                                        <strong>Task Name:</strong> " . $task['taskName'] . "<br>
                                        <strong>Task ID:</strong> " . $task['taskID'] . "<br>
                                        <strong>Project ID:</strong> " . $task['projectID'] . "<br>
                                        <strong>Description:</strong> " . $task['description'] . "
                                    </div>
                                    ";

                                }
                        ?>
                    </div>
                </form>
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
        
        document.getElementById('SelectChat').addEventListener('change', function(e) {
        window.location.href = "../ChatPage/chat_page.php"
        });

        function generateTaskID() {
            window.top.location = window.top.location;
        }

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
    <?php mysqli_close($dbc); // Close database connection ?>
    </body>
</html>