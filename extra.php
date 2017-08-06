<?php
            if(isset($_POST['submit'])){    // when the user clicks submit button
                echo "<script type=\"text/javascript\">
                        document.getElementById('custSelect').style.display='none';
                        document.getElementById('return').style.display='block';
                        </script>";
                echo "after display block";
            }
        ?>


        <script type="text/javascript">
            document.getElementById('id02').style.display='block';
        </script>


onclick=\"document.getElementById('id02').style.display='block'\" 


    <?php
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

    ?>
    <?php
        // WITH PREPARED STATEMENTS
        try 
        {
            $cnx = new PDO ($dsn,$dbuser,$dbpass);   
            $cnx->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $cnx->beginTransaction ();

            $stmt = $cnx->prepare ("SELECT * FROM users WHERE username=?");
            $stmt->execute(array($username));

            $cnx->commit();

            while ($row = $stmt->fetch (PDO::FETCH_OBJ)){
                echo $row->userid;
            }
        }

        catch (Exception $e) { 
            if (isset ($cnx)) 
                $cnx->rollback ();
            echo "Error:  " . $e; 
            }
        }
    ?>
