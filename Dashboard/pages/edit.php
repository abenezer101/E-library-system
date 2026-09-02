

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard</title>

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="../icofont/icofont.min.css">


</head>

<body>

    <div id="wrapper">

        <?php include 'includes/nav.php'?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Melue'e E-library system</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            MESSAGE BOX
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" action="#" enctype="multipart/form-data" method="post">

                                    <?php
                                        include 'dbconnect.php';

                                        // Retrieve form data using POST method
                                        $first_name = $_POST["first_name"];    
                                        $username = $_POST["username"];
                                        $phone_number = $_POST["phone_number"];

                                        $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM users where registration_status='1'");
                                        $row = mysqli_fetch_assoc($result);
                                        $new_id = ($row['count']-1) + 1;




                                        $profile_picture = $_FILES['profile_picture']['name'];
                                        $profile_picture_tmp = $_FILES['profile_picture']['tmp_name'];
                                        $profile_picture_path_dir = "profile_pictures/" . $profile_picture;
                                        move_uploaded_file($profile_picture_tmp, $profile_picture_path_dir);
                                        $escaped_photo_path_dir = mysqli_real_escape_string($conn, $profile_picture_path_dir);                                        




                                        // Update query
                                        $qry = "UPDATE users SET id='$new_id', first_name='$first_name', username='$username', phone_number='$phone_number', profile_picture='$escaped_photo_path_dir'";
                                        $result = mysqli_query($conn, $qry); // Execute the query

                                        // Check the result of the query execution
                                        if(!$result){
                                            echo "ERROR: " . mysqli_error($conn);
                                        } else {
                                            echo "SUCCESSFULLY UPDATED";
                                            // Optionally redirect to another page
                                            // header("Location:editview.php");
                                        }
                                    ?>


                                  </form>
                                </div>
                                
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

</body>

<footer>
        <p>&copy; <?php echo date("Y"); ?><a href="https://enattechnology.web.app">  Enat Technologies</a></p>
    </footer>
	
	<style>
	footer{
   background-color: #424558;
    bottom: 0;
    left: 0;
    right: 0;
    height: 35px;
    text-align: center;
    color: #CCC;
}

footer p {
    padding: 10.5px;
    margin: 0px;
    line-height: 100%;
}
	</style>

</html>
