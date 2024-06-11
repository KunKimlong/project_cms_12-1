<!-- @import jquery & sweet alert  -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<?php

    $connection = new mysqli('localhost','root','','db_final_12-1');

    function register(){
        global $connection;
        if(isset($_POST['btn_register'])){
            $name = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $profile = $_FILES['profile']['name'];

            if(!empty($name) && !empty($email) && !empty($password) && !empty($profile)){
                $profile  = rand(1,99999).'_'.$profile;
                $path     = "assets/image/".$profile;
                move_uploaded_file($_FILES['profile']['tmp_name'],$path);

                $password= md5($password);

                $sql = "INSERT INTO `user`(`username`,`password`, `email`, `profile`) VALUES ('$name','$password','$email','$profile')";
                $rs  = $connection->query($sql);
                header('location:login.php');
            }

        }
    }
    register();

    session_start();
    
    function login(){
        global $connection;
        if(isset($_POST['btn_login'])){
            $name_email = $_POST['name_email'];
            $password   = $_POST['password'];
            $password   = md5($password);
            if(!empty($password) && !empty($name_email)){
                $sql = "SELECT * FROM `user` WHERE (`email`='$name_email' OR `username` = '$name_email') AND `password`='$password'";
                $rs  = $connection->query($sql);
                $row = mysqli_fetch_assoc($rs);
                if($row){
                    $_SESSION['user'] = $row['id'];
                    header('location:index.php');
                }
                else{
                    echo '
                        <script>
                            $(document).ready(function(){
                                swal({
                                title: "Error",
                                text: "Invalid username email or password",
                                icon: "error",
                                });
                            });
                        </script>
                    ';
                }
            }
            else{
                echo '
                        <script>
                            $(document).ready(function(){
                                swal({
                                title: "Error",
                                text: "Please input all data",
                                icon: "error",
                                });
                            });
                        </script>
                    ';
            }
            
        }
    }
    login();

    function addLogo(){
        global $connection;
        if(isset($_POST['btn-add-logo'])){
            $status = $_POST['status'];
            $thumbnail = $_FILES['thumbnail']['name'];
            if(!empty($status) && !empty($thumbnail)){
                $thumbnail = rand(1,99999).'_'.$thumbnail;
                $path = 'assets/image/'.$thumbnail;
                move_uploaded_file($_FILES['thumbnail']['tmp_name'],$path);
                $sql = "INSERT INTO `logo`(`thumbnail`, `status`) VALUES ('$thumbnail','$status')";
                $rs  = $connection->query($sql);
                if($rs){
                    echo '
                        <script>
                            $(document).ready(function(){
                                swal({
                                title: "Success",
                                text: "Logo add successfully",
                                icon: "success",
                                });
                            });
                        </script>
                    '; 
                }
            }
            else{
                echo '
                        <script>
                            $(document).ready(function(){
                                swal({
                                title: "Error",
                                text: "Please input all data",
                                icon: "error",
                                });
                            });
                        </script>
                    ';
            }
        }
    }
    addLogo();

    function viewLogo(){
        global $connection;
        $sql = "SELECT * FROM `logo` ORDER BY `id` DESC";
        $rs  = $connection->query($sql);
        while($row=mysqli_fetch_assoc($rs)){
            echo '
                <tr>
                    <td>'.$row['id'].'</td>
                    <td>'.$row['status'].'</td>
                    <td><img src="assets/image/'.$row['thumbnail'].'" width="150px"/></td>
                    <td width="150px">
                        <a href="update-logo.php?id='.$row['id'].'" class="btn btn-primary">Update</a>
                        <button type="button" remove-id="'.$row['id'].'" class="btn btn-danger btn-remove" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            Remove
                        </button>
                    </td>
                </tr>
            ';
        }
    }

    function updateLogo(){
        global $connection;
        if(isset($_POST['btn-update-logo'])){
            $id     = $_GET['id'];
            $status = $_POST['status'];
            $thumbnail = $_FILES['thumbnail']['name'];
            if($thumbnail){
                $thumbnail = rand(1,99999).'_'.$thumbnail;
                $path = 'assets/image/'.$thumbnail;
                move_uploaded_file($_FILES['thumbnail']['tmp_name'],$path);
            }
            else{
                $thumbnail =$_POST['old_thumbnail'];
            }
            if(!empty($status) && !empty($thumbnail)){
                $sql = "UPDATE `logo` SET `thumbnail`='$thumbnail',`status`='$status' WHERE `id`='$id' ";
                $rs  = $connection->query($sql);
                if($rs){
                    echo '
                        <script>
                            $(document).ready(function(){
                                swal({
                                title: "Success",
                                text: "Logo update successfully",
                                icon: "success",
                                });
                            });
                        </script>
                    '; 
                }
            }
        }
    }
    updateLogo();
    function deleteLogo(){
        global $connection;
        if(isset($_POST['btn-delete-logo'])){
            $id = $_POST['remove_id'];
            $sql = "DELETE FROM `logo` WHERE `id`='$id'";
            $rs  = $connection->query($sql);
            if($rs){
                echo '
                <script>
                    $(document).ready(function(){
                        swal({
                        title: "Success",
                        text: "Logo Delete successfully",
                        icon: "success",
                        });
                    });
                </script>
            '; 
            }
        }
    }
    deleteLogo();