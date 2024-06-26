<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Project Page</title>
    <style>
        /* Styles for the main layout and appearance */
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

        .container {
            width: 70%;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        /* Styles for the header */
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

        .project-details span,
        .project-details input {
            display: block;
            padding: 10px;
            font-size: 1em;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .project-details input {
            width: 100%;
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

        .project-description span,
        .project-description textarea {
            display: block;
            padding: 10px;
            font-size: 1em;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
        }

        .project-description textarea {
            height: 100px;
            overflow-y: auto;
        }

        /* Styles for tasks section */
        .tasks {
            margin-bottom: 20px;
        }

        .tasks h2 {
            margin-bottom: 10px;
        }

        .tasks ul {
            list-style: none;
            padding: 0;
        }

        .tasks ul li {
            display: grid;
            grid-template-columns: 1.5fr 2fr 1fr 1fr 1fr;
            align-items: center;
            padding: 10px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
            gap: 10px;
        }

        .tasks ul li input,
        .tasks ul li textarea,
        .tasks ul li select {
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
        }

        .tasks ul li textarea {
            resize: none;
            height: 50px;
        }

        /* Styles for task form */
        .task-form {
            display: flex;
            flex-direction: column;
            padding: 10px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .task-form label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .task-form input,
        .task-form select {
            padding: 5px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .task-form button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .task-form button:hover {
            background-color: #45a049;
        }

        /* Styles for buttons */
        .buttons {
            display: flex;
            justify-content: space-between;
        }

        .buttons button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .buttons button:hover {
            background-color: #45a049;
        }

        .buttons #updateProjectButton {
            background-color: #007BFF;
        }

        .buttons #updateProjectButton:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
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
                <input type="text" id="project-name-input" value="Example Project Name">
            </div>
            <div class="project-id">
                <label for="project-id">Project ID</label>
                <span id="project-id">12345</span>
            </div>
        </section>

        <!-- Section for project description -->
        <section class="project-description">
            <label for="project-description">Project Description</label>
            <textarea id="project-description-input">This is an example project description.</textarea>
        </section>

        <!-- Section for managing tasks -->
        <section class="tasks">
            <h2>Tasks</h2>
            <ul id="task-list">
                <!-- Tasks will be added here dynamically -->
            </ul>
            <div class="buttons">
                <button id="createTaskButton">Create Task</button>
                <button id="updateProjectButton">Update Project</button>
            </div>
        </section>
    </div>

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
                            $diff = $due_date - $now;

                            echo "{ name: \"". $task['taskName'] ."\", description: \"". $task['description'] ."\", status: \"". return_task_status($dbc, $task['taskID']) ."\", daysRemaining: ". round($diff / (60 * 60 * 24)) ." },";
                        }
                    ?>
                    // { name: "Task 1", description: "Description for Task One", status: "unassigned", daysRemaining: 200 },
                    // { name: "Task 2", description: "Description for Task Two", status: "ongoing", daysRemaining: 150 },
                    // { name: "Task 3", description: "Description for Task Three", status: "completed", daysRemaining: 0 },
                    // { name: "Task 4", description: "Description for Task Four", status: "expired", daysRemaining: -10 },
                ]
            };

            // Initialize project details
            document.getElementById('project-title').innerText = projectData.title;
            document.getElementById('project-start-date').innerText = `Start Date: ${projectData.startDate}`;
            document.getElementById('project-end-date').innerText = `End Date: ${projectData.endDate}`;
            document.getElementById('project-name-input').value = projectData.name;
            document.getElementById('project-description-input').value = projectData.description;

            const taskList = document.getElementById('task-list');
            projectData.tasks.forEach(task => {
                addTaskToList(taskList, task.name, task.description, task.status, task.dueDate);
            });

            // Event listener to display task creation form
            document.getElementById('createTaskButton').addEventListener('click', function() {
                window.location = "../CreateTaskPage/create_task.php?id=<?php echo $project_id;?>";
            });

            // Event listener to calculate and display days remaining for a task
            document.getElementById('task-due-date').addEventListener('change', function() {
                const dueDate = new Date(document.getElementById('task-due-date').value);
                const currentDate = new Date();
                const timeDiff = dueDate - currentDate;
                const daysRemaining = Math.ceil(timeDiff / (1000 * 3600 * 24));
                document.getElementById('task-days-remaining').value = daysRemaining;
            });

            // Event listener to save a new task
            document.getElementById('saveTaskButton').addEventListener('click', function() {
                const name = document.getElementById('task-name').value;
                const description = document.getElementById('task-description').value;
                const status = document.getElementById('task-status').value;
                const dueDate = document.getElementById('task-due-date').value;

                addTaskToList(taskList, name, description, status, dueDate);

                document.getElementById('taskFormContainer').style.display = 'none';
            });

            // Event listener to update project details
            document.getElementById('updateProjectButton').addEventListener('click', function() {
                const projectName = document.getElementById('project-name-input').value;
                const projectDescription = document.getElementById('project-description-input').value;

                document.getElementById('project-title').innerText = projectName;
                document.getElementById('project-name-input').value = projectName;
                document.getElementById('project-description-input').value = projectDescription;

                alert("Project updated successfully.");
            });
        });

        // Function to add a task to the task list(Create Task Button)
        function addTaskToList(taskList, name, description, status, dueDate) {
            const taskItem = document.createElement('li');
            
            //task name in form
            const taskName = document.createElement('input');
            taskName.type = 'text';
            taskName.value = name;
            taskName.addEventListener('change', function() {
                updateTaskName(taskName.value);
            });

            //task description in form
            const taskDescription = document.createElement('input');
            taskDescription.type = 'text';
            taskDescription.value = description;
            taskDescription.addEventListener('change', function() {
                updateTaskDescription(taskDescription.value);
            });

            //task status in form
            const taskStatus = document.createElement('select');
            const statuses = ["unassigned", "ongoing", "completed", "expired"];
            statuses.forEach(stat => {
                const option = document.createElement('option');
                option.value = stat;
                option.innerText = stat.charAt(0).toUpperCase() + stat.slice(1);
                if (stat === status) option.selected = true;
                taskStatus.appendChild(option);
            });
            taskStatus.addEventListener('change', function() {
                updateTaskStatus(taskName.value, taskStatus.value);
            });

            //Drop down calendar in form
            const taskDueDate = document.createElement('input');
            taskDueDate.type = 'date';
            taskDueDate.value = dueDate;
            taskDueDate.addEventListener('change', function() {
                const dueDate = new Date(taskDueDate.value);
                const currentDate = new Date();
                const timeDiff = dueDate - currentDate;
                const daysRemaining = Math.ceil(timeDiff / (1000 * 3600 * 24));
                updateTaskDaysRemaining(taskName.value, daysRemaining);
                taskDaysRemaining.value = daysRemaining;
            });

            //Display days remaining in form
            const taskDaysRemaining = document.createElement('input');
            taskDaysRemaining.type = 'text';
            const dueDateObj = new Date(dueDate);
            const currentDate = new Date();
            const timeDiff = dueDateObj - currentDate;
            const daysRemaining = Math.ceil(timeDiff / (1000 * 3600 * 24));
            taskDaysRemaining.value = daysRemaining;
            taskDaysRemaining.readOnly = true;

            taskItem.appendChild(taskName);
            taskItem.appendChild(taskDescription);
            taskItem.appendChild(taskStatus);
            taskItem.appendChild(taskDueDate);
            taskItem.appendChild(taskDaysRemaining);
            taskList.appendChild(taskItem);
        }

        //Update Task
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
    </script>
</body>
</html>





