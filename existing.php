<?php
    require_once ('mysql_connect.php');
    $addressDetails = $dbc->query("SELECT ADDRESS_ID, ADDRESS, ADDRESS2, DISTRICT, POSTAL_CODE, PHONE FROM ADDRESS WHERE ADDRESS_ID=".$_POST['id'].";");
    $row = $addressDetails->fetch_assoc();
    
    $addressID = NULL;
    $address1 = NULL;
    $address2 = NULL;
    $district = NULL;
    $postal = NULL;

    $phone = $row['PHONE'];
    if($row != NULL){
        $addressID = $row['ADDRESS_ID']; 
        $address1 = $row['ADDRESS'];
        $address2 = $row['ADDRESS2'];
        $district = $row['DISTRICT'];
        $postal = $row['POSTAL_CODE'];
        $phone = $row['PHONE'];
    }
    
?>

Primary Home Address: 
<input type="text" name="address1" required value="<?php echo $address1 ?>"><br>

Secondary Home Address (leave blank if none): 
<input type="text" name="address2" value="<?php echo $address2 ?>"><br>

Home District: <input type = 'text' name = 'district' required value="<?php echo $district ?>"> <br>

Postal Code: <input type = 'number' name = 'postal' min="1" value="<?php echo $postal ?>"> <br>

Phone Number: <input type = 'number' name ='phone' min="1" required value="<?php echo $phone ?>"> <br>

<input type="hidden" name="addressID" value="<?php echo $addressID ?>">