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
                    <h1 class="page-header">Add Book Details</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Please fill up the form below:
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" action="addedbook.php" enctype="multipart/form-data" method="post">
                                     
                                        <div class="form-group">
                                            <label>Enter Title of the book</label>
                                            <input class="form-control" type="text" placeholder="Book Title" name="title" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Enter Book Publisher</label>
                                            <input class="form-control" type="text" placeholder="Chala " name="publisher" required>
                                        </div>                                        

                                        <!-- <div class="form-group">
                                            <label>Enter Year Published</label>
                                            <input class="form-control" type="date" name="year_published" required>
                                        </div> -->

                                        <div class="form-group">
                                            <label>Enter Year Published</label>
                                            <input class="form-control" type="number" name="year_published" min="1900" max="2099" step="1" required>
                                        </div>


                                        
                                        <div class="form-group" style="">
                                            <label >Upload cover page </label>
                                            <p>Files Supported: jpg,jpeg,png</p>
                                            <input type="file" name="thumbnail" accept=".jpg,.jpeg,.png" required>
                                        </div>
                                        <div class="form-group" style="">
                                            <label >Upload pdf file </label>
                                            <p>Files Supported: .pfd</p>
                                            <input type="file" name="pdf" accept=".pdf" required>
                                        </div>


                                       
                                    
										
                                        <button type="submit" class="btn btn-success btn-default" style="border-radius: 0%;">Submit Form</button>
                
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
