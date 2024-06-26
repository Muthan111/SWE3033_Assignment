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
                            <img src="select-icon.png" alt="Project Select Dropdown Icon" />
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
                        <button class="task-button">
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
    </main>
    <script>
        // var goHome = document.getElementById("HomeButton");
        // if (goHome) {
        //     goHome.addEventListener("click", function (e) {
        //         window.location.href = "../index.php";
        //         }
        //     );
        // }

        // document.getElementById('adminProjectSelect').addEventListener('change', function(e){
        //     window.location.href = "../DisplayProjectPage/display_project_page.php?id="+ this.value;
        //     });
        
        // document.getElementById('memberProjectSelect').addEventListener('change', function(e) {
        //     window.location.href = "../DisplayProjectPage/display_project_page.php?id="+ this.value;
        //     });

        //     function generateProjectID() {
        //     const projectID = 'PROJ-' + Math.floor(Math.random() * 1000000);
        //     document.getElementById('generatedProjectID').innerText = projectID;
        // }

        function generateTaskID() {
            window.top.location = window.top.location;
        }

        // function createNewTask() {
        //     const taskName = document.getElementById("taskName").value;
        //     const taskId = document.getElementById("taskId").value;
        //     const projectDescription = document.getElementById("projectDescription").value;
        //     const projectId = document.getElementById('generatedProjectID').innerText;
            
        //     if (taskName && taskId) {
        //         const taskList = document.getElementById("taskList");
        //         const taskItem = document.createElement("div");
        //         taskItem.className = "task-item";
        //         taskItem.innerHTML = `<strong>Task Name:</strong> ${taskName}<br><strong>Task ID:</strong> ${taskId}<br><strong>Project ID:</strong> ${projectId}<br><strong>Description:</strong> ${projectDescription}`;
        //         taskList.appendChild(taskItem);

        //         // Clear the input fields after creating the task
        //         document.getElementById("taskName").value = "";
        //         document.getElementById("taskId").value = "";
        //         document.getElementById("projectDescription").value = "";

        //         document.getElementById("myForm").submit(); 
        //     } else {
        //         alert("Please enter both task name and task ID");
        //     }
        // }
    </script>
    <?php mysqli_close($dbc); // Close database connection ?>
    </body>
</html>