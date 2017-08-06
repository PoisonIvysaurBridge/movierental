<?php include 'base.php' ?>

<?php startblock('content') ?>
    <?php
        require_once('mysql_connect.php');
        $query="SELECT LANGUAGE_ID, NAME FROM LANGUAGE";
        $languages=mysqli_query($dbc,$query);
        $orig=mysqli_query($dbc,$query);
        //session_start();
        // if everything went well, then the session is restarted for a new adding of recipe
        if (isset($_POST['ok'])){
            unset($_SESSION['category']);
            unset($_SESSION['actor']);
            unset($_SESSION['categoryID']);
            unset($_SESSION['actorID']);
        }
    ?>
    
    <div class="w3-container"  style="margin: 0 30px;" >
        <h1 style="text-align: center;">Add New Movie</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="w3-half">
        
            Film Title: <input type = 'text' name = 'title' required> <br>
            Film Description: <br> <textarea rows='5' cols='40' name ='desc'> </textarea> <br>
            Release Year: <input type = 'number' name = 'releaseyear' min="1990"> <br>
            Current Language: <select name = 'lang'> 
                                    <?php
                                        if (!$languages) {
                                            echo mysqli_error($dbc);
                                        }
                                        else {
                                            while($row=mysqli_fetch_array($languages,MYSQLI_ASSOC)){
                                                echo "
                                                    <option value=\"{$row['LANGUAGE_ID']}\">{$row['NAME']}</option>
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
                                            while($row=mysqli_fetch_array($orig,MYSQLI_ASSOC)){
                                                echo "
                                                    <option value=\"{$row['LANGUAGE_ID']}\">{$row['NAME']}</option>
                                                ";
                                            }
                                        }
                                    ?>
                               </select> <br>
            Rental Duration (in days): <input type = 'number' name = 'duration' min="3" value="3" required> <br>
            Rental Rates (in USD): <input type='number' step='any' name = 'rate' min="4.99" value="4.99" required><br>
            Movie Length (in minutes): <input type = 'number' name = 'length' min="1"> <br>
        </div>    <!-- FIRST HALF -->
        
        <div class="w3-half">
            Replacement Cost (if lost or damaged): <input type = 'number' step = 'any' name = 'replace' min="19.99" value="19.99" required> <br>
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
            
            <input type = 'submit' class="w3-button w3-teal w3-round" name='submit' style="margin: 50px 0 0 0" value = 'Add Movie'><br>
        </div><!-- SECOND HALF -->
        </form>
        
        <?php
            if (isset($_POST['submit'])){      
                $message=NULL;
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

                if (!empty($_POST['releaseyear'])){ // release year
                    $year = $_POST['releaseyear'];
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


                        // WRITE QUERIES HERE


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
                    
                    $filmID = 0;
                    $stmt = $dbc->prepare("INSERT INTO FILM (FILM_ID, TITLE, DESCRIPTION, RELEASE_YEAR, LANGUAGE_ID, ORIGINAL_LANGUAGE_ID, RENTAL_DURATION, RENTAL_RATE, LENGTH, REPLACEMENT_COST, RATING, SPECIAL_FEATURES, LAST_UPDATE)
                                                VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                                            ");
                                            
                    if(!$stmt->bind_param("issiiiididsss", $filmID, $title, $desc, $year, $lang, $orig, $duration, $rate, $length, $replacement, $rating, $features, $update)){
                        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
                    }

                    if (!$stmt->execute()) {
                        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                    }
                    else{
                        $message="<b><p>New movie {$title} {$rate} added! </b> 
                                    <p> Let's now go and add the categories and actors!";
                    }
                }
                if (isset($message)){
                    $message .= "<form method=\"post\" action=\"add-movie-details.php\">
                                            <input class=\"w3-button w3-teal w3-round\" type=\"submit\" name=\"ok\" value=\"OK\">
                                </form>";
                    echo '<div class="w3-grey w3-padding-16" style="margin: 20px 0 0 0; padding:20px; float:left; width:40%; border-radius: 10px;">';
                    echo '<p><b>'.$message. '</b></p>';
                    echo '</div>';
                }
            }
        ?>
    </div>
<?php endblock() ?>