<?php include 'base.php' ?>
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
<?php startblock('content') ?>
    <?php
        require_once('mysql_connect.php');
        // GET THE FILMS THAT ARE IN THE STORE AND ARE AVAILABLE FOR RENTING
        $query = "SELECT TITLE, F.FILM_ID 
                  FROM FILM F JOIN INVENTORY I ON I.FILM_ID = F.FILM_ID
                  WHERE I.STATUS = 2 AND I.STORE_ID = '".$_SESSION['storeID']."'
                  GROUP BY F.FILM_ID";
        $rsfilm = mysqli_query($dbc,$query);

        // GET THE LIST OF CUSTOMERS
        $query = "SELECT CUSTOMER_ID, FIRST_NAME, LAST_NAME 
                  FROM CUSTOMER 
                  WHERE ACTIVE = 1 AND getNumRents(CUSTOMER_ID) <3 
                  ORDER BY LAST_NAME, FIRST_NAME";
        $rscust = mysqli_query($dbc,$query);


        $message = NULL;
        if(isset($_POST['add'])){
            if(isset($_SESSION['filmctr'] )){
                $_SESSION['filmctr']++;
            }
            else{
                $_SESSION['filmctr'] = 1;
            }
            //echo $_SESSION['filmctr'];
            if($_SESSION['filmctr'] <= 3){
                $film = $_POST['film'];
                $_SESSION['filmID'][] = $film;
                $query = "SELECT TITLE FROM FILM WHERE FILM_ID = '". $film . "';";
                $filmTitles = mysqli_query($dbc,$query);
                if(!$filmTitles)
                    echo mysqli_error($dbc);
                else{
                    $row = mysqli_fetch_array($filmTitles, MYSQLI_ASSOC);
                    $film = $row['TITLE'];
                }
                $_SESSION['film'][] = $film; 
            }
            else{
                $message .= "<b><p>Maximum number of active rents for each customer is 3.</p><br>";
            }
            
        }
        else if(isset($_POST['minus'])){
            if(isset($_SESSION['filmctr'] )){
                $_SESSION['filmctr']--;
            }
            // removes from the choices
            $_SESSION['film'] = array_diff($_SESSION['film'], array($_POST['film']));
            $_SESSION['filmID'] = array_diff($_SESSION['filmID'], array($_POST['filmID']));
        }
        if (isset($_POST['confirm'])){
            // insert payment stuffs
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
                    $customer_id = $_POST['custID'];

                    $query = "INSERT INTO PAYMENT(PAYMENT_ID, CUSTOMER_ID, STAFF_ID, RENTAL_ID, AMOUNT, PAYMENT_DATE, LAST_UPDATE)
                                VALUES('0', '{$customer_id}', '{$_SESSION['user']}', '{$rental_id}', '{$amount}', '{$date}', '{$date}')";
                    $result = mysqli_query($dbc, $query);
                    if (!$result) {
                        echo mysqli_error($dbc);
                    } 
                    else{
                        $message .= "<p> Payment for Rental #{$rental_id} acknowledged!</p>";
                        $paymentReady = 1;
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
            unset($_SESSION['rates']);
            unset($_SESSION['inventoryIDs']);
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
                        <th>Movie Title</th>
                        <th></th>
                    </tr>
                    <!-- TABLE DETAILS -->
                    
                    <?php 
                        if(isset($_SESSION['film'])) {   // this displays the contents inside the CATEGORIES session array
                            foreach ($_SESSION['film'] as $key => $value) {
                                $filmID = $_SESSION['filmID'][$key];
                                echo '<tr>';
                                echo '<td style="text-align: left">' . $value . '</td>';
                                echo '<td><form method="post" action="rent-film.php">
                                        <button type="submit" style="margin: 0 0 0 20px;" name="minus">
                                            <span class="w3-text-red fa fa-minus w3-xlarge" onclick=""></span>
                                            <input type="hidden" name="film" value="' . htmlspecialchars($value) . '"/>
                                            <input type="hidden" name="filmID" value="' . htmlspecialchars($filmID) . '"/>
                                        </button>
                                        </form>
                                    </td>';
                                echo '</tr>';

                                // note htmlspecialchars() is to prevent cross site scripting ;)
                            }
                        }
                    ?>
                </table> <!-- FILMS -->

            </form>    
                
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
                                echo "<option value=\"{$row['CUSTOMER_ID']}\">{$row['LAST_NAME']}, {$row['FIRST_NAME']}</option>";
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
            <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px; margin:75px auto;">
                <header class="w3-container w3-teal">
                <div class="w3-center"><br>
                    <span onclick="document.getElementById('id01').style.display='none'" class="w3-button w3-xlarge w3-hover-red w3-display-topright" title="Close Modal">&times;</span>
                </div>
                <h3>Add Film</h3>
                </header>

                <form class="w3-container" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div style="margin: 20px 150px">
                    <div class="w3-section" style="text-align: left">
                        <label><b>Film</b></label><br>
                        <select name="film">
                            <?php
                                if (!$rsfilm) {
                                    echo mysqli_error($dbc);
                                }
                                else{
                                    while($row = mysqli_fetch_array($rsfilm, MYSQLI_ASSOC)){
                                        if(!in_array($row['TITLE'], $_SESSION['film']))
                                            echo "<option value=\"{$row['FILM_ID']}\">{$row['TITLE']}</option>";
                                    }
                                }
                            ?>
                        </select><br>
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
                            
                            foreach ($_SESSION['filmID'] as $row => $col) {
                                $title = $_SESSION['film'][$row];
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
                                    echo "<label style='text-align:left'>Film #{$col} - {$title}</label>
                                    <label style='text-align:center'>{$rental}</label><br>";
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
                        <input type="hidden" name="custID" value="<?php echo $_POST['customerID']; ?>"/>
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
                if(!isset($message)){
                    
                    if (empty($_SESSION['film'])) {
                        $message .= "<b><p>No film added!";
                    }
                    else{
                        if (count(array_unique($_SESSION['film'])) != count($_SESSION['film'])){
                            $message .= "<b><p>Duplicate films, please remove one!";
                        }
                    }
                    
                    if(!isset($message)){
                            //var_dump($_SESSION['film']);
                        $_SESSION['inventoryIDs'] = array();//$inventoryIDs = array();
                        foreach ($_SESSION['filmID'] as $row => $col) {
                            $title = $_SESSION['film'][$row];
                            $query = "SELECT INVENTORY_ID FROM INVENTORY WHERE FILM_ID = '".$col."' AND STATUS = 2 AND STORE_ID = '".$_SESSION['storeID']."' ORDER BY 1 LIMIT 1";
                            $result = mysqli_query($dbc, $query);
                            if (!$result) {
                                echo mysqli_error($dbc);
                            } 
                            else {
                                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                $inventoryID = $row['INVENTORY_ID'];
                                $query = "INSERT INTO RENTAL(RENTAL_ID, RENTAL_DATE, INVENTORY_ID, CUSTOMER_ID, RETURN_DATE, STAFF_ID, LAST_UPDATE)
                                            VALUES('0', '{$date}', '{$inventoryID}', '{$_POST['customerID']}', NULL, '{$_SESSION['user']}', '{$date}')";
                                $result = mysqli_query($dbc, $query);
                                if (!$result) {
                                    $message .= "<p>".mysqli_error($dbc);
                                    $keyfilm = array_search($title, $_SESSION['film']); 
                                    $keyfilmID = array_search($col, $_SESSION['filmID']); 
                                    unset($_SESSION['film'][$keyfilm]);
                                    unset($_SESSION['filmID'][$keyfilmID]);
                                } 
                                else {
                                    $paymentReady = 1;
                                    $message .= "<b><p>Film #{$col} - {$title} added! </b><br>";
                                    $_SESSION['inventoryIDs'][] = $inventoryID; //array_push($inventoryIDs, $inventoryID);
                                }
                            }
                        }
                        if(isset($message)){
                            $message .= "<input class=\"w3-button w3-teal w3-round\" type=\"submit\" onclick=\"document.getElementById('id02').style.display='block'\" name=\"checkout\" value=\"Checkout\">
                                            
                                            ";
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