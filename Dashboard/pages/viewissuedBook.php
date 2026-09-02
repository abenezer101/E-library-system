<html>

<head>


<title>Dashboard</title>

<!-- Bootstrap Core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">

<!-- MetisMenu CSS -->
<link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

<!-- DataTables CSS -->
 <link href="../css/dataTables/dataTables.bootstrap.css" rel="stylesheet">
 
<!-- DataTables Responsive CSS -->
<link href="../css/dataTables/dataTables.responsive.css" rel="stylesheet">

<!-- Custom CSS -->
<link href="../dist/css/sb-admin-2.css" rel="stylesheet">

<!-- Custom Fonts -->
<link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="../icofont/icofont.min.css">

</head>
<style>
        .search-bar {
			padding-bottom:20px;
			position: relative;
			left:90rem;
            max-width: 600px;
        }
        .search-bar .form-control {
            border-radius: 0;
        }
        .search-bar .btn {
            border-radius: 0;
            background-color: #007bff;
            color: #fff;
			height: 35px;
			position:absolute;
			left:20rem;
			top:0px;
        }
        .search-bar .btn:hover {
            background-color: #0056b3;
        }
</style>

<body>
<div id="wrapper">

<?php include 'includes/nav.php'?>


<div id="page-wrapper">
<div class="container-fluid">
<div class="row">
<div class=".col-lg-12">
               <h1 class="page-header">List of Books</h1>
			   <div class="container">
        <div class="search-bar">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search for books...">
                <div class="input-group-append">
                    <button class="btn btn-default" type="button">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
			   
                </div>
  </div>  

				<div class="row">
                        <div class=".col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Total Records of available books
                                </div>
								
								 <div class="panel-body">
                                    <div class="table-responsive">
									<table class="table table-striped table-bordered table-hover" id="dataTables-example">
									
									<?php

						include "dbconnect.php";

						$qry="select * from issued_books WHERE rented_book='1'";
						$result=mysqli_query($conn,$qry);


						echo"
						<thead>
						<tr>
							<th>Telegram ID</th>
							<th>Issued Name</th>
							<th>Book Title</th>
							<th>Publisher</th>
							<th>Date Published</th>
							<th>Isbn number</th>
							<th>Issued Date</th>
							<th>Due Date</th>
						</tr>
						</thead>";

						while($row=mysqli_fetch_array($result)){
						  echo"<tbody>
						  <tr class='gradeA'>
						  <td>".$row['user_id']."</td>
						  <td>".$row['issuer_name']."</td>
						  <td>".$row['title']."</td>
						  <td>".$row['publisher']."</td>
						  <td>".$row['year_published']."</td>
						  <td>".$row['unique_id']."</td>
						  <td>".$row['issued_date']."</td>
						  <td>".$row['issued_date']."</td>
						</tr>
						<tbody>
						";
						}

						?>
						</table>
									
				</div>
				</div>		
		</div>
		</div>	
		</div>	
		</div>
		</div>
		</div>

  <!-- jQuery -->
  <script src="../vendor/jquery/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="../vendor/metisMenu/metisMenu.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="../dist/js/sb-admin-2.js"></script>

<!-- DataTables JavaScript -->
<script src="../js/dataTables/jquery.dataTables.min.js"></script>
<script src="../js/dataTables/dataTables.bootstrap.min.js"></script>

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