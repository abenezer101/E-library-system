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
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

    <div id="wrapper">

        <?php include 'includes/nav.php'?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Add New book</h1>
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
                                        if(isset($_POST['title'])){
                                            $issued_user = $_POST["issued_user"];
                                            $title = $_POST["title"];    
                                            $publisher = $_POST["publisher"];
                                            $year_published = $_POST["year_published"];
                                            $unique_id = $_POST["unique_id"];
                                            $due_date = $_POST["due_date"];
                                            $issued_date = date("Y-m-d");

                                            include 'dbconnect.php';

                                            // Fetch the first name of the user
                                            $issued_name_query = "SELECT issuer_name FROM issued_books WHERE user_id='$issued_user' AND wait_list='1' AND rented_book='0'";
                                            $issued_name_result = mysqli_query($conn, $issued_name_query);
                                        
                                            if ($issued_name_result) {
                                                $issued_name_row = mysqli_fetch_assoc($issued_name_result);
                                                $issued_name = $issued_name_row['issuer_name'];
                                        
                                                // Update the issued_books table
                                                $qry = "UPDATE issued_books SET wait_list='0', issuer_name='$issued_name', title='$title', publisher='$publisher', year_published='$year_published', unique_id='$unique_id', due_date='$due_date', rented_book='1', issued_date='$issued_date' WHERE  user_id='$issued_user' AND wait_list='1'";
                                                $result = mysqli_query($conn, $qry);
                                        
                                                if ($result) {
                                                    // Prepare book data
                                                    $book_data = "Title: $title\nPublisher: $publisher\nYear Published: $year_published\nUnique ID: $unique_id\nDue Date: $due_date\nIssued Date: $issued_date";
                                                    
                                                    // Insert into messages table
                                                    $message_query = "INSERT INTO messages (user_id, issuer_name, content,message_type) VALUES ('$issued_user', '$issued_name', '$book_data','rented_book_msg')";
                                                    $message_result = mysqli_query($conn, $message_query);
                                                    
                                                    if ($message_result) {
                                                        echo "<div style='text-align: center'><h1>SUBMITTED SUCCESSFULLY</h1>";
                                                        echo "<a href='index.php' div style='text-align: center'><h3>Go Back</h3></a></div>";
                                                    } else {
                                                        echo "ERROR: Could not execute $message_query. " . mysqli_error($conn);
                                                    }
                                                } else {
                                                    echo "ERROR: Could not execute $qry. " . mysqli_error($conn);
                                                }
                                            } else {
                                                echo "ERROR: Could not execute $issued_name_query. " . mysqli_error($conn);
                                            }
                                        
                                            mysqli_close($conn);
                                            
                                        } else {
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
