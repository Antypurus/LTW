<?php
    include('../includes/session.php');

    include('../includes/redirectLoggedIn.php');

    include('../database/connection.php');
    if(empty($_POST['location']))
        $_POST['location'] = " ";
    if(!empty($_POST['email']) && !empty($_POST['username']) && !empty($_POST['birthday']) && !empty($_POST['password']) && !empty($_POST['confirm_password'])  && !empty($_POST['name']))
    {
        //Enter the new user in the database
        $sql = "INSERT INTO users (email,username, password, name, registerDate, birthday, location, profilePicture) VALUES (:email, :username, :password, :name, :registerDate, :birthday, :location, :profilePicture)";
        $stmt = $conn->prepare($sql);
        if($stmt != null)
        {
            
            $PW = $_POST['password'];
            $PWC = $_POST['confirm_password'];
            $UserName = $_POST['username'];
            $email = $_POST['email'];
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':username', $UserName);
            $PWHashed = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $PWHashed);
            $stmt->bindParam(':name', $_POST['name']);
            $stmt->bindParam(':location', $_POST['location']);
            $birthday = strtotime($_POST['birthday']);
            $stmt->bindParam(':birthday', $birthday);
            $date = strtotime("now");
            $stmt->bindParam(':registerDate',  $date);
            if($_FILES["fileToUpload"]["name"] == null)
                $PPicDirectory = '../account/profilePictures/Default.PNG';
            else
            {
                $PPicDirectory = '../account/profilePictures/' . basename($_FILES["fileToUpload"]["name"]);
                $uploadOk = 1;
                $imageFileType = pathinfo($PPicDirectory,PATHINFO_EXTENSION);
                // Check if image file is a actual image or fake image
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                if($check !== false) 
                {
                    $uploadOk = 1;
                } 
                else 
                {
                    $uploadOk = 0;
                }
            
                if ($uploadOk == 0) 
                {
                    echo "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
                } 
                else  
                    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $PPicDirectory);
            }

            $stmt->bindParam(':profilePicture', $PPicDirectory);

            if($stmt->execute())
            {
                $records = $conn->prepare('SELECT id FROM users WHERE username = :username');
                $records->bindParam(':username', $UserName);
                $records->execute();
                $results = $records->fetch(PDO::FETCH_ASSOC);
                $_SESSION['user_id'] = $results['id'];
                header("Location: ../main/index.php");
            }
            else
            {
                header("Location: ../account/register.php");
                echo('Unexpected error');
            }
        }

    }

    include('../templates/header.php');
    include('registerForm.php');
    include('../templates/footer.php');
?>
