<?php
require 'con1.php';
  if (isset($_POST['save_care'])) {
    $sql = "INSERT INTO room_care value('','".$_POST['care_name']."','".$_POST['care_tel']."')";
    $res = $con->query($sql);
    if ($res) {
      $last_id = $con->insert_id;
      $sql = "SELECT * FROM room_care WHERE care_id='".$last_id."'";
      $r = $con->query($sql);
      while ($rw = $r->fetch_assoc()) {
        $care_name = $rw['care_name'];
      }
    }
    if (isset($_REQUEST['new'])) {
        header("location:new_room.php?care_name=$care_name");
    }
    else {
      $room_id = $_REQUEST['room_id'];
        header("location:edit_room.php?room_id=$room_id");
    }
  }
?>
