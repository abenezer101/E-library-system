<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
   <div class="navbar-header" >
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" style="font-weight:600;position:relative;left:40px;" href="index.php"> <img src="http://localhost/Melu-e%20E-Library%20system/Dashboard/img/melu'e_logo round.jpg" style="height:36px;width:36px;position:absolute;top:6px;left:-29px;">Melu'e E-library Management System</a>
   </div>
   <!-- /.navbar-header -->
   <ul class="nav navbar-top-links navbar-right">
      <!-- /.dropdown -->
      <li class="dropdown">
         <a class="dropdown-toggle" data-toggle="dropdown" href="#">
         <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
         </a>
         <ul class="dropdown-menu dropdown-user">
            <!-- <li class="divider"></li> -->
            <li><a href="../logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
            </li>
         </ul>
         <!-- /.dropdown-user -->
      </li>
      <!-- /.dropdown -->
   </ul>
   <!-- /.navbar-top-links -->
   <div class="navbar-default sidebar" role="navigation">
      <div class="sidebar-nav navbar-collapse">
         <ul class="nav" id="side-menu">
            <style>
               .profile-container {
               display: flex;
               align-items: center;
               background-color: white;
               border: 1px solid #ccc;
               border-radius: 1px;
               padding: 10px 20px;
               box-shadow: 0 4px 8px rgba(0,0,0,0.1);
               height:5rem;
               background-color:#EBEBEB;
               }
               .profile-picture {
               width: 30px;
               height: 30px;
               border-radius: 50%;
               overflow: hidden;
               margin-right: 15px;
               }
               .profile-picture img {
               width: 100%;
               height: auto;
               }
               .profile-name {
               font-size: 1.2em;
               color: #428bca;
               }
            </style>
            <div class="profile-container">
               <div class="profile-picture">
                  <img src="http://localhost/Melu-e%20E-Library%20system/Dashboard/img/melu'e_logo.jpg" alt="">
               </div>
               <div class="profile-name">
                  Melu'e Admin
               </div>
            </div>
            <li>
               <a href="index.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
            </li>
            <li>
               <a href=""><i class="icofont-book-alt"></i>    Book collection <span class="fa arrow"></span></a>
               <ul class="nav nav-second-level">
                  <li>
                     <a href="addbook.php">Add Book</a>
                  </li>
                  <li>
                     <a href="viewbook.php">View Books </a>
                  </li>
                  <li>
                     <a href="editbook.php">Edit Book</a>
                  </li>
                  <li>
                     <a href="deletebook.php">Remove book</a>
                  </li>
               </ul>
               <!-- /.nav-second-level -->
            </li>
            <li>
               <a href=""><i class="fa fa-calendar-plus-o"></i>    Issued Books<span class="fa arrow"></span></a>
               <ul class="nav nav-second-level">
                  <li>
                     <a href="issueBook.php">Add book issue</a>
                  </li>
                  <li>
                     <a href="viewissuedBook.php">view book issued users</a>
                  </li>
                  <li>
                     <a href="editissuedBook.php">Edit book issued users</a>
                  </li>
               </ul>
               <!-- /.nav-second-level -->
            </li>

            <li>
               <a href="viewuser.php"><i class="fa fa-solid fa-users"></i> Users Registered</a>
            </li>
            <li>
               <a href="resgistration_approval.php"><i class="fa fa-user-plus"></i> Approve New user</a>
            </li>
            <li>
               <a href="editview.php"><i class="fa fa-edit fa-fw"></i> Edit User Details</a>
            </li>
            <li>
               <a href="deleteview.php"><i class="fa fa-user-times"></i> Delete User</a>
            </li>
            <li>
               <a href="sendmessage.php"><i class="fa fa-comments"></i> Send Message to User</a>
            </li>
            <li>
               <a href=""><i class="fa fa-bullhorn"></i> Announcements <span class="fa arrow"></span></a>
               <ul class="nav nav-second-level">
                  <li>
                     <a href="makeannouncement.php">Make Announcement</a>
                  </li>
                  <li>
                     <a href="viewannouncement.php">View Announcement</a>
                  </li>
                  <li>
                     <a href="editannounceform.php">Edit Announcement</a>
                  </li>
                  <li>
                     <a href="deleteannouncement.php">Remove Announcement</a>
                  </li>
               </ul>
            </li>
         </ul>
      </div>
      <!-- /.sidebar-collapse -->
   </div>
   <!-- /.navbar-static-side -->
</nav>
<body>
<!-- Image Preview Modal -->
<div id="image-preview-modal" class="modal">
	<span class="close">&times;</span>
	<img class="modal-content" id="preview-image">
</div>

<!-- JavaScript for Image Preview Modal -->
<script>
    $(document).ready(function() {
        // Get the modal
        var modal = document.getElementById("image-preview-modal");

        // Get the image and insert it inside the modal
        var modalImg = document.getElementById("preview-image");
        $(".image-link").click(function(event) {
            event.preventDefault();
            modal.style.display = "block";
            modalImg.src = this.href;
        });

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }
    });
</script>
</body>
