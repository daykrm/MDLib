<?PHP
require 'con1.php';
  if (isset($_POST['send'])) {
    $room_id = $_REQUEST['room_id'];
    $sql = "UPDATE room SET room_name = '".$_POST['room_name']."',location = '".$_POST['location']."'
    ,capacity = '".$_POST['capacity']."',note = '".$_POST['note']."',care_id = '".$_POST['room_care']."'";
      if($_FILES['filename']['size'] == 0)
      {
        $sql.="WHERE room_id = '".$_REQUEST['room_id']."'";
        if ($res = $con->query($sql)) {
          header("location:edit_room.php?room_id=$room_id");
        }
        else {
          echo "Error ไม่สามารถแก้ไขข้อมูลห้องได้";
        }
      }
      else {
        $sql.=",img = '".$_FILES['filename']['name']."' WHERE room_id = '".$_REQUEST['room_id']."'";
        $path = "pic/";
        $path = $path . basename( $_FILES['filename']['name']);
        move_uploaded_file($_FILES['filename']['tmp_name'], $path);
        if ($res = $con->query($sql)) {
          echo "<script>alert('แก้ไขข้อมูลห้องสำเร็จ')</script>";
          header("location:edit_room.php?room_id=$room_id");
        }
        else {
          echo "Error ไม่สามารถแก้ไขข้อมูลห้องได้";
        }
      }
  }
?>
