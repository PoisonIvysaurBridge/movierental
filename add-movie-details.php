<?php include 'base.php' ?>

<?php startblock('content') ?>
    <?php
        require_once('mysql_connect.php');
        //session_start();
        // SESSIONS
        //$categories = array(); // this initializes the an array to store the table details to be added to the session array later
        //$actors = array();

        $query = "SELECT TITLE, FILM_ID FROM FILM ORDER BY FILM_ID DESC LIMIT 1";
        $film = mysqli_query($dbc,$query);

        $query = "SELECT CATEGORY_ID, NAME FROM CATEGORY";
        $rscategory = mysqli_query($dbc,$query);
                                    
        $query = "SELECT ACTOR_ID, FIRST_NAME, LAST_NAME FROM ACTOR ORDER BY LAST_NAME";
        $rsactor = mysqli_query($dbc,$query);

        if(isset($_POST['addcategory'])){
            $category = $_POST['category'];
            $_SESSION['categoryID'][] = $category;
            $query = "SELECT NAME FROM CATEGORY WHERE CATEGORY_ID = '". $category . "';";
            $categoryNames = mysqli_query($dbc,$query);
            if(!$categoryNames)
                echo mysqli_error($dbc);
            else{
                while($row = mysqli_fetch_array($categoryNames, MYSQLI_ASSOC)){
                    $category = $row['NAME'];
                }
            }
            $_SESSION['category'][] = $category; 
        }

        else if(isset($_POST['addactor'])){
            $actor = $_POST['actor'];
            $_SESSION['actorID'][] = $actor;
            $query = "SELECT FIRST_NAME, LAST_NAME FROM ACTOR WHERE ACTOR_ID = '". $actor . "';";
            $actorNames = mysqli_query($dbc,$query);
            if(!$actorNames)
                echo mysqli_error($dbc);
            else{
                while($row = mysqli_fetch_array($actorNames, MYSQLI_ASSOC)){
                    $actor = $row['LAST_NAME'].", ".$row['FIRST_NAME'];
                }
            }
            $_SESSION['actor'][] = $actor; 
        }

        else if(isset($_POST['minuscategory'])){
            // removes from the choices
            $_SESSION['category'] = array_diff($_SESSION['category'], array($_POST['category']));
            //var_dump($_SESSION['category']);
            //var_dump(array($_POST['category']));
        }

        else if(isset($_POST['minusactor'])){
            // removes from the choices
            $_SESSION['actor'] = array_diff($_SESSION['actor'], array($_POST['actor']));
        }
        
    ?>
    <div class="w3-container"  style="margin: 0 30px;" >
        <h1 style="text-align: center;">Add New Movie</h1>
        <!-- ADD DETAILS BUTTON -->
        <div class="w3-center" style="float: right; margin:0 50px;">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input class="w3-button w3-teal w3-round" type="submit"  name="detail" value="Add Details">
            </form>
        </div>
        <div class="w3-half">
            <?php 
                if(!$film){
                    echo mysqli_error($dbc);
                }
                else{
                    while($row = mysqli_fetch_array($film, MYSQLI_ASSOC)){
                        echo "<h3>Film Title: {$row['TITLE']}</h3>";
                        $filmID = $row['FILM_ID'];
                    }
                }
            ?>
            <h3>Add Category</h3>
                    <button onclick="document.getElementById('id01').style.display='block'" class="w3-button">
                        <span class="table-add w3-text-green fa fa-plus w3-xxlarge"></span>
                    </button>
            <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
                <tr class="w3-dark-grey">
                    <th>Category</th>
                    <th></th>
                </tr>
                <!-- TABLE DETAILS -->
                <?php 
                    if(isset($_SESSION['category'])) {   // this displays the contents inside the CATEGORIES session array
                        foreach ($_SESSION['category'] as $key => $value) {
                            echo '<tr>';
                            echo '<td style="text-align: center">' . $value . '</td>';
                            echo '<td><form method="post" action="add-movie-details.php">
                                    <button type="submit" style="margin: 0 0 0 20px;" name="minuscategory">
                                        <span class="w3-text-red fa fa-minus w3-xlarge" onclick=""></span>
                                        <input type="hidden" name="category" value="' . htmlspecialchars($value) . '"/>
                                    </button>
                                    </form>
                                </td>';
                            echo '</tr>';

                            // note htmlspecialchars() is to prevent cross site scripting ;)
                        }
                    }
                ?>

            </table> <!-- CATEGORIES -->
            <br>
            <h3>Add Actors</h3>
                    <button onclick="document.getElementById('id02').style.display='block'" class="w3-button">
                        <span class="table-add w3-text-green fa fa-plus w3-xxlarge"></span>
                    </button>
            <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
                <tr class="w3-dark-grey">
                    <th>Actor</th>
                    <th></th>
                </tr>

                <!-- ACTOR TABLE DETAILS -->
                <?php
                    if(isset($_SESSION['actor'])) {   // this displays the contents inside the ACTORS session array
                        foreach ($_SESSION['actor'] as $key => $value) {
                            echo '<tr>';
                            echo '<td style="text-align: center">' . $value . '</td>';
                            echo '<td><form method="post" action="add-movie-details.php">
                                    <button type="submit" style="margin: 0" name="minusactor">
                                        <span class="w3-text-red fa fa-minus w3-xlarge" onclick=""></span>
                                        <input type="hidden" name="actor" value="' . htmlspecialchars($value) . '"/>
                                    </button>
                                    </form>
                                </td>';
                            echo '</tr>';

                            // note htmlspecialchars() is to prevent cross site scripting ;)
                        
                        }
                    }
                ?>
            </table><!-- ACTORS -->

            <!-- MODAL -->
            <div id="id01" class="w3-modal">
                <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px; margin:75px auto;">
                    <header class="w3-container w3-teal">
                    <div class="w3-center"><br>
                        <span onclick="document.getElementById('id01').style.display='none'" class="w3-button w3-xlarge w3-hover-red w3-display-topright" title="Close Modal">&times;</span>
                    </div>
                    <h3>Add Category</h3>
                    </header>

                    <form class="w3-container" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <div style="margin: 20px 150px">
                        <div class="w3-section" style="text-align: left">
                            <label><b>Category</b></label><br>
                            <select name="category">
                                <?php
                                    if (!$rscategory) {
                                        echo mysqli_error($dbc);
                                    }
                                    else{
                                        while($row = mysqli_fetch_array($rscategory, MYSQLI_ASSOC)){
                                            if(!in_array($row['NAME'], $_SESSION['category']))
                                                echo "<option value=\"{$row['CATEGORY_ID']}\">{$row['NAME']}</option>";
                                        }
                                    }
                                ?>
                            </select><br>
                            <input class="w3-button w3-teal w3-section w3-padding w3-round" type="submit" name="addcategory" value="Add"/>
                        </div>
                        </div>
                    </form>
                </div>
            </div><!-- CATEGORY MODAL -->
            <div id="id02" class="w3-modal">
                <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px; margin:75px auto;">
                    <header class="w3-container w3-teal">
                    <div class="w3-center"><br>
                        <span onclick="document.getElementById('id02').style.display='none'" class="w3-button w3-xlarge w3-hover-red w3-display-topright" title="Close Modal">&times;</span>
                    </div>
                    <h3>Add Actor</h3>
                    </header>

                    <form class="w3-container" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <div style="margin: 20px 150px">
                        <div class="w3-section" style="text-align: left">
                            <label><b>Actor</b></label><br>
                            <select name="actor">
                                <?php
                                    if (!$rsactor) {
                                        echo mysqli_error($dbc);
                                    }
                                    else{
                                        while($row = mysqli_fetch_array($rsactor, MYSQLI_ASSOC)){
                                            if(!in_array($row['LAST_NAME'].", ".$row['FIRST_NAME'], $_SESSION['actor']))
                                                echo "<option value=\"{$row['ACTOR_ID']}\">{$row['LAST_NAME']}, {$row['FIRST_NAME']}</option>";
                                        }
                                    }
                                ?>
                            </select><br>
                            <input class="w3-button w3-teal w3-section w3-padding w3-round" type="submit" name="addactor" value="Add"/>
                        </div>
                        </div>
                    </form>
                </div>
            </div><!-- ACTOR MODAL -->

        </div><!-- first half -->
        <?php
            if (isset($_POST['detail'])){   // if the user clicks add detail button
                $message=NULL;
                $date = date("Y-m-d H:i:s");
                if(!isset($message)){
                    
                    if (empty($_SESSION['category'])) {
                        $message .= "<b><p>No category added!";
                    }
                    else{
                        if (count(array_unique($_SESSION['category'])) != count($_SESSION['category'])){
                            $message .= "<b><p>Duplicate categories, please remove one!";
                        }
                    }
                    if (empty($_SESSION['actor'])) {
                        $message .= "<b><p>No actor added!";
                    }
                    else{
                        if (count(array_unique($_SESSION['actor'])) != count($_SESSION['actor'])){
                            $message .= "<b><p>Duplicate actors, please remove one!";
                        }
                    }
                    
                    if(!isset($message)){
                        foreach ($_SESSION['categoryID'] as $row => $col) {
                            $query = "INSERT INTO FILM_CATEGORY(FILM_ID, CATEGORY_ID, LAST_UPDATE)
                                        VALUES('{$filmID}', '{$col}', '{$date}')";
                            $result = mysqli_query($dbc, $query);
                            if (!$result) {
                                echo mysqli_error($dbc);
                            } 
                            else {
                                //header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/setup-ingredient.php");
                                $message .= "<b><p>Category details added! </b>";
                            }
                        }
                        foreach ($_SESSION['actorID'] as $row => $col) {
                            $query = "INSERT INTO FILM_ACTOR(ACTOR_ID, FILM_ID, LAST_UPDATE)
                                        VALUES('{$col}', '{$filmID}', '{$date}')";
                            $result = mysqli_query($dbc, $query);
                            if (!$result) {
                                echo mysqli_error($dbc);
                            } 
                            else {
                                //header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/setup-ingredient.php");
                                $message .= "<b><p>Actor details added! </b>";
                            }
                        }
                    }
                }
                if (isset($message)){
                        $message .= "<form method=\"post\" action=\"add-inventory.php\">
                                            <input class=\"w3-button w3-teal w3-round\" type=\"submit\" name=\"ok\" value=\"OK\">
                                    </form>";
                        echo '<div class="w3-grey w3-padding-16" style="margin: 20px 90px; padding:20px; float:left; width:30%; border-radius: 10px;">';
                        echo '<p><b>'.$message. '</b></p>';
                        echo '</div>';
                }
            }

        ?>
    </div>

<?php endblock() ?>