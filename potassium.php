
<?php
session_start();

include('mainnav.php');
 ?>

<link rel="stylesheet" href="assets/processing/foundation.min.css">
<link rel="stylesheet" href="assets/processing/img.min.css">
<link rel="stylesheet" href="assets/css/form-basic.css">

<script type="text/javascript" src="script/jquery-1.8.2.min.js"></script>

<div class="container">
        <div class="media-container-row">
            <div class="title col-12 col-md-8">
                <h2 class="align-center pb-3 mbr-fonts-style display-2"><br><br>Potassium
                </h2>
                <h3 class="mbr-section-subtitle align-center mbr-light mbr-fonts-style display-5"></h3>

            </div>
        </div>
    </div>

   <div class="main-content">

<!-- PHOTO -->



<center>
<!-- CONTAINER FOR PHOTO -->
<table>
  <tr>
    <td colspan="3">
      <canvas id="imageCanvas" width="300" height="300"></canvas>
    </td>
  </tr>
  <tr>
    <td>
      <canvas id="pixCanvas" width="100" height="100" style="background-color: #ddd;"></canvas>
    </td>
    <td>
      <canvas id="barvaCanvas" width="100" height="100" style="background-color: #ddd;" hidden></canvas>
    </td>
  </tr>
</table>

  <div class="main-content">

<form class="form-basic" method="post" action="#" enctype="multipart/form-data">

  <table align="center">
    <tr>
      <td>
        <input type="file" name="photo" id="imageLoader" />
        <a href="https://html-color-codes.info/colors-from-image/#" id="webbtn" ></a>
      </td>
    </tr>

    <tr>
      <td><input type="text" name="pixcolor" id="pixcolor" style="height:40px;" placeholder="Color Hex Value" readonly/></td>
    </tr>

    <tr>
      <td colspan="2"><input name="Test" type="Submit"  id="test" value="Test"/></td>
    </tr>
  </table>
</form>


<?php
$level = "";
$pixcolor = "";

if(isset($_POST["Test"]))
{
  include_once 'server.php';

  if(isset($_POST["pixcolor"])){
    $pixcolor =substr($_POST["pixcolor"],1);
  } else{
    $pixcolor = "";
  }

  mysql_select_db("db_ccasd", $con);
  $result = mysql_query("SELECT * FROM tblpotassium ");

  if(($rowval = mysql_fetch_array($result))> 0) {
    $pixcolor= $rowval["hex_val"];
    $level= "Sufficient" ;

  //  $level= $rowval["level"]; WHERE hex_val='".$pixcolor."'
  } else{
    echo ("<SCRIPT LANGUAGE='JavaScript'>
       window.alert('Please Try Again')
       window.location.href='imageprocess.php';
       </SCRIPT>");
  }
//upload photo to database
    $name = $_FILES['photo']['name'];
    $target_dir = "upload/";
    $target_file = $target_dir . basename($_FILES["photo"]["name"]);
    // Select file type
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Valid file extensions
    $extensions_arr = array("jpg","jpeg","png","gif");

    // Check extension
    if( in_array($imageFileType,$extensions_arr) ){
      // Convert to base64
      $image_base64 = base64_encode(file_get_contents($_FILES['photo']['tmp_name']) );
      $image = 'data:image/'.$imageFileType.';base64,'.$image_base64;

      // Insert record
      mysql_query("insert into images values(null,'".$name."','".$image."')");

      // Upload file
      move_uploaded_file($_FILES['photo']['tmp_name'],'upload/'.$name);


    }



//  $sampno = $_POST['sampno'];

    mysql_close($con);

}

?>

    <form class="form-basic"  method="post" enctype="multipart/form-data">
      <table align="center">
        <tr>
        <td><input type="text" id="level"  name="k" placeholder="Level" readonly value='<?php echo  $level; ?>'/></td>
        </tr>
        <tr>
          <td><input type="text" name="pixcolor" style="height:40px;" placeholder="Color Hex Value" value='<?php echo  $pixcolor; ?>' readonly/></td>
        </tr>
        <tr>
          <td colspan="2"><input name="Next" type="Submit" value="Next" /></td>
        </table>
    </form>

    <?php

    if(isset($_POST["Next"])){

      include_once 'msqli.php';

$k = $_POST['k'];
$pixcolor = $_POST['pixcolor'];


    $sql = "UPDATE temp SET res_k='$k',res_k_val='$pixcolor' WHERE 1";

    if ($conn->query($sql) === TRUE) {
   echo "<script type= 'text/javascript'>alert('New record created successfully'); window.location.href='ph.php';
  </script>";


    } else {
    //echo "<script type= 'text/javascript'>alert('Error: " . $sql . "<br>" . $conn->error."');</script>";
    }

    $conn->close();
    }
    ?>


  </div>

</div>

<script src="assets/processing/jquery.min.js.download"></script>
<script src="assets/processing/img.min.js.download"></script>
<script src="assets/processing/foundation.min.js.download"></script>
<script>
  $(document).foundation();
</script>

<?php
include('footer.php');
?>