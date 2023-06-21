<?php 

// include libraries & configuration 
include_once('config.php');
include_once('libs/functions.php');

// start session
session_start();
 
// initiate database connection
$db = mysqli_connect($db_host, $db_username, $db_password, $db_name);

// initialise user
$currentUser = getUser();

// if there's no current user, redirect to login
if($currentUser == null){
	redirect('login.php');	
}

// initialise messages
$messages = array(
	'error' => array(),
	'success' => array()
);

// main application logic
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	// add a joy project
	if(isset($_POST['add-project'])){
		$name = $_POST['name'];
		$description = $_POST['description'];
	
		if(empty($name)){
			array_push($messages['error'], 'You must enter a project name');
		} else if(empty($description)){
			array_push($messages['error'], 'You must enter a description');
		}
	
		if(empty($messages['error'])){
			$projectInsertSql = "INSERT INTO project (name, description, user_id) VALUES ('{$name}', '{$description}', {$currentUser['id']})";
			mysqli_query($db, $projectInsertSql);
			array_push($messages['success'], 'Successfully added project');
		}
	}
	
	// delete a joy project
	if(isset($_POST['delete-project'])){
		$projectId = $_POST['project-id'];
		
		$projectDeleteSql = "DELETE FROM project WHERE id = {$projectId} AND user_id = {$currentUser['id']}";
		mysqli_query($db, $projectDeleteSql);
		array_push($messages['success'], 'Successfully deleted project');
	}
	
	// update a joy project
	if(isset($_POST['update-project'])){
		$projectId = $_POST['project-id'];
		$name = $_POST['name'];
		$description = $_POST['description'];
		
		if(empty($name)){
			array_push($messages['error'], 'You must enter a project name');	
		} else if(empty($description)){
			array_push($messages['error'], 'You must enter a description');	
		}
		
		if(empty($messages['error'])){
			$projectUpdateSql = "UPDATE project SET name = '{$name}', description = '{$description}' WHERE id = {$projectId} AND user_id = {$currentUser['id']}";
			mysqli_query($db, $projectUpdateSql);
			array_push($messages['success'], 'Successfully updated project');
		}
	}
	
	// add a joy activity
	if(isset($_POST['add-activity'])){
		$projectId = $_POST['project-id'];
		$name = $_POST['name'];
		$description = $_POST['description'];
	
		if(empty($name)){
			array_push($messages['error'], 'You must enter a activity name');
		} else if(empty($description)){
			array_push($messages['error'], 'You must enter a description');
		}
	
		if(empty($messages['error'])){
			$activityInsertSql = "INSERT INTO activity (project_id, name, description) VALUES ({$projectId}, '{$name}', '{$description}')";
			mysqli_query($db, $activityInsertSql);
			array_push($messages['success'], 'Successfully added activity');
		}
	}
	
	// delete a joy acitivity
	if(isset($_POST['delete-activity'])){
		$activityId = $_POST['activity-id'];
		
		$activityDeleteSql = "DELETE FROM activity WHERE id = {$activityId}";
		mysqli_query($db, $activityDeleteSql);
		array_push($messages['success'], 'Successfully deleted activity');
	}
	
	// update a joy acitivty
	if(isset($_POST['update-activity'])){
		$activityId = $_POST['activity-id'];
		$name = $_POST['name'];
		$description = $_POST['description'];
		
		if(empty($name)){
			array_push($messages['error'], 'You must enter a activity name');	
		} else if(empty($description)){
			array_push($messages['error'], 'You must enter a description');	
		}
		
		if(empty($messages['error'])){
			$activityUpdateSql = "UPDATE activity SET name = '{$name}', description = '{$description}' WHERE id = {$activityId}";
			mysqli_query($db, $activityUpdateSql);
			array_push($messages['success'], 'Successfully updated activity');
		}
	}
	
	// add a joy item
	if(isset($_POST['add-item'])){
		$activityId = $_POST['activity-id'];
		$name = $_POST['name'];
		$description = $_POST['description'];
	
		if(empty($name)){
			array_push($messages['error'], 'You must enter a item name');
		} else if(empty($description)){
			array_push($messages['error'], 'You must enter a description');
		}
	
		if(empty($messages['error'])){
			$itemInsertSql = "INSERT INTO item (activity_id, name, description) VALUES ({$activityId}, '{$name}', '{$description}')";
			mysqli_query($db, $itemInsertSql);
			array_push($messages['success'], 'Successfully added item');
		}
	}
	
	// delete a joy item
	if(isset($_POST['delete-item'])){
		$itemId = $_POST['item-id'];
		
		$itemDeleteSql = "DELETE FROM item WHERE id = {$itemId}";
		mysqli_query($db, $itemDeleteSql);
		array_push($messages['success'], 'Successfully deleted item');
	}
	
	// update a joy item
	if(isset($_POST['update-item'])){
		$itemId = $_POST['item-id'];
		$name = $_POST['name'];
		$description = $_POST['description'];
		
		if(empty($name)){
			array_push($messages['error'], 'You must enter a item name');	
		} else if(empty($description)){
			array_push($messages['error'], 'You must enter a description');	
		}
		
		if(empty($messages['error'])){
			$itemUpdateSql = "UPDATE item SET name = '{$name}', description = '{$description}' WHERE id = {$itemId}";
			mysqli_query($db, $itemUpdateSql);
			array_push($messages['success'], 'Successfully updated item');
		}
	}
}

// get all projects for the current user
$projectSql = "SELECT * FROM project WHERE user_id = {$currentUser['id']}";
$projects = mysqli_query($db, $projectSql);

// display alert messages
if(isset($_GET['alert'])){
	$alert = $_GET['alert'];
	switch($alert){
		case 'add-project':
			array_push($messages['success'], 'Successfully added project');
			break;	
		case 'delete-project':
			array_push($messages['success'], 'Successfully deleted project');
			break;
		case 'update-project':
			array_push($messages['success'], 'Successfully updated project');
			break;
		case 'add-activity':
			array_push($messages['success'], 'Successfully added activity');
			break;	
		case 'delete-activity':
			array_push($messages['success'], 'Successfully deleted activity');
			break;
		case 'update-activity':
			array_push($messages['success'], 'Successfully updated activity');
			break;
		case 'add-item':
			array_push($messages['success'], 'Successfully added item');
			break;	
		case 'delete-item':
			array_push($messages['success'], 'Successfully deleted item');
			break;
		case 'update-item':
			array_push($messages['success'], 'Successfully updated item');
			break;
	}
}

// display header
include_once('header.php');

// display the main application
include_once('main.php');

// display footer
include_once('footer.php');

// close database connection
mysqli_close($db);
?>