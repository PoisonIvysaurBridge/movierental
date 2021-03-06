
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!--
    <title>SB Admin 2 - Bootstrap Admin Theme</title>
    -->
    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- MetisMenu CSS --><!--
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    -->
    <!-- DataTables CSS -->
    <link href="vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<?php include 'base.php' ?>
<style>.w3-bar-block a{text-decoration:none;}</style>
<?php startblock('content') ?>

    <?php 
        require_once('mysql_connect.php');
        if (isset($_POST['ok'])){
            unset($_SESSION['category']);
            unset($_SESSION['actor']);
            unset($_SESSION['categoryID']);
            unset($_SESSION['actorID']);
        }
    ?>
    <div id="wrapper">
    <div class="w3-container" style="text-align:center;">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 style="text-align: center; float: left;">View Inventory</h1>
                    <h4 style="float:left; margin: 30px 20px;">As of <?php echo date('Y-m-d'); ?></h4>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            STORE #<?php echo $_SESSION['storeID']; ?>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>Inventory ID</th>
                                        <th>Film Title</th>
                                        <th>Last Update</th>
                                        <th>Status</th>
                                        <th>Rented To</th>
                                        <th>Due On</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $query = "SELECT I.INVENTORY_ID, TITLE, I.LAST_UPDATE, S.DESCRIPTION, R.CUSTOMER_ID, C.FIRST_NAME, C.LAST_NAME,
                                                     ADDDATE(R.RENTAL_DATE, F.RENTAL_DURATION) AS DATEDUE     
                                            FROM INVENTORY I 
                                            LEFT JOIN FILM F ON F.FILM_ID = I.FILM_ID
                                            LEFT JOIN STATUS S ON S.STATUS = I.STATUS
                                            LEFT JOIN (SELECT CUSTOMER_ID, RENTAL_DATE, INVENTORY_ID FROM RENTAL WHERE RETURN_DATE IS NULL) R ON R.INVENTORY_ID = I.INVENTORY_ID
                                            LEFT JOIN CUSTOMER C ON C.CUSTOMER_ID = R.CUSTOMER_ID
                                            WHERE I.STORE_ID = '".$_SESSION['storeID']."'
                                            ORDER BY INVENTORY_ID";            
                                    $result=mysqli_query($dbc,$query);
                                    if (!$result) {
                                        echo mysqli_error($dbc);
                                    }
                                    while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
                                        echo "
                                        <tr>
                                            <td>{$row['INVENTORY_ID']}</td>
                                            <td>{$row['TITLE']}</td>
                                            <td>{$row['LAST_UPDATE']}</td>
                                            <td>{$row['DESCRIPTION']}</td>
                                            <td>{$row['CUSTOMER_ID']} {$row['FIRST_NAME']} {$row['LAST_NAME']}</td>
                                            <td>{$row['DATEDUE']}</td>  
                                        </tr>
                                        ";
                                    }
                                ?>
                            </tbody>
                            </table>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
        </div>
        <!-- /#page-wrapper -->
        </div>
    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="vendor/metisMenu/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="vendor/datatables-responsive/dataTables.responsive.js"></script>


    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });
    </script>

<?php endblock() ?>