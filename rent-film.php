<?php include 'base.php' ?>
<!--
<script src = "ajaxmethods.js"></script>
<script>
    function resetCash(){
        $("#amountrecieved").val('');
        $("#discountID").val('');
        $("#discountRate").val('');
        $(".changeprice").empty();
    }
    function clickedMenuItem(me){
        //Changes the label of the MenuItem for the modal
        $("#watfood").empty();
        $("#watfood").append($(me).attr('mydesc'));

        $("#addToCart").attr({
            'myId' : $(me).attr('myId'),
            'myDesc': $(me).attr('myDesc'),
            'myPrice': $(me).attr('myPrice')
            });
    }
</script>
-->
<?php startblock('content') ?>
    <?php
        require_once('mysql_connect.php');
        // GET THE FILMS THAT ARE IN THE STORE AND ARE AVAILABLE FOR RENTING
        $query = "SELECT INVENTORY_ID, TITLE, F.FILM_ID 
                  FROM FILM F JOIN INVENTORY I ON I.FILM_ID = F.FILM_ID
                  WHERE I.STATUS = 2 AND I.STORE_ID = '".$_SESSION['storeID']."'";
                  //GROUP BY F.FILM_ID";
        $rsfilm = mysqli_query($dbc,$query);

        // GET THE LIST OF CUSTOMERS
        $query = "SELECT CUSTOMER_ID, FIRST_NAME, LAST_NAME 
                  FROM CUSTOMER 
                  WHERE ACTIVE = 1 AND getNumRents(CUSTOMER_ID) < 3 
                  ORDER BY LAST_NAME, FIRST_NAME";
        $rscust = mysqli_query($dbc,$query);


        $message = NULL;
        if(isset($_POST['add'])){
            if(!isset($_SESSION['film'])){
                $_SESSION['film'] = array();
            }
            if(count($_SESSION['film']) < 3){
                //$film = $_POST['film'];
                
                $inventoryCopy = $_POST['film'];    // film is the inventory ID now
                $query = "SELECT INVENTORY_ID FROM INVENTORY WHERE STATUS = 2 AND INVENTORY_ID = '{$inventoryCopy}' AND STORE_ID = '".$_SESSION['storeID']."'";
                $result = mysqli_query($dbc, $query);
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                if(empty($row)){
                    $message .= "<b><p>Inventory Copy not available in store.</p><br>";
                }
                else{
                    //get film id from inventory table based on the posted inventory ID
                    $query = "SELECT FILM_ID FROM INVENTORY WHERE INVENTORY_ID = '".$inventoryCopy."'";
                    $result = mysqli_query($dbc, $query);
                    if (!$result) {
                        echo mysqli_error($dbc);
                    } 
                    else{
                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                        if(!isset($_SESSION['film'])){
                            $_SESSION['film'] = array();
                        }
                        $_SESSION['filmID'][] = $row['FILM_ID'];//$film;
                        $query = "SELECT TITLE FROM FILM WHERE FILM_ID = '". $row['FILM_ID'] . "';";
                        $filmTitles = mysqli_query($dbc,$query);
                        if(!$filmTitles)
                            echo mysqli_error($dbc);
                        else{
                            $row = mysqli_fetch_array($filmTitles, MYSQLI_ASSOC);
                            $title = $row['TITLE'];
                        }
                        $_SESSION['film'][] = $title; 
                        $_SESSION['inventoryIDs'][] = $inventoryCopy;
                        //var_dump($_SESSION['film']);echo"<br>";
                        //var_dump($_SESSION['inventoryIDs']);echo"<br>";
                    }
                }
                
            }
            else{
                $message .= "<b><p>Maximum number of active rents for each customer is 3.</p><br>";
            }
            
        }
        else if(isset($_POST['minus'])){
            
            // removes from the choices
            $_SESSION['film'] = array_diff($_SESSION['film'], array($_POST['film']));
            $_SESSION['filmID'] = array_diff($_SESSION['filmID'], array($_POST['filmID']));
            $_SESSION['inventoryIDs'] = array_diff($_SESSION['inventoryIDs'], array($_POST['inventoryIDs']));
            if(count($_SESSION['film'] != count($_SESSION['inventoryIDs']))){
                unset($_SESSION['film']);
                unset($_SESSION['filmID']);
                unset($_SESSION['inventoryIDs']);
            }
            //var_dump($_SESSION['film']);echo"<br>";
            //var_dump($_SESSION['inventoryIDs']);echo"<br>";
        }
        if (isset($_POST['confirm'])){
            // insert payment stuffs
            if(!isset($_SESSION['inventoryIDs'])){
                $_SESSION['inventoryIDs'] = array();//$inventoryIDs = array();
            }
            $date = date("Y-m-d H:i:s");
            foreach ($_SESSION['inventoryIDs'] as $key => $value) {
                $amount = $_SESSION['rates'][$key];
                $query = "SELECT RENTAL_ID  FROM RENTAL WHERE INVENTORY_ID = '".$value."' AND RETURN_DATE IS NULL";
                $result = mysqli_query($dbc, $query);
                if (!$result) {
                    echo mysqli_error($dbc);
                } 
                else {
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $rental_id = $row['RENTAL_ID'];
                    $customer_id = $_SESSION['customerID'];
                    try{
                        $dbc->autocommit(FALSE); // i.e., start transaction
                        $query = "INSERT INTO PAYMENT(PAYMENT_ID, CUSTOMER_ID, STAFF_ID, RENTAL_ID, AMOUNT, PAYMENT_DATE, LAST_UPDATE)
                                    VALUES('0', '{$customer_id}', '{$_SESSION['user']}', '{$rental_id}', '{$amount}', '{$date}', '{$date}')";
                        $result = $dbc->query($query);//$result = mysqli_query($dbc, $query);
                        if (!$result) {
                            echo mysqli_error($dbc);
                            $result->free();
                            throw new Exception($dbc->error);
                        } 
                        else{
                            $message .= "<p> Payment for Rental #{$rental_id} acknowledged!</p>";
                            $paymentReady = 1;
                        }

                        // our SQL queries have been successful. commit them
                        // and go back to non-transaction mode.

                        $dbc->commit();
                        $dbc->autocommit(TRUE); // i.e., end transaction
                    }
                    catch(Exception $e){
                        // before rolling back the transaction, you'd want
                        // to make sure that the exception was db-related
                        $dbc->rollback(); 
                        $dbc->autocommit(TRUE); // i.e., end transaction   
                    }
                    
                }
            }/*
            if(isset($message)){
                $message .= "<form method=\"post\" action=\"rent-film.php\">
                                        <input class=\"w3-button w3-teal w3-round\" type=\"submit\" name=\"ok\" value=\"OK\">
                                </form>";
                    echo '<div class="w3-grey w3-padding-16" style="margin: 20px 90px; padding:20px; float:center; width:30%; border-radius: 10px;">';
                    echo '<p><b>'.$message. '</b></p>';
                    echo '</div>';
            }*/
            unset($_SESSION['film']);
            unset($_SESSION['filmID']);
            unset($_SESSION['inventoryIDs']);
            unset($_SESSION['rates']);
            unset($_SESSION['customerID']);
            $_SESSION['filmctr'] = 0;
        }
    ?>

    <div class="w3-container"  style="margin: 0 30px;" >
        <h1 style="text-align: center;">Rent Movie Film</h1>
        <div class="w3-half">
            
                <h3>Add Film</h3>
                        <button onclick="document.getElementById('id01').style.display='block'" class="w3-button">
                            <span class="table-add w3-text-green fa fa-plus w3-xxlarge"></span>
                        </button>
                <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
                    <tr class="w3-dark-grey">
                        <th>Inventory ID</th>
                        <th>Movie Title</th>
                        <th></th>
                    </tr>
                    <!-- TABLE DETAILS -->
                    
                    <?php 
                        if(isset($_SESSION['film'])) {   // this displays the contents inside the CATEGORIES session array
                            //var_dump($_SESSION['film']);
                            foreach ($_SESSION['film'] as $key => $value) {
                                $filmID = $_SESSION['filmID'][$key];
                                $inventoryID = $_SESSION['inventoryIDs'][$key];
                                echo '<tr>';
                                echo '<td style="text-align: left">' . $inventoryID . '</td>';
                                echo '<td style="text-align: left">' . $value . '</td>';
                                echo '<td><form method="post" action="rent-film.php">
                                        <button type="submit" style="margin: 0 0 0 20px;" name="minus">
                                            <span class="w3-text-red fa fa-minus w3-xlarge" onclick=""></span>
                                            <input type="hidden" name="film" value="' . htmlspecialchars($value) . '"/>
                                            <input type="hidden" name="filmID" value="' . htmlspecialchars($filmID) . '"/>
                                            <input type="hidden" name="inventoryIDs" value="' . htmlspecialchars($inventoryID) . '"/>
                                        </button>
                                        </form>
                                    </td>';
                                echo '</tr>';

                                // note htmlspecialchars() is to prevent cross site scripting ;)
                            }
                        }
                    ?>
                </table> <!-- FILMS -->

           
                
        </div><!-- first half -->    
        <div class="w3-half">
            <div class="w3-center" style="float: center; margin:50px 0;">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <h3>Customer ID: </h3>
                <select name="customerID">
                    <?php
                        if (!$rscust) {
                            echo mysqli_error($dbc);
                        }
                        else{
                            while($row = mysqli_fetch_array($rscust, MYSQLI_ASSOC)){
                                echo "<option value=\"{$row['CUSTOMER_ID']}\">ID #{$row['CUSTOMER_ID']} - {$row['LAST_NAME']}, {$row['FIRST_NAME']}</option>";
                            }
                        }
                    ?>
                </select><br>
                <!-- RENT BUTTON -->
                <input class="w3-button w3-teal w3-round" type="submit"  name="rent" value="Rent Film(s)">    
            </form>
            </div>
        </div>   

        <!-- FILM MODAL -->
        <div id="id01" class="w3-modal">
            <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px; margin:5px auto;">
                <header class="w3-container w3-teal">
                <div class="w3-center"><br>
                    <span onclick="document.getElementById('id01').style.display='none'" class="w3-button w3-xlarge w3-hover-red w3-display-topright" title="Close Modal">&times;</span>
                </div>
                <h3>Add Film</h3>
                </header>

                <form class="w3-container" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div style="margin: 20px 150px">
                    <div class="w3-section" style="text-align: left">
                        <label><b>Film Copy ID</b></label><br>
                        <input list="film" name="film"  required>
                        <datalist id="film" required>
                            <?php
                                if (!$rsfilm) {
                                    echo mysqli_error($dbc);
                                }
                                else{
                                    while($row = mysqli_fetch_array($rsfilm, MYSQLI_ASSOC)){
                                        if(!in_array($row['TITLE'], $_SESSION['film']))
                                            echo "<option value=\"{$row['INVENTORY_ID']}\">Copy ID #{$row['INVENTORY_ID']} - {$row['TITLE']}</option>";
                                    }
                                }
                            ?>
                        </datalist><br>
                        <input class="w3-button w3-teal w3-section w3-padding w3-round" type="submit" name="add" value="Add"/>
                    </div>
                    </div>
                </form>
            </div>
        </div><!-- FILM MODAL -->

        <!-- PAYMENT MODAL -->
        <div id="id02" class="w3-modal">
            <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px; margin:75px auto;">
                <header class="w3-container w3-teal">
                <div class="w3-center"><br>
                    <span onclick="document.getElementById('id02').style.display='none'" class="w3-button w3-xlarge w3-hover-red w3-display-topright" title="Close Modal">&times;</span>
                </div>
                <h3>Confirm Transaction</h3>
                </header>
                
                <form class="w3-container" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div style="margin: 20px;">
                        <?php
                            $total = 0;
                            $_SESSION['rates'] = array();//$rates = array();
                            if(isset($_POST['checkout'])){
                                    ?>

                                    <script type="text/javascript">
                                        document.getElementById('id02').style.display='block';
                                    </script>
                                    
                                    <?php
                                foreach ($_SESSION['filmID'] as $row => $col) {
                                    $title = $_SESSION['film'][$row];
                                    $copy = $_SESSION['inventoryIDs'][$row];
                                    $query = "SELECT RENTAL_RATE FROM FILM WHERE FILM_ID = '".$col."' ";
                                    $result = mysqli_query($dbc, $query);
                                    if (!$result) {
                                        echo mysqli_error($dbc);
                                    } 
                                    else {
                                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                        $rental = $row['RENTAL_RATE'];
                                        $total += $rental;
                                        $_SESSION['rates'][] = $rental;//array_push($rates, $rental);
                                        echo "<label style='text-align:left'>Film Copy #{$copy} - {$title}</label>
                                        <label style='text-align:center'>{$rental}</label><br>";
                                    }
                                }
                            }
                        ?>
                    </div>
                    <div style="margin: 20px 150px">
                    <div class="w3-section" style="text-align: left">
                        
                        <h3>Total: <?php echo $total;?></h3>
                        <label>Amount Recieved:</label>
                        <input type="number" step="any" class="form-control" id="amountrecieved" min="<?php echo $total;?>" required>
                        <input class="w3-button w3-teal w3-section w3-padding w3-round" type="submit" name="confirm" value="Confirm"/>
                    </div>
                    </div>
                </form>
            </div>
        </div><!-- PAYMENT MODAL -->

        <?php
            // This is to alert if the list of movie films are greater than 3
            if (isset($message)){
                $message .= "<form method=\"post\" action=\"rent-film.php\">
                                    <input class=\"w3-button w3-teal w3-round\" type=\"submit\" name=\"ok\" value=\"OK\">
                            </form>";
                echo '<div class="w3-grey w3-padding-16" style="margin: 20px 90px; padding:20px; float:left; width:30%; border-radius: 10px;">';
                echo '<p><b>'.$message. '</b></p>';
                echo '</div>';
            }

            if (isset($_POST['rent'])){   // if the user clicks RENT button
                $date = date("Y-m-d H:i:s");
                $paymentReady = 0;
                if(!isset($_SESSION['customerID'])){
                    $_SESSION['customerID'] = $_POST['customerID'];
                }
                if(!isset($message)){
                    // Check if empty
                    if (empty($_SESSION['film'])) {
                        $message .= "<b><p>No film added!";
                    }
                    // Check if same items in cart
                    else if (count(array_unique($_SESSION['film'])) != count($_SESSION['film'])){
                            $message .= "<b><p>Duplicate films! Only one copy is allowed to be rented.";
                            unset($_SESSION['film']);
                            unset($_SESSION['filmID']);
                            unset($_SESSION['inventoryIDs']);
                    }
                    // Check if same items in past rentals of the customer
                    else{
                        foreach ($_SESSION['film'] as $row => $col) {
                            $query = "SELECT R.RENTAL_ID, TITLE FROM RENTAL R 
                                  JOIN INVENTORY I ON I.INVENTORY_ID = R.INVENTORY_ID
                                  JOIN FILM F ON F.FILM_ID = I.FILM_ID
                                  WHERE TITLE = '".$col."' AND 
                                        CUSTOMER_ID = '".$_SESSION['customerID']."' AND
                                        RETURN_DATE IS NULL";
                            $result = mysqli_query($dbc, $query);
                            
                            if(!$result){
                                echo mysqli_error($dbc);
                            }
                            else{
                                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                if($row != NULL)
                                    $message .= "<p>{$col} has already been rented! Please return before borrowing again.";
                            }
                        }
                    }
                    
                    if(!isset($message)){
                            //var_dump($_SESSION['film']);
                        
                        
                        foreach ($_SESSION['filmID'] as $row => $col) {
                            $title = $_SESSION['film'][$row];
                            $inventoryID = $_SESSION['inventoryIDs'][$row];
                            /*
                            $query = "SELECT INVENTORY_ID FROM INVENTORY WHERE FILM_ID = '".$col."' AND STATUS = 2 AND STORE_ID = '".$_SESSION['storeID']."' ORDER BY 1 LIMIT 1";
                            $result = mysqli_query($dbc, $query);
                            if (!$result) {
                                echo mysqli_error($dbc);
                            } 
                            else {
                                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                */
                                try{
                                    $dbc->autocommit(FALSE); // i.e., start transaction
                                    $query = "INSERT INTO RENTAL(RENTAL_ID, RENTAL_DATE, INVENTORY_ID, CUSTOMER_ID, RETURN_DATE, STAFF_ID, LAST_UPDATE)
                                                VALUES('0', '{$date}', '{$inventoryID}', '{$_SESSION['customerID']}', NULL, '{$_SESSION['user']}', '{$date}')";
                                    $result = $dbc->query($query);//$result = mysqli_query($dbc, $query);
                                    if (!$result) {
                                        $message .= "<p>".mysqli_error($dbc);
                                        $keyfilm = array_search($title, $_SESSION['film']); 
                                        $keyfilmID = array_search($col, $_SESSION['filmID']); 
                                        $keyinventoryID = array_search($inventoryID, $_SESSION['inventoryIDs']); 
                                        unset($_SESSION['film'][$keyfilm]);
                                        unset($_SESSION['filmID'][$row]);
                                        unset($_SESSION['inventoryIDs'][$keyinventoryID]);
                                        throw new Exception($dbc->error);
                                    } 
                                    else {
                                        $paymentReady = 1;
                                        $message .= "<b><p>Copy #{$inventoryID} - {$title} added! </b><br>";
                                        //$_SESSION['inventoryIDs'][] = $inventoryID; //array_push($inventoryIDs, $inventoryID);
                                    }

                                    // our SQL queries have been successful. commit them
                                    // and go back to non-transaction mode.

                                    $dbc->commit();
                                    $dbc->autocommit(TRUE); // i.e., end transaction
                                }
                                catch(Exception $e){
                                    // before rolling back the transaction, you'd want
                                    // to make sure that the exception was db-related
                                    $dbc->rollback(); 
                                    $dbc->autocommit(TRUE); // i.e., end transaction   
                                }
                                
                            //}
                        }
                        if(isset($message)){
                            $message .= "<form method=\"post\" action=\"rent-film.php\">
                                            <input class=\"w3-button w3-teal w3-round\" type=\"submit\" name=\"checkout\" value=\"Checkout\">
                                             </form>";
                        }
                    }
                }
                if (isset($message) && $paymentReady == 0){
                    $message .= "<form method=\"post\" action=\"rent-film.php\">
                                        <input class=\"w3-button w3-teal w3-round\" type=\"submit\" name=\"ok\" value=\"OK\">
                                </form>";
                    echo '<div class="w3-grey w3-padding-16" style="margin: 20px 90px; padding:20px; float:left; width:30%; border-radius: 10px;">';
                    echo '<p><b>'.$message. '</b></p>';
                    echo '</div>';
                }
                if($paymentReady == 1){
                    echo '<div class="w3-grey w3-padding-16" style="margin: 20px 90px; padding:20px; float:left; width:30%; border-radius: 10px;">';
                    echo '<p><b>'.$message. '</b></p>';
                    echo '</div>';
                }
            }

        ?>
    </div>
<?php endblock() ?>