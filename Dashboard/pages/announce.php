



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
                    <h1 class="page-header">Announcement</h1>
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
                                    <form role="form" action="index.php" method="post">
                                        <?php 

                                            if(isset($_POST['Author_name'])){
                                                include 'dbconnect.php';
                                                
                                                $Author_name = mysqli_real_escape_string($conn, $_POST["Author_name"]);
                                                $title = mysqli_real_escape_string($conn, $_POST["title"]);
                                                $message = mysqli_real_escape_string($conn, $_POST["message"]);

                                                // Set the time zone to UTC+3 and Get the current timestamp in UTC+3 and format it in 12-hour format
                                                date_default_timezone_set('Etc/GMT-3');
                                                $created_at = date("Y-m-d h:i:s A");

                                                // Get the current number of announcements
                                                $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM announcement");
                                                $row = mysqli_fetch_assoc($result);
                                                $new_id = $row['count'] + 1;

                                                $photo_path = $_FILES['photo_path']['name'];
                                                $photo_path_tmp = $_FILES['photo_path']['tmp_name'];
                                                $pdf_path_dir = "Announcement/" . $photo_path;
                                                move_uploaded_file($photo_path_tmp, $pdf_path_dir);
                                                $escaped_pdf_path_dir = mysqli_real_escape_string($conn, $pdf_path_dir);


                                                //code after connection is successfull
                                                $qry = "insert into announcement(id, Author_name,title,message,photo_path,created_at) values ('$new_id','$Author_name','$title','$message','$escaped_pdf_path_dir','$created_at')";
                                                $result = mysqli_query($conn,$qry); //query executes

                                                if(!$result){
                                                    echo"ERROR";
                                                }else {
                                                    echo" <div style='text-align: center'><h1>ANNOUNCEMENT MADE</h1>";
                                                    echo" <a href='index.php' div style='text-align: center'><h3>Go Back</h3>";

                                                }

                                                }else{
                                                    echo"<h3>YOU ARE NOT AUTHORIZED TO REDIRECT THIS PAGE. GO BACK to <a href='index.php'> DASHBOARD </a></h3>";
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
