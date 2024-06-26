<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Display</title>
    <style>
        /* Styles for the overall page layout and appearance */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }

        /* Container styles for the project display */
        .container {
            width: 70%;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        /* Header styles for project title and dates */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        header h1 {
            font-size: 2em;
        }

        .project-dates {
            font-size: 0.8em;
        }

        /* Styles for project details section */
        .project-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .project-details div {
            flex: 1;
            margin-right: 10px;
        }

        .project-details div:last-child {
            margin-right: 0;
        }

        .project-details label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .project-details span {
            display: block;
            padding: 10px;
            font-size: 1em;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        /* Styles for project description section */
        .project-description {
            margin-bottom: 20px;
        }

        .project-description label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .project-description span {
            display: block;
            padding: 10px;
            font-size: 1em;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            height: 100px;
            overflow-y: auto;
        }

        /* Styles for tasks section */
        .tasks {
            margin-bottom: 20px;
        }

        .tasks h2 {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        /* Styles for the task ID input and add task button */
        .tasks .task-input-container {
            position: relative;
            display: flex;
            align-items: center;
            width: 100%;
        }

        .tasks .task-input-container input {
            width: calc(100% - 100px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 25px 0 0 25px;
            border-right: none;
            font-size: 1em;
        }

        .tasks .task-input-container button {
            width: 100px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 0 25px 25px 0;
            background-color: #007BFF;
            color: white;
            cursor: pointer;
            font-size: 1em;
            border-left: none;
        }

        .tasks .task-input-container button:hover {
            background-color: #0056b3;
        }

        .tasks table {
            width: 100%;
            border-collapse: collapse;
        }

        .tasks th, .tasks td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .tasks th {
            background: #f9f9f9;
        }

        /* Styles for task status labels */
        .task-status {
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
            margin-right: 10px;
            display: inline-block;
        }

        .status-unassigned {
            background-color: grey;
        }

        .status-ongoing {
            background-color: yellow;
            color: black;
        }

        .status-completed {
            background-color: green;
        }

        .status-expired {
            background-color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1 id="project-title">Project One</h1>
            <div class="project-dates">
                <span id="project-start-date">Start Date: 2024-01-01</span> |
                <span id="project-end-date">End Date: 2024-12-31</span>
            </div>
        </header>
        <section class="project-details">
            <div class="project-name">
                <label for="project-name">Project Name</label>
                <span id="project-name">Example Project Name</span>
            </div>
            <div class="project-id">
                <label for="project-id">Project ID</label>
                <span id="project-id">12345</span>
            </div>
        </section>
        <section class="project-description">
            <label for="project-description">Project Description</label>
            <span id="project-description">This is an example project description.</span>
        </section>
        <section class="tasks">
            <h2>
                Tasks
            </h2>
            
            <form class="task-input-container" action="display_project.php?id=<?php echo $project_id ?>" method="post">
                <input type="text" id="taskIDInput" name="taskIDInput" placeholder="Task ID" oninput="enableAddButton()" >
                <button id="addTaskButton" disabled type="submit" value="Submit">Add Task</button>
            </form>
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
        </section>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data fetched from database
            const projectData = {
                title: "<?php echo $project_name;?>", // Redundant
                startDate: "<?php echo $start?>",
                endDate: "<?php echo $due?>",
                name: "<?php echo $project_name?>",
                id: "<?php echo $project_id?>",
                description: "<?php echo $project_desc?>",
                tasks: [
                    <?php

                        $data = return_task_list($dbc, $project_id, $user_id);

                        while($task = mysqli_fetch_assoc($data)){
                            $now = time(); // or your date as well
                            $due_date = strtotime($task['dueDate']);
                            $diff = $now - $your_date;

                            echo "{ name: ". $task['taskName'] .", description: ". $task['taskDescription'] .", 
                            status: ". return_task_status($dbc, $task['taskID']) .", daysRemaining: ". round($diff / (60 * 60 * 24)) ." },";
                        }
                    ?>
                    // { name: "Task 1", description: "Description for Task One", status: "unassigned", daysRemaining: 200 },
                    // { name: "Task 2", description: "Description for Task Two", status: "ongoing", daysRemaining: 150 },
                    // { name: "Task 3", description: "Description for Task Three", status: "completed", daysRemaining: 0 },
                    // { name: "Task 4", description: "Description for Task Four", status: "expired", daysRemaining: -10 },
                ]
            };

            // Populate the HTML elements with the project data
            document.getElementById('project-title').innerText = projectData.title;
            document.getElementById('project-start-date').innerText = `Start Date: ${projectData.startDate}`;
            document.getElementById('project-end-date').innerText = `End Date: ${projectData.endDate}`;
            document.getElementById('project-name').innerText = projectData.name;
            document.getElementById('project-id').innerText = projectData.id;
            document.getElementById('project-description').innerText = projectData.description;

            const taskList = document.getElementById('task-list');
            projectData.tasks.forEach(task => {
                // Add each task to the task list
                addTaskToList(taskList, task.name, task.description, task.status, task.daysRemaining);
            });
        });

        // Function to enable the "Add Task" button if the input field is not empty
        function enableAddButton() {
            const taskIDInput = document.getElementById('taskIDInput');
            const addTaskButton = document.getElementById('addTaskButton');
            addTaskButton.disabled = taskIDInput.value.trim() === "";
        }

        // // Helper function to add a task item to the task list
        // function addTaskToList(taskList, name, description, status, daysRemaining) {
        //     const taskRow = document.createElement('tr');
            
        //     const taskNameCell = document.createElement('td');
        //     taskNameCell.innerText = name;
        //     taskRow.appendChild(taskNameCell);
            
        //     const taskDescriptionCell = document.createElement('td');
        //     taskDescriptionCell.innerText = description;
        //     taskRow.appendChild(taskDescriptionCell);
            
        //     const taskStatusCell = document.createElement('td');
        //     const taskStatus = document.createElement('span');
        //     taskStatus.classList.add('task-status', `status-${status}`);
        //     taskStatus.innerText = status.charAt(0).toUpperCase() + status.slice(1);
        //     taskStatusCell.appendChild(taskStatus);
        //     taskRow.appendChild(taskStatusCell);

        //     const daysRemainingCell = document.createElement('td');
        //     daysRemainingCell.innerText = daysRemaining;
        //     taskRow.appendChild(daysRemainingCell);

        //     taskList.appendChild(taskRow);
        // }

        // // Function to handle adding a new task
        // function addTask() {
        //     const taskIDInput = document.getElementById('taskIDInput');
        //     const taskName = taskIDInput.value.trim();
            
        //     if (taskName === '') return; // If task name is empty, do nothing
            
        //     // Generate a mock ID for the new task (you can modify this as needed)
        //     const taskId = Math.floor(Math.random() * 10000) + 1;

        //     // Mock new task object
        //     const newTask = {
        //         name: `Task ${taskId}: ${taskName}`,
        //         description: "New Task Description", // Example description
        //         status: "unassigned", // Example status
        //         daysRemaining: 30 // Example days remaining
        //     };

        //     // Add new task to the task list
        //     addTaskToList(taskList, newTask.name, newTask.description, newTask.status, newTask.daysRemaining);

        //     // Clear input and disable button
        //     taskIDInput.value = '';
        //     enableAddButton();
        // }

        // // Event listener for Add Task button
        // document.getElementById('addTaskButton').addEventListener('click', addTask);
    </script>
</body>
</html>



