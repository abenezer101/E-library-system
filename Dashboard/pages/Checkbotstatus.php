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


    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
        }
        .status {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .success {
            background-color: #dff0d8;
            color: #3c763d;
        }
        .error {
            background-color: #f2dede;
            color: #a94442;
        }
        .last-checked {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }
        .live-button {
            display: block;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #28a745;
            margin: 20px auto;
            animation: glow 1s infinite alternate;
        }
        @keyframes glow {
            from {
                box-shadow: 0 0 5px #28a745;
            }
            to {
                box-shadow: 0 0 20px #28a745, 0 0 30px #28a745, 0 0 40px #28a745;
            }
        }
    </style>

    <script>
        // Reload the page every 60 seconds
        setInterval(function(){
            location.reload();
        }, 60000); // 60000 milliseconds = 60 seconds
    </script>    




  </head>
  <body>
        <div id="wrapper"> <?php include "includes/nav.php"; ?> 
            <div id="page-wrapper">
    


            
            <div class="container">
            <h1>Telegram Bot Status</h1>

            <?php
            // Replace with your bot's health check URL
            $botHealthUrl = 'http://192.168.56.1:5000/health';

            function checkBotStatus($url) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5); // 5 seconds timeout
                $response = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                return $http_code == 200;
            }

            // Check bot status
            $isBotRunning = checkBotStatus($botHealthUrl);
            $lastChecked = date("Y-m-d H:i:s");
            ?>

            <div class="status <?php echo $isBotRunning ? 'success' : 'error'; ?>">
                    <p>
                        <strong><?php echo $isBotRunning ? 'Bot is running successfully!' : 'Bot is not running.'; ?></strong>
                    </p>
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
    <p>&copy; <?php echo date(
        "Y"
    ); ?> <a href="https://enattechnology.web.app"> Enat Technologies</a>
    </p>
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