<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width" />
    <link rel="stylesheet" href="./Homepage.CSS" />
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
                <?php
                    // This section is to call upon the session variables and dependencies that will be used in this file
                    session_start();
                    $user_id = $_SESSION['user_id'];
                    $username = $_SESSION['username'];

                    include ('../PHP/project_functions.php');
                    include ('../PHP/mysqli_connect.php');
                ?>
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
            <div class="header">
                <h2 class="recent-projects" id="RecentProjectHeader">Recent Projects</h2>
            </div>
            <div class="project-list">
                <?php
                    list($check, $data) = return_project_list($dbc, $user_id, 2);

                    if ($check == 1){

                        while($project = mysqli_fetch_assoc($data)){
                            
                            $project_name = $project['projectName'];
                            $project_id = $project['projectID'];
                            $project_desc = $project['projectDescription'];
                            $start = $project['startDate'];
                            $due = $project['dueDate'];

                            echo 
                            "   
                                <button class='pressproject' id='ProjectOneButton' onclick='displayProject(\"".$project_id."\")'>
                                        <div class='project-info'>
                                            <div class='pressproject-label'>$project_name</div>
                                            <div class='projectid-label'>PID: $project_id</div>
                                        </div>
                                        <div class='dd-mm-yyyy-group'>
                                            <div class='dd-mm-yyyy2'>$start</div>
                                            <div class='div1'>-&gt;</div>
                                            <div class='dd-mm-yyyy3'>$due</div>
                                        </div>
                                    <div class='lorem-ipsum-dolor-sit-amet-co-container'>
                                        <div class='lorem-ipsum-dolor-container2'>
                                            <span class='lorem-ipsum-dolor-container3'>
                                                <p class='lorem-ipsum-dolor1'>$project_desc</p>
                                            </span>
                                        </div>
                                    </div>
                                    <div class='project-inner'>
                                        <div class='frame-child2'>
                                        </div>
                                    </div>
                                    <div class='project-child'>
                                        <div class='frame-child3'>
                                        </div>
                                    </div>
                                </button>
                            ";
                        }

                    } else{
                        echo "<p class='lorem-ipsum-dolor1'>Currently not part of any Project</p>";
                    }

                    mysqli_close($dbc); // Close database connection?>
            </div>
            <div class="footer">
                <button class="project-create" id="ProjectCreateButton">
                    <img class="CreateAddIcon" alt="" src="add-icon.png">
                    <div class="CreateProjectLabel">Create a New Project</div>
                </button> 
                
            </div>
        </section>
    </main>

    <div id="joinCodePopup" class="popup">
        <div class="popup-content">
            <span class="close">&times;</span>
            <form id="joinCodeForm">
                <input type="text" id="projectCode" placeholder="Enter Project Code" class="popupInputField" name="projectCode" required>
                <button type="submit">Join Project</button>
            </form>
        </div>
    </div>

    <script>
        var createProject = document.getElementById("ProjectCreateButton");
        if (createProject) {
            createProject.addEventListener("click", function (e) {
                window.location.href = "../CreateProjectPage/CreateProject.php";
                }
            );
        }
        function displayProject(projectID){
            window.location.href = "../DisplayProjectPage/display_project.php?id=" + projectID;
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

        // Handle the form submission
        document.getElementById("joinCodeForm").addEventListener("submit", function(event) {
            event.preventDefault();
            var projectCode = document.getElementById("projectCode").value;
            // Perform your AJAX request or form submission logic here
            // For example, you can redirect to a PHP page with the project code
            window.location.href = "../DisplayProjectPage/display_project.php?id=" + projectCode;
        });
    </script>
</body>
</html>
