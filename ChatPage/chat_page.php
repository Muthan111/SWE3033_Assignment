<!DOCTYPE html>
<html>
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
    } else {
        $error_message = "You have already joined this project"; 
    }
}
?>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width" />
    <link rel="stylesheet" href="./chat_page.CSS" />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Inter:wght@200;400;500;600;700&display=swap"
    />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Noto Sans:wght@400&display=swap"
    />
</head>
<body onload="show_chat_history()">
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
                        <img src="../images/select-icon.png" alt="Project Select Dropdown Icon" />
                        <select class="menu-item14" id="memberProjectSelect">
                            <option value="" disabled selected>Select Project</option>
                            <?php
                                list($check, $data) = return_project_list($dbc, $user_id, 0); // Is not an admin

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


    <?php
    // Check if form is submitted
    if (isset($_POST['submit'])){
        // Escape user inputs for security
        $message = mysqli_real_escape_string($dbc, $_REQUEST['message']);
        date_default_timezone_set('Asia/Singapore');
        $timeSent = date('y-m-d h:ia');

        // Attempt insert query execution
        $sql = "INSERT INTO chats (userID, chatMsg, timeSent) VALUES ('$user_id','$message', '$timeSent') ORDER BY timeSent ASC;";
        if(mysqli_query($dbc, $sql)){
            // Message successfully inserted
            //echo "<p>Message sent successfully!</p>";
        } else {
            //echo "<p>ERROR: Message not sent!!!</p>";
        }
    }

    // Retrieve messages from the database
    $query = 
    "SELECT account.username, chats.chatMsg, chats.timeSent FROM chats
    INNER JOIN account ON chats.userID = account.userID ORDER BY timeSent ASC";
    $run = $dbc->query($query);
    ?>

    <div class="header-container">
        <header class="header">
        <div>
            <h2>ScorpioSpeak</h2>
        </div>
        </header>
    </div>

    <section class="main-container">



        <div class="chat-messages" id="chatMessages">
            <?php 
            $messageOwner = 0;
            if ($user_id){
            $messageOwner = 1;
            } else {
            $messageOwner = 2;
            }
            while ($row = $run->fetch_array()) :
                if ($messageOwner == 1) {
                    $first = $row;
                    
            ?>
            <div class="message-container">
                <div class="displayPicture">
                    <img src="../images/dp.png" class="dpClass">
                </div>
                <div class="message-content">
                    <div class="message-header">
                        <div class="displayUsername"><?php echo $row['username']?></div>
                        <div class="displayTimeSent"><?php echo $row['timeSent']?></div>
                    </div>
                    <div class="msgUser1">
                        <span><?php echo $row['chatMsg']?></span>
                    </div>
                </div>
            </div>
            <br></br>
    
            <?php  }
            endwhile;
            ?>
        </div>
    </section>

    <div class="footer-container">
    <footer>
        <form class="ScorpioChatForm" id="ScorpioChatForm" action="chat_page.php" method="POST">
            <div class="message-input-container">
                <textarea class="message" id="message" name="message" placeholder="Message Project Chat" required></textarea>
                <input type="submit" name="submit" value="Send" class="send-button">
            </div>
        </form>
    </footer>        
    </div>


    <section>
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
    </section>

    </main>

<script>
// JavaScript for handling the pop-up and form submission

var goHome = document.getElementById("HomeButton");
if (goHome) {
    goHome.addEventListener("click", function (e) {
        window.location.href = "../index.php";
        }
    );
}

var createProject = document.getElementById("ProjectCreateButton");
if (createProject) {
    createProject.addEventListener("click", function (e) {
        window.location.href = "../CreateProjectPage/create_project.php";
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

document.getElementById('SelectChat').addEventListener('change', function(e) {
    window.location.href = "../ChatPage/chat_page.php"
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

function show_chat_history(){
 
 var element = document.getElementById("chatMessages");
    element.scrollTop = element.scrollHeight;
  
 }
            
function refresh() {
    window.top.location = window.top.location;
}
</script>
</body>
</html>
