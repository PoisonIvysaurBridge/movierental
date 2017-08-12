<!DOCTYPE html>
<?php require_once 'ti.php' ?>
<?php
    session_start();
    if (!isset($_SESSION['user']))
        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/login.php");
?>
<html>
<header>
    <title>MOVIE RENTAL SYSTEM</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    
    <!--<link rel="stylesheet" type="text/css" href="../css/style.css">-->
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
    html,body,h1,h2,h3,h4,h5 {font-family: "Raleway", sans-serif}
    input, select{
        border-radius: 5px;
        margin: 10px 5px;
        padding: 5px 8px;
    }
    a{
        text-decoration: none;
    }
    </style>
    <?php startblock('style') ?>
    <?php endblock() ?>

    <script type="text/javascript" src="../js/filter.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
	<script type="text/javascript" src="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.6.0/underscore.js"></script>
    
</header>

<body class="w3-light-grey">
    
    <!-- Top container -->
    <div class="w3-bar w3-top w3-black w3-large" style="z-index:4">
        <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="w3_open();"><i class="fa fa-bars"></i>  Menu</button>
        <span class="w3-bar-item w3-right">Movie Rental System
            <img src="http://www.knotandgownfilms.com/wp-content/uploads/2016/03/Film-reel-public-domain.png" style="height:56px">
        </span>
    </div>


    <?php startblock('sidenav') ?>
    <!-- Sidebar/menu -->
        <nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index:3;width:300px;" id="mySidebar"><br>
        <?php startblock('userprofile') ?>
            <div class="w3-container w3-row">
                <div class="w3-col s4">
                <img src="https://poorishaadi.com/user-icon-png-pnglogocom.png" class="w3-circle w3-margin-right" style="width:46px; margin-top:20px;">
                </div>
                <div class="w3-col s8 w3-bar">
					<br>
                    <h4>Welcome, <?php echo $_SESSION['username']; ?>!</h4>
                    <!--<a href="registration.php" class="w3-bar-item w3-button"><i class="fa fa-user"></i>    Register new customer</a>-->
                    <a href="logout.php" class="w3-button w3-dark-grey">Logout</a>
                    
                </div>
            </div>
        <?php endblock() ?>
        <hr>
        <div class="w3-container">
            <h5>Dashboard</h5>
        </div>
        <div class="w3-bar-block">
            <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  Close Menu</a>
            <a href="home.php" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa-home fa-fw"></i>  Overview</a>
            <a href="registration.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-address-book fa-fw"></i>  New Customer</a>
            <a href="rent-film.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-cart-plus fa-fw"></i>  Rent Movie</a>
            <a href="return-film.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-cart-arrow-down fa-fw"></i>  Return Movie</a>
            <a href="view-inventory.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-bar-chart-o fa-fw"></i>  View Film Copies</a>
            <a href="add-inventory.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-plus fa-fw"></i>  Add Film Copy</a>
            <a href="add-movie.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-caret-square-o-right fa-fw"></i>  Add New Movie</a>
            <a href="edit-movie.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-edit fa-fw"></i>  Edit Movie</a>
            <!--<a href="delete-movie.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-close fa-fw"></i>  Delete Movie</a>-->
            
            
            <!--<a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-cog fa-fw"></i>  Help & Settings</a><br><br>-->
        </div>
        </nav>

    <?php endblock() ?>

    <!-- Overlay effect when opening sidebar on small screens -->
    <div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>


    <!-- !PAGE CONTENT! -->
    
    <div class="w3-main" style="margin-left:300px;margin-top:53px;">
        <br>
        <?php startblock('content') ?>
        <?php endblock() ?>
        
        <!-- Footer -->
        <footer class="w3-container w3-padding-16 w3-light-grey">
            <p align=center>Powered by PHP</a></p>
        </footer>

    <!-- End page content -->
    </div>

    <!-- SIDE NAV ACCORDION -->
    <script>

        // Get the Sidebar
        var mySidebar = document.getElementById("mySidebar");

        // Get the DIV with overlay effect
        var overlayBg = document.getElementById("myOverlay");

        // Toggle between showing and hiding the sidebar, and add overlay effect
        function w3_open() {
            if (mySidebar.style.display === 'block') {
                mySidebar.style.display = 'none';
                overlayBg.style.display = "none";
            } else {
                mySidebar.style.display = 'block';
                overlayBg.style.display = "block";
            }
        }

        // Close the sidebar with the close button
        function w3_close() {
            mySidebar.style.display = "none";
            overlayBg.style.display = "none";
        }
        
        // Accordion 
        function sales() {
            var x = document.getElementById("sales");
            if (x.className.indexOf("w3-show") == -1) {
                x.className += " w3-show";
            } else {
                x.className = x.className.replace(" w3-show", "");
            }
        }
        function inventory() {
            var x = document.getElementById("inventory");
            if (x.className.indexOf("w3-show") == -1) {
                x.className += " w3-show";
            } else {
                x.className = x.className.replace(" w3-show", "");
            }
        }
        function reports() {
            var x = document.getElementById("reports");
            if (x.className.indexOf("w3-show") == -1) {
                x.className += " w3-show";
            } else {
                x.className = x.className.replace(" w3-show", "");
            }
        }
        function archive() {
            var x = document.getElementById("archive");
            if (x.className.indexOf("w3-show") == -1) {
                x.className += " w3-show";
            } else {
                x.className = x.className.replace(" w3-show", "");
            }
        }
        function setup() {
            var x = document.getElementById("setup");
            if (x.className.indexOf("w3-show") == -1) {
                x.className += " w3-show";
            } else {
                x.className = x.className.replace(" w3-show", "");
            }
        }
        
    </script>

    <!-- MAKING CRF TABLES AND DR TALBES-->
    <script src="./stopExecutionOnTimeout-b2a7b3fe212eaa732349046d8416e00a9dec26eb7fd347590fbced3ab38af52e.js.download"></script><script src="./jquery.min.js.download"></script><script src="./jquery-ui.min.js.download"></script><script src="./bootstrap.min.js.download"></script><script src="./underscore.js.download"></script>
    <script>
        var $TABLE = $('#table');
        var $BTN = $('#export-btn');
        var $EXPORT = $('#export');

        $('.table-add').click(function () {
        var $clone = $TABLE.find('tr.hide').clone(true).removeClass('hide table-line');
        $TABLE.find('table').append($clone);
        });

        $('.table-remove').click(function () {
        $(this).parents('tr').detach();
        });

        $('.table-up').click(function () {
        var $row = $(this).parents('tr');
        if ($row.index() === 1) return; // Don't go above the header
        $row.prev().before($row.get(0));
        });

        $('.table-down').click(function () {
        var $row = $(this).parents('tr');
        $row.next().after($row.get(0));
        });

        // A few jQuery helpers for exporting only
        jQuery.fn.pop = [].pop;
        jQuery.fn.shift = [].shift;

        $BTN.click(function () {
        var $rows = $TABLE.find('tr:not(:hidden)');
        var headers = [];
        var data = [];
        
        // Get the headers (add special header logic here)
        $($rows.shift()).find('th:not(:empty)').each(function () {
            headers.push($(this).text().toLowerCase());
        });
        
        // Turn all existing rows into a loopable array
        $rows.each(function () {
            var $td = $(this).find('td');
            var h = {};
            
            // Use the headers from earlier to name our hash keys
            headers.forEach(function (header, i) {
            h[header] = $td.eq(i).text();   
            });
            
            data.push(h);
        });
        
        // Output the result
        $EXPORT.text(JSON.stringify(data));
        });
        //# sourceURL=pen.js
    </script>

    <!-- FILTER in SEARCH BAR -->
    <script>
        function filter() {
            // Declare variables 
            var input, filter, table, tr, td, i;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("inventoryTable");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1];
                if (td) {
                    if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                } 
            }
        }
	
    </script>
    <!-- FOR PRINTING -->
    <script>
    function printPage() {
        window.print();
    }
    </script>

    <!-- FOR IMAGE UPLOAD -->
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#image')
                        .attr('src', e.target.result)
                        .width(150)
                        .height(200);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    <!-- FOR CLOSING THE MODAL JUST BY CLICKING ANYWHERE OUTSIDE THE MODAL -->
    <script>
        // Get the modal
        var modal1 = document.getElementById('id01');
        var modal2 = document.getElementById('id02');

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal1) {
                modal1.style.display = "none";
            }
            else if (event.target == modal2) {
                modal2.style.display = "none";
            }
        }
    </script>
	
</body>
</html>
