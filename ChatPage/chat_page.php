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
    } else {
        $error_message = "You have already joined this project"; 
    }
}
?>
<html>
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
<body>
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
                            <img src="../images/chat-icon.png" alt="Project Chat Select Dropdown Icon" />
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
            <div class="chat-container">
                <div class="chat-display" id="chat-display"></div>
                <div class="chat-input">
                    <input type="text" id="chat-input" placeholder="Type a message...">
                    <button id="send-button">Send</button>
                </div>
            </div>
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
        document.addEventListener('DOMContentLoaded', () => {
            const chatDisplay = document.getElementById('chat-display');
            const chatInput = document.getElementById('chat-input');
            const sendButton = document.getElementById('send-button');

            // Sample messages (simulating messages from other members)
            const messages = [
            { text: "Hello, team!", sender: "Alice", time: new Date('2024-06-20T09:30:00') },
                { text: "Hi Alice!", sender: "Bob", time: new Date('2024-06-20T14:30:00') },
                { text: "How's everyone doing today?", sender: "Charlie", time: new Date('2024-06-21T18:45:00') },
                { text: "Hey folks, any updates on the project?", sender: "David", time: new Date('2024-06-22T09:00:00') },
                { text: "Good morning! Just checking in.", sender: "Emily", time: new Date('2024-06-23T16:20:00') },
                { text: "Afternoon everyone! Any plans for the weekend?", sender: "Frank", time: new Date('2024-06-24T11:10:00') },
                { text: "Hey team, quick reminder about the meeting tomorrow.", sender: "Grace", time: new Date('2024-06-25T13:55:00') },
                { text: "Join code 1111", sender: "Admin", time: new Date('2024-06-26T11:20:00') },
            ];

            // Function to display messages
            function displayMessages() {
                chatDisplay.innerHTML = '';
                messages.sort((a, b) => a.time - b.time).forEach(message => {
                    const messageContainer = document.createElement('div');
                    messageContainer.classList.add('message-container');

                    const messageText = document.createElement('p');
                    messageText.classList.add('message-text');
                    messageText.textContent = `${message.sender}: ${message.text}`;

                    const messageMeta = document.createElement('div');
                    messageMeta.classList.add('message-meta');
                    messageMeta.textContent = `${formatMessageTime(message.time)} on ${formatMessageDate(message.time)}`;

                    messageContainer.appendChild(messageText);
                    messageContainer.appendChild(messageMeta);
                    chatDisplay.appendChild(messageContainer);
                });
                chatDisplay.scrollTop = chatDisplay.scrollHeight;
            }

            // Function to format message time
            function formatMessageTime(time) {
                return `${time.getHours()}:${('0' + time.getMinutes()).slice(-2)}`;
            }

            // Function to format message date
            function formatMessageDate(time) {
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                return time.toLocaleDateString('en-US', options);
            }

            // Function to add a new message
            function addMessage(text, sender) {
                const currentTime = new Date();
                const message = { text, sender, time: currentTime };
                messages.push(message);

                const messageContainer = document.createElement('div');
                messageContainer.classList.add('message-container');

                const messageText = document.createElement('p');
                messageText.classList.add('message-text');
                messageText.textContent = `${sender}: ${text}`;

                const messageMeta = document.createElement('div');
                messageMeta.classList.add('message-meta');
                messageMeta.textContent = `${formatMessageTime(currentTime)} on ${formatMessageDate(currentTime)}`;

                messageContainer.appendChild(messageText);
                messageContainer.appendChild(messageMeta);
                chatDisplay.appendChild(messageContainer);

                // Scroll to bottom
                chatDisplay.scrollTop = chatDisplay.scrollHeight;
            }

            // Event listener for send button
            sendButton.addEventListener('click', () => {
                const messageText = chatInput.value.trim();
                if (messageText) {
                    addMessage(messageText, 'You');
                    chatInput.value = '';

                }
            });

            // Initial display of messages
            displayMessages();
        });

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