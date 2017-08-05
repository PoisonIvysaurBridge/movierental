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
    document.getElementById('return').style.display='none';
</script>