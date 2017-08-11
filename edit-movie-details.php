<?php include 'base.php' ?>

<?php startblock('content') ?>
    <?php
        //session_start();
        require_once('mysql_connect.php');
        $query="SELECT LANGUAGE_ID, NAME FROM LANGUAGE";
        $languages=mysqli_query($dbc,$query);
        $orig=mysqli_query($dbc,$query);

        if(isset($_POST['film']))
            $_SESSION['film'] = $_POST['film'];
        $query = "SELECT FILM_ID, TITLE, DESCRIPTION, RELEASE_YEAR, LANGUAGE_ID, ORIGINAL_LANGUAGE_ID, RENTAL_DURATION, 
                         RENTAL_RATE, LENGTH, REPLACEMENT_COST, RATING, SPECIAL_FEATURES
                  FROM FILM 
                  WHERE FILM_ID = '".$_SESSION['film']."'";
        $result = mysqli_query($dbc, $query);
        if(!$result){
            echo mysqli_error($dbc);
        }
        else{
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        }

        $query = "SELECT CATEGORY_ID, NAME FROM CATEGORY WHERE CATEGORY_ID NOT IN(SELECT CATEGORY_ID FROM FILM_CATEGORY WHERE FILM_ID = {$_SESSION['film']})";
        $rscategory = mysqli_query($dbc,$query);
                                    
        $query = "SELECT ACTOR_ID, FIRST_NAME, LAST_NAME FROM ACTOR WHERE ACTOR_ID NOT IN(SELECT ACTOR_ID FROM FILM_ACTOR WHERE FILM_ID = {$_SESSION['film']}) ORDER BY LAST_NAME";
        $rsactor = mysqli_query($dbc,$query);



    ?>
    <div class="w3-container"  style="margin: 0 30px;" >
		<h1 style="text-align: center;">Edit Movie: Film #<?php echo $row['FILM_ID']." - "; echo $row['TITLE']; ?></h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="w3-half">
            Film Title: <input type = 'text' name = 'title' required value="<?php echo $row['TITLE']; ?>"> <br>
            Film Description: <br> <textarea rows='5' cols='40' name ='desc' value="<?php echo $row['DESCRIPTION']; ?>"> </textarea> <br>
            Release Year: <input type = 'number' name = 'releaseyear' min="1990" value="<?php echo $row['RELEASE_YEAR']; ?>"> <br>
            Current Language: <select name = 'lang'> 
                                    <?php
                                        if (!$languages) {
                                            echo mysqli_error($dbc);
                                        }
                                        else {
                                            while($curLang=mysqli_fetch_array($languages,MYSQLI_ASSOC)){
                                                echo "
                                                    <option value=\"{$curLang['LANGUAGE_ID']}\">{$curLang['NAME']}</option>
                                                ";
                                            }
                                        }
                                    ?>
                              </select> <br>
            Original Language: <select name = 'origLang'> 
                                    <option selected value="0" > -- select an option -- </option>
                                    <?php
                                        if (!$orig) {
                                            echo mysqli_error($dbc);
                                        }
                                        else {
                                            while($origLang=mysqli_fetch_array($orig,MYSQLI_ASSOC)){
                                                echo "
                                                    <option value=\"{$origLang['LANGUAGE_ID']}\">{$origLang['NAME']}</option>
                                                ";
                                            }
                                        }
                                    ?>
                               </select> <br>
            Rental Duration (in days): <input type = 'number' name = 'duration' min="3" required value="<?php echo $row['RENTAL_DURATION']; ?>"> <br>
            Rental Rates (in USD): <input type='number' step='any' name = 'rate' min="4.99" required value="<?php echo $row['RENTAL_RATE']; ?>"><br>
            Movie Length (in minutes): <input type = 'number' name = 'length' min="1" value="<?php echo $row['LENGTH']; ?>"> <br>
            Replacement Cost (if lost or damaged): <input type = 'number' step = 'any' name = 'replace' min="19.99" required value="<?php echo $row['REPLACEMENT_COST']; ?>"> <br>
            Movie Maturity Rating: <select name = 'rating'> 
                                        <option value = 'G' selected> General Audience </option> 
                                        <option value = 'PG'> Parental Guidance </option> 
                                        <option value = 'PG13'> PG-13 </option> 
                                        <option value = 'R'> Restricted </option> 
                                        <option value = 'NC17'> Adults Only </option> 
                                    </select> <br><br>
            Special Features:<br>
            <input type='checkbox' name='features[]' value='Trailers'>Trailers<br>
            <input type='checkbox' name='features[]' value='Commentaries'>Commentaries<br>
            <input type='checkbox' name='features[]' value='Deleted Scenes'>Deleted Scenes<br>
            <input type='checkbox' name='features[]' value='Behind the Scenes'>Behind the Scenes<br>
            
            <input type="hidden" name="filmID" value="<?php echo $row['FILM_ID']; ?>">
            <input type = 'submit' class="w3-button w3-teal w3-round" name='edit' style="margin: 50px 0 0 0" value = 'Edit Movie'><br>
        </div>    <!-- FIRST HALF -->
        </form>
        
        <div class="w3-half">

            <h3>Add Category</h3>
                    <button onclick="document.getElementById('id01').style.display='block'" class="w3-button">
                        <span class="table-add w3-text-green fa fa-plus w3-xxlarge"></span>
                    </button>
            <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
                <tr class="w3-dark-grey">
                    <th>Category</th>
                    <th></th>
                </tr>
                <!-- CATEGORY TABLE DETAILS -->
                
                <?php 
                    $query = "SELECT NAME, FC.CATEGORY_ID FROM FILM_CATEGORY FC JOIN CATEGORY C ON C.CATEGORY_ID = FC.CATEGORY_ID WHERE FC.FILM_ID = {$_SESSION['film']}";
                    $dbCategories = mysqli_query($dbc, $query);
                    if(!$dbCategories){
                        echo mysqli_error($dbc);
                    }
                    else{
                        
                        while ($categoryTable = mysqli_fetch_array($dbCategories, MYSQLI_ASSOC)) {
                            echo '<tr>';
                            echo '<td style="text-align: left">' . $categoryTable['NAME'] . '</td>';
                            echo '<td><form method="post" action="edit-movie-details.php">
                                    <button type="submit" style="margin: 0 0 0 20px;" name="minuscategory">
                                        <span class="w3-text-red fa fa-minus w3-xlarge" onclick=""></span>
                                        <input type="hidden" name="category" value="' . $categoryTable['NAME'] . '"/>
                                        <input type="hidden" name="categoryID" value="' . $categoryTable['CATEGORY_ID'] . '"/>
                                    </button>
                                    </form>
                                </td>';
                            echo '</tr>';

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
                    $query = "SELECT LAST_NAME, FIRST_NAME, FA.ACTOR_ID FROM FILM_ACTOR FA JOIN ACTOR A ON A.ACTOR_ID = FA.ACTOR_ID WHERE FA.FILM_ID = {$_SESSION['film']}";
                    $dbActors = mysqli_query($dbc, $query);
                    if(!$dbActors){
                        echo mysqli_error($dbc);
                    }
                    else{
                        
                        while ($actorTable = mysqli_fetch_array($dbActors, MYSQLI_ASSOC)) {
                            echo '<tr>';
                            echo '<td style="text-align: left">' . $actorTable['LAST_NAME'] .', '.$actorTable['FIRST_NAME']. '</td>';
                            echo '<td><form method="post" action="edit-movie-details.php">
                                    <button type="submit" style="margin: 0 0 0 20px;" name="minusactor">
                                        <span class="w3-text-red fa fa-minus w3-xlarge" onclick=""></span>
                                        <input type="hidden" name="actor" value="' . $actorTable['LAST_NAME'] . '"/>
                                        <input type="hidden" name="actorID" value="' . $actorTable['ACTOR_ID'] . '"/>
                                    </button>
                                    </form>
                                </td>';
                            echo '</tr>';

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
            
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            </form>
        </div><!-- SECOND HALF -->
        

            
        <?php
            if (isset($_POST['edit'])){      
                $message=NULL;
                $date = date("Y-m-d H:i:s");

                $filmID = $_SESSION['film'];
                // REQUIRED / NOT NULL fields
                $title = $_POST['title'];           // title
                $lang = $_POST['lang'];             // language
                $duration = $_POST['duration'];     // duration
                $rate = $_POST['rate'];             // rate
                $replacement = $_POST['replace'];   // replacement cost
                $update = date("Y-m-d H:i:s");      // last updated

                if (!empty($_POST['desc'])){        // description
                    $desc = $_POST['desc'];
                }
                else{
                    $desc = NULL;
                }

                if (!empty($_POST['releaseyear']) && !$_POST['releaseyear'] == ""){ // release year
                    $year = (int) $_POST['releaseyear'];
                }
                else{
                    $year = NULL; 
                }
                
                if (($_POST['origLang']) != 0){    // original language
                    $orig = $_POST['origLang'];
                }
                else{
                    $orig = NULL;
                }

                if (!empty($_POST['length'])){    // length
                    $length = $_POST['length'];
                }
                else{
                    $length = NULL;
                }

                $rating = $_POST['rating'];     // rating

                $features = '';
                if(isset($_POST['features'])){
                    foreach($_POST['features'] as $feature){
                        if($feature == end($_POST['features']))// if last of the array
                            $features .= $feature;
                        else
                            $features .= $feature . ",";
                    }
                    
                }
                else{
                    $features = NULL;
                }
            
                if(!isset($message)){   //\"('{$features}')\"
                /*
                    $query = "INSERT INTO FILM (FILM_ID, TITLE, DESCRIPTION, RELEASE_YEAR, LANGUAGE_ID, ORIGINAL_LANGUAGE_ID, RENTAL_DURATION, RENTAL_RATE, LENGTH, REPLACEMENT_COST, RATING, SPECIAL_FEATURES, LAST_UPDATE)
                                VALUES('0', '{$title}', '{$desc}', '{$year}', '{$lang}', '$orig', '{$duration}', '{$rate}', '{$length}', '{$replacement}', '{$rating}', '{$features}', '$update')";
                    $result=mysqli_query($dbc,$query);*/
                    // prepare and bind
                    try{
                        $dbc->autocommit(FALSE); // i.e., start transaction
                        $query="UPDATE FILM SET
                                TITLE = '{$title}',
                                DESCRIPTION = '{$desc}',
                                RELEASE_YEAR = '{$year}',
                                LANGUAGE_ID = '{$lang}',
                                ORIGINAL_LANGUAGE_ID = '{$orig}',
                                RENTAL_DURATION = '{$duration}',
                                RENTAL_RATE = '{$rate}',
                                LENGTH = '{$length}',
                                REPLACEMENT_COST = '{$replacement}',
                                RATING = '{$rating}',
                                SPECIAL_FEATURES = '{$features}',
                                LAST_UPDATE = '{$date}'
                                WHERE FILM_ID = '{$filmID}'";
                        
                        $result = $dbc->query($query);//$result=mysqli_query($dbc,$query);

                        if (!$result) {
                            echo mysqli_error($dbc);
                        }
                        else{
                            $message="<b><p>New movie {$title} {$rate} updated! </b>";
                                        //<p> Let's now go and add the categories and actors!";
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
                if (isset($message)){
                    $message .= "<form method=\"post\" action=\"edit-movie.php\">
                                            <input class=\"w3-button w3-teal w3-round\" type=\"submit\" name=\"ok\" value=\"Done\">
                                </form>";
                    echo '<div class="w3-grey w3-padding-16" style="margin: 20px 0 0 0; padding:20px; float:left; width:40%; border-radius: 10px;">';
                    echo '<p><b>'.$message. '</b></p>';
                    echo '</div>';
                }
            }
        ?>
	</div>
<?php endblock() ?>