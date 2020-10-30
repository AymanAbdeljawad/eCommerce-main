<?php
session_start();
$pagetitle = "mubere";
/*
 * ========================================================
 *  manage or users mumber page
 *  you can add | edite | delete mumber from hear
 * ========================================================
*/

if (isset($_SESSION['username'])) {
    include "init.php";
    $do = isset($_GET['do']) ? $_GET['do'] : "manage";
    $id = isset($_GET['id']) ? $_GET['id'] : "0";

    if ($do == "manage") {   //manger redirect


        $sql = "SELECT * FROM usres WHERE GroupID != 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
//        print_r($rows);

        ?>

        <h2 class="text-center title-muber">Manage User</h2>
        <div class="container">

            <div class="table-responsive">
                <table class="main-table table table-bordered text-center">
                    <tr class="bg-dark">
                        <th>#id</th>
                        <th>user Name</th>
                        <th>Email</th>
                        <th>Full Name</th>
                        <th>Regestar Date</th>
                        <th>Control</th>
                    </tr>
                    <?php


                    foreach ($rows as $row) {
                        ?>

                        <tr>
                            <td><?= $row['UserID'] ?></td>
                            <td><?= $row['Username'] ?></td>
                            <td><?= $row['Email'] ?></td>
                            <td><?= $row['Fullname'] ?></td>
                            <td><?= $row['RegusterDate'] ?></td>
                            <td>
                                <a href="?do=edite&id=<?= $row['UserID'] ?>&gid=0" class="btn btn-success">Edit</a>
                                <a href="?do=delete&id=<?= $row['UserID'] ?>" class="btn btn-danger confirm">Delete</a>

                            </td>
                        </tr>

                        <?php
                    }


                    ?>
                </table>
            </div>


            <a class="btn btn-primary w-25 d-block m-auto pr-3 font-weight-bold" href="?do=add">
                <i class="fa fa-plus"></i>
                Add New user
            </a>
        </div>


        <?php
    }






    elseif ($do == "edite") { //edit page
        $userId = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;

        $gid = isset($_GET['gid']) ? $_GET['gid'] : 1;
        echo $gid;
        $sql = "SELECT UserID, Username, Password, 	Email, Fullname FROM usres 
            WHERE    UserID = ? 
            AND 	GroupID = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($userId, $gid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        if ($count > 0) {
            ?>
            <h2 class="text-center title-muber">Edite User</h2>
            <div class="container edit">
                <form method="POST" action="muber.php?do=update&gid=<?= $gid ?>">
                    <input type="hidden" name="id" value="<?= $row['UserID'] ?>">
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-1 col-form-label">username</label>
                        <div class="col-sm-10 my-form-group">
                            <input type="text" class="form-control" id="staticusername" name="username"
                                   value="<?= $row['Username'] ?>"
                                   placeholder="username"
                                   autocomplete="off"
                                   required="required"
                                   minlength="5"
                                   maxlength="20">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword" class="col-sm-1 col-form-label">Password</label>
                        <div class="col-sm-10 my-form-group">
                            <input type="password" class="form-control" id="inputPassword" name="password"

                                   placeholder="Password"
                                   autocomplete="new-password">
                            <input type="hidden" name="oldpassword" value="<?= $row['Password'] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword" class="col-sm-1 col-form-label">email</label>
                        <div class="col-sm-10 my-form-group">
                            <input type="text" class="form-control" id="inputemail" name="email"
                                   value="<?= $row['Email'] ?>"
                                   placeholder="email"
                                   required="required"
                                   maxlength="60"
                                   minlength="20"
                                   autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword" class="col-sm-1 col-form-label">fullname</label>
                        <div class="col-sm-10 my-form-group">
                            <input type="text" class="form-control" id="inputfullname" name="fullname"
                                   value="<?= $row['Fullname'] ?>"
                                   placeholder="fullname"
                                   required="required"
                                   minlength="5"
                                   maxlength="20"
                                   autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">

                        <div class="col-sm-10 offset-1">
                            <input type="submit" class="btn btn-primary btn-block" name="edite" value="Edite">
                        </div>
                    </div>
                </form>
            </div>
            <?php
        } else {
            echo 'not user';
        }
    }//end edit







    elseif ($do == "update") { //update page
        ?>
        <h2 class="text-center title-muber">Update User</h2>
        <div class="container">
            <?php

            $gid = isset($_GET['gid']) ? $_GET['gid'] : 1;
            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                $userid = $_POST['id'];
                $username = $_POST['username'];
                $email = $_POST['email'];
                $fullname = $_POST['fullname'];
                $update = $_POST['edite'];
                $password = "";
                $password = empty($_POST['password']) ? $_POST['oldpassword'] : sha1($_POST['password']);

                $formErrors = array();

                if (strlen($username) > 20) {
                    $formErrors['usernameStrlen'] = "can not lanth > 20";
                    ?>
                    <div class="alert alert-primary" role="alert">
                        <?= $formErrors['usernameStrlen'] ?>
                    </div>
                    <?php
                }
                if (empty($username)) {
                    $formErrors['usernameEmpty'] = "can not empty usernameEmpty";
                    ?>
                    <div class="alert alert-primary" role="alert">
                        <?= $formErrors['usernameEmpty'] ?>
                    </div>
                    <?php
                }
                if (strlen($username) < 5) {
                    $formErrors['usernameStrlen'] = "can not lanth < 5";
                    ?>
                    <div class="alert alert-primary" role="alert">
                        <?= $formErrors['usernameStrlen'] ?>
                    </div>
                    <?php
                }
                if (empty($email)) {
                    $formErrors['emailEmpty'] = "can not empty emailEmpty";
                    ?>
                    <div class="alert alert-primary" role="alert">
                        <?= $formErrors['emailEmpty'] ?>
                    </div>
                    <?php
                }
                if (empty($fullname)) {
                    $formErrors['fullnameEmpty'] = "can not empty fullname";
                    ?>
                    <div class="alert alert-primary" role="alert">
                        <?= $formErrors['fullnameEmpty'] ?>
                    </div>
                    <?php
                }

                if (!empty($formErrors)) {
                    ?>
                    <a class="btn btn-primary" href="muber.php?do=edite&id=<?= $_SESSION['UserID'] ?>">back</a>
                    <?php
                }


                if (empty($formErrors)) {
                    //            var_dump($_POST);
                    $sql = "UPDATE usres SET
                            Username = ?,
                            Password =?,
                            Email = ?,
                            Fullname = ?
                            WHERE    UserID = ?
                            AND 	GroupID = ? LIMIT 1";

                    $stmt = $conn->prepare($sql);
                    $stmt->execute(array($username, $password, $email, $fullname, $userid, $gid));
                    $res = $stmt->rowCount();
//                            ?>


                    <div class="alert alert-info" role="alert">
                        Recourd Update <?= $res ?>
                    </div>
                    <?php
//                            echo $password;
                    if ($gid == 1) {
                        $_SESSION['username'] = $username;
                        $_SESSION['UserID'] = $userid;
                    }
                    //            header('Location: dashboard.php');
                    //            exit();
                }
//                        else{
//                            foreach ($formErrors as $formError) {
//                                echo $formError . "<br>";
//                            }
//                        }


            } else {
                "you can not browes the page diractory";
            }
            ?>
        </div>

        <?php
    }









    elseif ($do == "add") { // add item user in database
        ?>
        <h2 class="text-center title-muber">Add New User</h2>
        <div class="container edit">
            <form method="POST" action="?do=insert">
                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-1 col-form-label">username</label>
                    <div class="col-sm-10 my-form-group">
                        <input type="text" class="form-control" id="staticusername" name="username"
                               value=""
                               placeholder="username"
                               autocomplete="off"
                               required="required"
                               minlength="5"
                               maxlength="20">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword" class="col-sm-1 col-form-label">Password</label>
                    <div class="col-sm-10 my-form-group">
                        <input type="password" class="password form-control" id="inputPassword" name="password"
                               required="required"
                               placeholder="Password"
                               autocomplete="new-password">
                        <i class="show-class fa fa-eye fa-1x"></i>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword" class="col-sm-1 col-form-label">email</label>
                    <div class="col-sm-10 my-form-group">
                        <input type="text" class="form-control" id="inputemail" name="email"
                               value=""
                               placeholder="email"
                               required="required"
                               maxlength="60"
                               minlength="20"
                               autocomplete="off">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword" class="col-sm-1 col-form-label">fullname</label>
                    <div class="col-sm-10 my-form-group">
                        <input type="text" class="form-control" id="inputfullname" name="fullname"
                               value=""
                               placeholder="fullname"
                               required="required"
                               minlength="5"
                               maxlength="20"
                               autocomplete="off">
                    </div>
                </div>
                <div class="form-group row">

                    <div class="col-sm-10 offset-1">
                        <input type="submit" class="btn  btn-primary btn-block" name="add" value="Add">
                    </div>
                </div>
            </form>
        </div>
        <?php
    }


    elseif ($do == "insert") { //insert in database
        ?>
        <h2 class="text-center title-muber">Insert User</h2>
        <div class="container">
        <?php
        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $fullname = $_POST['fullname'];
            $password = $_POST['password'];
            $update = $_POST['add'];

            $hashPassword = sha1($_POST['password']);

            $formErrors = array();
            if (strlen($username) > 20) {
                $formErrors['usernameStrlen'] = "can not lanth > 20";
                ?>
                <div class="alert alert-primary" role="alert"><?= $formErrors['usernameStrlen'] ?></div><?php
            }
            if (empty($username)) {
                $formErrors['usernameEmpty'] = "can not empty usernameEmpty";
                ?>
                <div class="alert alert-primary" role="alert"><?= $formErrors['usernameEmpty'] ?></div><?php
            }
            if (strlen($username) < 5) {
                $formErrors['usernameStrlen'] = "can not lanth < 5";
                ?>
                <div class="alert alert-primary" role="alert"><?= $formErrors['usernameStrlen'] ?></div><?php
            }
            if (strlen($password) < 5) {
                $formErrors['passwordEmpty'] = "can not empty password";
                ?>
                <div class="alert alert-primary" role="alert"><?= $formErrors['passwordEmpty'] ?></div><?php
            }
            if (empty($email)) {
                $formErrors['emailEmpty'] = "can not empty emailEmpty";
                ?>
                <div class="alert alert-primary" role="alert"><?= $formErrors['emailEmpty'] ?></div><?php
            }
            if (empty($fullname)) {
                $formErrors['fullnameEmpty'] = "can not empty fullname";
                ?>
                <div class="alert alert-primary" role="alert"><?= $formErrors['fullnameEmpty'] ?></div><?php
            }
            if (!empty($formErrors)) {
                ?>
                <a class="btn btn-primary" href="muber.php?do=edite&id=<?= $_SESSION['UserID'] ?>">back</a>
                <?php
            }


            if (empty($formErrors)) {

                $sqlCheck = "SELECT * FROM usres WHERE Username = ? AND Email = ?";

                $stmtChack = $conn->prepare($sqlCheck);
                $stmtChack->execute(array($username, $email));
                $resChacks = $stmtChack->fetchAll();

                if ($stmtChack->rowCount() != 1) {
                    $sql = "INSERT INTO usres (Username, Password, Email, Fullname)
                            VALUES (:username, :password, :email, :fullname)";
                    $stmt = $conn->prepare($sql);
                    try{

                        $stmt->execute(array("username" => $username, "password" => $hashPassword, "email" => $email, "fullname" => $fullname));

                        $res = $stmt->rowCount();
                        ?>
                        <div class="alert alert-info" role="alert">Recourd Insert <?= $res ?></div><?php

                    }catch (PDOException $ex){
                        echo $ex->getMessage();
                    }

                }else{
                    ?>
                    <div class="alert alert-info" role="alert">Recourd Insert try use</div>
                    <?php
                }



                //                echo $password;
                //                $_SESSION['username'] = $username;
                //                $_SESSION['UserID'] = $userid;
                //                header('Location: dashboard.php');
                //                exit();

                //                echo "not error";
            }
            ?></div><?php

        }else{
            echo "no directore url vvvvvvvvvvvvvvv insert";
        }

    }

    elseif ($do === "delete"){

        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $sql = "DELETE FROM usres WHERE UserID = ?";
            $stmt = $conn->prepare($sql);
            $res = $stmt->execute(array($id));
            echo $res;  $sql = "DELETE FROM usres WHERE UserID = ?";
            $stmt = $conn->prepare($sql);
            $res = $stmt->execute(array($id));

            header("Location: muber.php");
        }






    }






    else  //chck url requst
    {
        echo "else page";
    }

    include $tpl . "footer.php";
} else  //chack session username
{
    header('Location: index.php');
    exit();
}

?>