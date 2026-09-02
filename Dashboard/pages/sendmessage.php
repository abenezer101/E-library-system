<html>

<head>

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

<style>
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    padding-top: 60px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgb(0,0,0);
    background-color: rgba(0,0,0,0.9);
}

.modal-content {
    margin: auto;
    display: block;
    max-width: 100%;
    max-height: 100%;
}

.close {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
}

.close:hover,
.close:focus {
    color: #bbb;
    text-decoration: none;
    cursor: pointer;
}

.table-wrapper {
    max-height: 400px; /* Adjust this value to your needs */
    overflow-y: auto;
}

.table-wrapper thead th {
    position: sticky;
    top: 0;
    background: #fff;
    z-index: 100;
}
</style>
</head>

<body>
<div id="wrapper">

<?php include 'includes/nav.php'?>

<div id="page-wrapper">
<div class="container-fluid">
<div class="row">
<div class=".col-lg-12">
    <h1 class="page-header">Send Message</h1>
</div>
</div>

<div class="row">
    <div class=".col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Total Records for registered users
                <div style="position:relative;right: -90rem;top:-10px;">
                    <form method="GET" action="sendmessage.php">
                        <label for="filter">filter by</label>
                        <select name="filter" id="filter" onchange="this.form.submit()">
                            <option value="All" <?php if(isset($_GET['filter']) && $_GET['filter'] == 'All') echo 'selected'; ?>>All users</option>
                            <option value="due_date" <?php if(isset($_GET['filter']) && $_GET['filter'] == 'due_date') echo 'selected'; ?>>Book rent deadline</option>
                            <option value="Not_registered" <?php if(isset($_GET['filter']) && $_GET['filter'] == 'Not_registered') echo 'selected'; ?>>Not registered</option>
                        </select>
                    </form>
                </div>
            </div>

            <div class="panel-body">
                <div class="table-wrapper">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">

                    <?php

                    include "dbconnect.php";
                    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'All';
                    $qry = "";

                    if ($filter == 'due_date') {
                        $qry = "SELECT u.first_name, u.phone_number,u.preferred_language, u.profile_picture, u.telegram_id, ib.due_date
                                FROM users u
                                JOIN issued_books ib ON u.telegram_id = ib.user_id
                                WHERE ib.due_date < CURDATE() AND u.registration_status='1'";
                    } elseif ($filter == 'Not_registered') {
                        $qry = "SELECT first_name, phone_number, preferred_language, profile_picture, telegram_id FROM users WHERE registration_status='0'";
                    } else {
                        $qry = "SELECT first_name, phone_number, preferred_language, profile_picture, telegram_id FROM users WHERE registration_status='1'";
                    }

                    $result = mysqli_query($conn, $qry);

                    echo "
                    <thead>
                    <tr>
                        <th>Telegram ID</th>
                        <th>Name</th>
                        <th>Phone Number</th>
                        <th>preffered Language</th>
                        <th>Profile Picture</th>
                        <th>Send Message</th>
                    </tr>
                    </thead>";

                    echo "<tbody>";

                    while ($row = mysqli_fetch_array($result)) {
                        echo "
                        <tr>
                            <td>".$row['telegram_id']."</td>
                            <td>".$row['first_name']."</td>
                            <td>".$row['phone_number']."</td>
                            <td>".$row['preferred_language']."</td>
                            <td><a href='".$row['profile_picture']."' class='image-link'><img src='".$row['profile_picture']."' alt='Profile pic' width='50'></a></td>
                            <td><a href='messageform.php?telegram_id=".$row['telegram_id']."'><button class='btn btn-success'>send message</button></a></td>
                        </tr>";
                    }

                    echo "</tbody>";

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

<!-- Wheelzoom JavaScript -->
<script src="../dist/js/wheelzoom.js"></script>

<!-- JavaScript for Image Preview Modal -->
<script>
$(document).ready(function () {
    // Get the modal
    var modal = document.getElementById("image-preview-modal");

    // Get the image and insert it inside the modal
    var modalImg = document.getElementById("preview-image");
    $(".image-link").click(function (event) {
        event.preventDefault();
        modal.style.display = "block";
        modalImg.src = this.href;

        // Apply wheelzoom to the modal image
        wheelzoom(modalImg);
    });

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks on <span> (x), close the modal
    span.onclick = function () {
        modal.style.display = "none";
        wheelzoom(modalImg, false);  // Remove wheelzoom effect when closing the modal
    }

    // When the user clicks anywhere outside of the modal content, close the modal
    modal.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
            wheelzoom(modalImg, false);  // Remove wheelzoom effect when closing the modal
        }
    }
});
</script>

</body>
<footer>
    <p>&copy; <?php echo date("Y"); ?><a href="https://enattechnology.web.app"> Enat Technologies</a></p>
</footer>

<style>
footer {
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
