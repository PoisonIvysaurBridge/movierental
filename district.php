<?php
require_once ('mysql_connect.php');
$districtRs = $dbc->query("SELECT DISTRICT FROM ADDRESS WHERE CITY_ID = ".$_GET['id']." GROUP BY DISTRICT;");
$postalRS = $dbc->query("SELECT POSTAL_CODE FROM ADDRESS WHERE CITY_ID = ".$_GET['id']." GROUP BY POSTAL_CODE;");
?>
Home District: <!--<input type = 'text' name = 'district' required> <br>-->
<select name="district" class="form-control" required>
  <?php while($row = mysqli_fetch_array($districtRs, MYSQLI_ASSOC)){ ?>
  <option value="<?php echo $row['DISTRICT']; ?>"><?php echo $row['DISTRICT']; ?></option>
  <?php } ?>
</select><br>  

Postal Code: <!--<input type = 'number' name = 'postal' min="1"> <br>-->
<select name="postal" class="form-control" required>
  <?php while($row = mysqli_fetch_array($postalRS, MYSQLI_ASSOC)){ ?>
  <option value="<?php echo $row['POSTAL_CODE']; ?>"><?php echo $row['POSTAL_CODE']; ?></option>
  <?php } ?>
</select><br>