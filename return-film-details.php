<?php include 'base.php' ?>

<?php startblock('content') ?>
    <?php
        require_once('mysql_connect.php');
        if(!isset($_SESSION['customerID'])){
            $_SESSION['customerID'] = $_POST['customer'];
        }
        $query = "SELECT CUSTOMER_ID, FIRST_NAME, LAST_NAME FROM CUSTOMER WHERE CUSTOMER_ID = '".$_SESSION['customerID']."'";
        $result = mysqli_query($dbc, $query);
        if(!$result){
            echo mysqli_error($dbc);
        }
        else{
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $customerName = $row['LAST_NAME'].", ".$row['FIRST_NAME'];
        }


        // GET THE FILMS OF THE CUSTOMER THAT HAVE NOT YET BEEN RETURNED YET
        $query = "SELECT TITLE, F.FILM_ID, I.INVENTORY_ID
                FROM FILM F JOIN INVENTORY I ON I.FILM_ID = F.FILM_ID
                JOIN RENTAL R ON R.INVENTORY_ID = I.INVENTORY_ID
                WHERE R.CUSTOMER_ID = '".$_SESSION['customerID']."' AND R.RETURN_DATE IS NULL
                GROUP BY I.INVENTORY_ID, F.FILM_ID";
        $rsfilm = mysqli_query($dbc,$query);
        if (!$rsfilm) {
            echo mysqli_error($dbc);
        }

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
                $inventoryID = $_POST['inventoryID'];
                
                $query = "SELECT F.FILM_ID, F.TITLE FROM FILM F JOIN INVENTORY I ON I.FILM_ID = F.FILM_ID WHERE INVENTORY_ID = '".$inventoryID."'";
                $result = mysqli_query($dbc,$query);
                if(!$result){
                    echo mysqli_error($dbc);
                }
                else{
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $_SESSION['filmID'][] = $row['FILM_ID'];
                    $_SESSION['film'][] = $row['TITLE']; 
                    $_SESSION['inventoryIDs'][] = $inventoryID;
                }
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
            $_SESSION['inventoryIDs'] = array_diff($_SESSION['inventoryIDs'], array($_POST['inventoryID']));
        }
        
    ?>
		<div class="w3-container"  style="margin: 0 30px;" >
            <h1 style="text-align: center;">Return Movie Film</h1>
            
            <div class="w3-half">
                <h3>Customer ID #<?php echo $_SESSION['customerID']; ?>: <?php echo $customerName; ?></h3>

                <h3>Films to return:</h3>
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
                        if(isset($_SESSION['film'])) {   // this displays the contents inside the FILM session array
                            foreach ($_SESSION['film'] as $key => $value) {
                                $filmID = $_SESSION['filmID'][$key];
                                $inventoryID = $_SESSION['inventoryIDs'][$key];
                                echo '<tr>';
                                echo '<td style="text-align: left">' . $value . '</td>';
                                echo '<td><form method="post" action="return-film-details.php">
                                        <button type="submit" style="margin: 0 0 0 20px;" name="minus">
                                            <span class="w3-text-red fa fa-minus w3-xlarge" onclick=""></span>
                                            <input type="hidden" name="film" value="' . htmlspecialchars($value) . '"/>
                                            <input type="hidden" name="filmID" value="' . htmlspecialchars($filmID) . '"/>
                                            <input type="hidden" name="inventoryID" value="' . htmlspecialchars($inventoryID) . '"/>
                                        </button>
                                        </form>
                                    </td>';
                                echo '</tr>';

                                // note htmlspecialchars() is to prevent cross site scripting ;)
                            }
                        }
                    ?>
                </table> <!-- FILMS -->
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input type = 'submit' class="w3-button w3-teal w3-round" name='return' style="margin: 50px 0 0 0" value = 'Return Film(s)'><br>
                </form>
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
                            <select name="inventoryID">
                                <?php
                                    
                                        while($row = mysqli_fetch_array($rsfilm, MYSQLI_ASSOC)){
                                            if(!in_array($row['TITLE'], $_SESSION['film']))
                                                echo "<option value=\"{$row['INVENTORY_ID']}\">{$row['TITLE']}</option>";
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
                    <h3>Penalties Incurred</h3>
                    </header>
                    
                    <form class="w3-container" method="post" action="return-film.php">
                        <div style="margin: 20px;">
                            <?php
                                $total = 0;
                                $_SESSION['penalties'] = array();//$rates = array();
                                if(isset($_POST['penalty'])){
                                    ?>

                                    <script type="text/javascript">
                                        document.getElementById('id02').style.display='block';
                                    </script>
                                    
                                    <?php
                                    foreach ($_SESSION['inventoryIDs'] as $row => $col) {
                                        $title = $_SESSION['film'][$row];
                                        // GET THE DATE DUE
                                        $query = "SELECT ADDDATE(R.RENTAL_DATE, F.RENTAL_DURATION) AS DATEDUE, F.RENTAL_DURATION, R.RETURN_DATE, F.RENTAL_RATE,
                                                        DATEDIFF(R.RETURN_DATE, ADDDATE(R.RENTAL_DATE, F.RENTAL_DURATION)) AS DAYSDUE,
                                                        ROUND(CEILING(ADDDATE(R.RENTAL_DATE, F.RENTAL_DURATION) / RENTAL_DURATION) * RENTAL_RATE,2) AS PENALTY
                                                FROM FILM F
                                                JOIN INVENTORY I ON I.FILM_ID = F.FILM_ID
                                                JOIN RENTAL R ON R.INVENTORY_ID = I.INVENTORY_ID
                                                WHERE I.INVENTORY_ID = '".$col."' 
                                                ORDER BY DATEDUE DESC LIMIT 1";
                                                //ROUND(CEILING(ADDDATE(R.RENTAL_DATE, F.RENTAL_DURATION) / RENTAL_DURATION) * RENTAL_RATE,2) AS PENALTY
                                        $result = mysqli_query($dbc, $query);
                                        if (!$result) {
                                            echo mysqli_error($dbc);
                                        } 
                                        else {
                                            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                                            $dateDue = $row['DATEDUE'];
                                            $duration = $row['RENTAL_DURATION'];
                                            $return = $row['RETURN_DATE']; if($return == NULL) $return = date("Y-m-d H:i:s");
                                            
                                            $daysDue = $row['DAYSDUE'];     if($daysDue < 0) $daysDue = 0;
                                            $rate = $row['RENTAL_RATE'];
                                            $penalty = round(ceil($daysDue / $duration) * $rate, 2); //$row['PENALTY'];
                                            /*
                                            if($daysDue <= 0) {
                                                $daysDue = 0;
                                                $penalty = round(ceil($daysDue / $duration) * $rate, 2); //$row['PENALTY'];
                                            }
                                            else if($daysDue > 0){
                                                $penalty = $row['PENALTY'];
                                                //echo "more than 0 ".$penalty;
                                            }
                                            */
                                            
                                            
                                            $total += $penalty;
                                            $_SESSION['penalties'][] = $penalty;//array_push($rates, $rental);
                                            echo "<label style='text-align:left'>Inventory #{$col} - {$title}: </label><br>";
                                            echo "DATE DUE: ".$dateDue."<br>";
                                            echo "DAYS DUE: ".$daysDue;
                                            echo "<br> PENALTY: ".$penalty;
                                            echo "<br><br>";
                                        }
                                    }
                                }
                                
                            ?>
                        </div>
                        <div style="margin: 20px 150px">
                        <div class="w3-section" style="text-align: left">
                            
                            <h3>Total Penalties: <?php echo $total;?></h3>
                            <label>Amount Recieved:</label>
                            <input type="number" step="any" class="form-control" id="amountrecieved" min="<?php echo $total;?>" required>
                            <input class="w3-button w3-teal w3-section w3-padding w3-round" type="submit" name="done" value="Done"/>
                        </div>
                        </div>
                    </form>
                </div>
            </div><!-- PAYMENT MODAL -->

            <?php
                if (isset($_POST['return'])){   // if the user clicks RETURN button
                    $date = date("Y-m-d H:i:s");
                    $done = FALSE;
                    $goBackReturnFilm = FALSE;
                    $forPenalty = FALSE;
                    if(!isset($message)){
                    
                        if (empty($_SESSION['film'])) {
                            $message .= "<b><p>No film added!";
                        }
                        else{      // CHECK IF THE FILM WAS BORROWED FROM THE SAME STORE
                            foreach ($_SESSION['inventoryIDs'] as $row => $col) {
                                $title = $_SESSION['film'][$row];
                                $filmID = $_SESSION['filmID'][$row];
                                $query = "SELECT STORE_ID FROM INVENTORY WHERE INVENTORY_ID = '".$col."'";
                                $result = mysqli_query($dbc, $query);
                                if(!$result){
                                    echo mysqli_error($dbc);
                                }
                                else{
                                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                    if($row['STORE_ID'] != $_SESSION['storeID']){
                                        $message .= "Film #{$filmID} - {$title} was not rented in this store!";
                                        $goBackReturnFilm = TRUE;
                                    }
                                }
                            }
                        }
                        
                        if(!isset($message)){
                            
                            foreach ($_SESSION['inventoryIDs'] as $row => $col) {
                                $title = $_SESSION['film'][$row];
                                $query = "UPDATE RENTAL
                                          SET RETURN_DATE = '".$date."'
                                          WHERE INVENTORY_ID = '".$col."' AND RETURN_DATE IS NULL";
                                $result = mysqli_query($dbc, $query);
                                if(!$result){
                                    echo mysqli_error($dbc);
                                }
                                else{
                                    $forPenalty = TRUE;
                                    $message .= "<b><p>Inventory #{$col} - {$title} updated! </b><br>";
                                    
                                }
                            }
                            if(isset($message)){
                                $message .= "<form method=\"post\" action=\"return-film-details.php\">
                                            <input class=\"w3-button w3-teal w3-round\" type=\"submit\" name=\"penalty\" value=\"Check for Penalties\">
                                             </form>";
                            }
                        }
                    }
                    if (isset($message) && $forPenalty == FALSE && $goBackReturnFilm == FALSE){
                        $message .= "<form method=\"post\" action=\"return-film-details.php\">
                                            <input class=\"w3-button w3-teal w3-round\" type=\"submit\" name=\"ok\" value=\"OK\">
                                    </form>";
                        echo '<div class="w3-grey w3-padding-16" style="margin: 20px 90px; padding:20px; float:left; width:30%; border-radius: 10px;">';
                        echo '<p><b>'.$message. '</b></p>';
                        echo '</div>';
                    }
                    if($goBackReturnFilm){
                        $message .= "<form method=\"post\" action=\"return-film.php\">
                                            <input class=\"w3-button w3-teal w3-round\" type=\"submit\" name=\"ok\" value=\"OK\">
                                    </form>";
                        echo '<div class="w3-grey w3-padding-16" style="margin: 20px 90px; padding:20px; float:left; width:30%; border-radius: 10px;">';
                        echo '<p><b>'.$message. '</b></p>';
                        echo '</div>';
                    }
                    if($forPenalty){
                        echo '<div class="w3-grey w3-padding-16" style="margin: 20px 90px; padding:20px; float:left; width:30%; border-radius: 10px;">';
                        echo '<p><b>'.$message. '</b></p>';
                        echo '</div>';
                    }
                    
                    /* DEBUGGING PURPOSES
                    echo"film title: ";var_dump($_SESSION['film']); echo "<br>";
                    echo"film ID: ";var_dump($_SESSION['filmID']);echo "<br>";
                    echo"customer ID: ";var_dump($_SESSION['customerID']);echo "<br>";
                    echo"inventory ID: ";var_dump($_SESSION['inventoryIDs']);echo "<br>";*/
                }
            ?>
        </div>
<?php endblock() ?>