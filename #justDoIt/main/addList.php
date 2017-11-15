<?php
    include('../includes/session.php');

    include('../database/connection.php');

    include('passing.php');

    if (isset($_POST['addListButton'])) 
    {
        if($_POST['listName']!="") 
        {
          $records = $conn->prepare('INSERT INTO toDoList (name, userid) VALUES (:name, :userid)');
          $records->bindParam(':userid', $_SESSION['user_id']);
          $records->bindParam(':name', $_POST['listName']);
          $records->execute();
          header("Location: ../main/index.php");
        }
    }
  ?>