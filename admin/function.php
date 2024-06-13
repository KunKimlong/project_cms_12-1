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


    function addNews(){
        global $connection;
        if(isset($_POST['btn-add-news'])){
            $title = $_POST['title'];
            $news_type = $_POST['news_type'];
            $category  = $_POST['category'];
            $thumbnail = $_FILES['thumbnail']['name'];
            $banner    = $_FILES['banner']['name'];
            $description = $_POST['description'];

            if(!empty($title) && !empty($news_type) && !empty($category) && !empty($thumbnail) && !empty($banner) && !empty($description)){
                $thumbnail = rand(1,99999).'_'.$thumbnail;
                $banner    = rand(1,99999).'_'.$banner;
                $pathThumbanil = 'assets/image/'.$thumbnail;
                $pathBanner = 'assets/image/'.$banner;

                move_uploaded_file($_FILES['thumbnail']['tmp_name'],$pathThumbanil);
                move_uploaded_file($_FILES['banner']['tmp_name'],$pathBanner);

                $sql = "INSERT INTO `news`(`title`, `thumbnail`, `banner`, `news_type`, `category`, `description`) 
                                    VALUES ('$title','$thumbnail','$banner','$news_type','$category','$description')";

                $rs  = $connection->query($sql);
                if($rs){
                    echo '
                        <script>
                            $(document).ready(function(){
                                swal({
                                title: "Success",
                                text: "News Insert successfully",
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
                                title: "error",
                                text: "News can not Insert",
                                icon: "error",
                                });
                            });
                        </script>
                    '; 
            }
        }
    }
    addNews();

    function viewNews(){
        global $connection;
        $sql = "SELECT * FROM `news` ORDER BY `id` DESC";
        $rs  = $connection->query($sql);
        while($row = mysqli_fetch_assoc($rs)){
            echo '
                <tr>
                    <td>'.$row['title'].'</td>
                    <td>'.$row['news_type'].'</td>
                    <td>'.$row['category'].'</td>
                    <td><img src="assets/image/'.$row['thumbnail'].'" width="120px"/></td>
                    <td><img src="assets/image/'.$row['banner'].'" width="120px"/></td>
                    <td>'.$row['viewer'].'</td>
                    <td>'.$row['postdate'].'</td>
                    <td width="150px">
                        <a href="update-news.php?id='.$row['id'].'" class="btn btn-primary">Update</a>
                        <button type="button" remove-id="'.$row['id'].'" class="btn btn-danger btn-remove" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            Remove
                        </button>
                    </td>
                </tr>
            ';
        }
    }

    function updateNews(){
        global $connection;
        if(isset($_POST['btn-update-news'])){
            $id  =  $_GET['id']; 
            $title = $_POST['title'];
            $news_type = $_POST['news_type'];
            $category  = $_POST['category'];
            $thumbnail = $_FILES['thumbnail']['name'];
            $banner    = $_FILES['banner']['name'];
            $description = $_POST['description'];

            if($thumbnail){
                $thumbnail = rand(1,99999).'_'.$thumbnail;
                $pathThumbanil = 'assets/image/'.$thumbnail;
                move_uploaded_file($_FILES['thumbnail']['tmp_name'],$pathThumbanil);
            }
            else{
                $thumbnail = $_POST['old_thumbnail'];
            }

            if($banner){
                $banner = rand(1,99999).'_'.$banner;
                $pathBanner = 'assets/image/'.$banner;
                move_uploaded_file($_FILES['banner']['tmp_name'],$pathBanner);
            }
            else{
                $banner = $_POST['old_banner'];
            }


            if(!empty($title) && !empty($news_type) && !empty($category) && !empty($thumbnail) && !empty($banner) && !empty($description)){

                $sql = "UPDATE `news` 
                        SET `title`='$title',`thumbnail`='$thumbnail',`banner`='$banner',`news_type`='$news_type',`category`='$category',`description`='$description' 
                        WHERE `id` = '$id'";
                $rs  = $connection->query($sql);
                if($rs){
                    echo '
                        <script>
                            $(document).ready(function(){
                                swal({
                                title: "Success",
                                text: "News Update successfully",
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
                                text: "News Update not success",
                                icon: "error",
                                });
                            });
                        </script>
                    '; 
            }
        }
    }
    updateNews();

    function deleteNews(){
        global $connection;
        if(isset($_POST['btn-delete-news'])){
            $id = $_POST['remove_id'];
            $sql = "DELETE FROM `news` WHERE `id`='$id'";
            $rs  = $connection->query($sql);
            if($rs){
                echo '
                <script>
                    $(document).ready(function(){
                        swal({
                        title: "Success",
                        text: "News Delete successfully",
                        icon: "success",
                        });
                    });
                </script>
            '; 
            }
        }
    }
    deleteNews();