<?php
    include('../session/session.php');
    include('../database/connection.php');
    include('../list_management/updateList.php');

    if(!empty($_POST['listID']))
    {
        $ListID = $_POST['listID'];

        if(!empty($_POST['taskName']) && !empty($_POST['taskDate']))
        {
            $records = $conn->prepare('INSERT INTO task (title, completed, toDoListId, expiring, description) VALUES (:title, "false", :toDoListId, :expiring, :description)');
            if($records != null)
            {
                $records->bindParam(':toDoListId', $ListID);
                $taskName = strip_tags($_POST['taskName']);
                $records->bindParam(':title', $taskName);
                $taskDesc = strip_tags($_POST['taskDescription']);
                $records->bindParam(':description', $taskDesc);
                $expiring = strtotime($_POST['taskDate']);
                $records->bindParam(':expiring', $expiring);
                $records->execute();
            }
        }
        header("Location: ../main");
    }
?>
