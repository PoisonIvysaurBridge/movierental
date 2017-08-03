<?php include 'base.php' ?>

<?php startblock('content') ?>
    <div class="w3-container"  style="margin: 0 30px;" >
    <form action = "" method = "post">
  <p>Customer ID: &nbsp &nbsp <input list="custid" name="custid">
  			<datalist id="custid">
  				<option value="1">
  				<option value="2">
  				<option value="3">
  				<option value="4">
  				<option value="5">
  			</datalist>

  			<p>Movie ID: &nbsp &nbsp <input list="movid" name="movid">
  			<datalist id="movid">
  				<option value="1">
  				<option value="2">
  				<option value="3">
  				<option value="4">
  				<option value="5">
  				<option value="6">
  				<option value="7">
  				<option value="8">
  				<option value="9">
  				<option value="10">
  			</datalist>

        <p> <label for='formMovies[]'>Select movies to be returned:</label><br>
        <select multiple="multiple" name="formMovies[]">
          <option value="1"> Movie 1 </option>
          <option value="2"> Movie 2 </option>
          <option value="3"> Movie 3 </option>
          <option value="4"> Movie 4 </option>
          <option value="5"> Movie 5 </option>
          <option value="6"> Movie 6 </option>
        </select><br>
        <input type="submit" name="formSubmit" value="Submit" >
      </form>
      </div>
<?php endblock() ?>