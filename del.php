<?php
require 'con1.php';
if(isset($_REQUEST['room_id'])){
  $sql = "SELECT * FROM res_detail
  INNER JOIN res ON res_detail.res_id = res.res_id
  WHERE res.room_id ='".$_REQUEST['room_id']."'";
  if ($res = $con->query($sql)) {
    while ($rw = $res->fetch_assoc()) {
      $id[] = $rw['id'];
    }
  }
  foreach ($id as $key) {
    $sql = "DELETE FROM res_detail WHERE id ='".$key."'";
    $con->query($sql);
  }
    $sql = "DELETE FROM res WHERE room_id ='".$_REQUEST['room_id']."'";
    $con->query($sql);
    $sql = "DELETE FROM room WHERE room_id ='".$_REQUEST['room_id']."'";
    $res = $con->query($sql);
    if ($res) {
      echo "<script>alert('ลบข้อมูลห้องสำเร็จ')</script>";
      header('Refresh:0; url=manage_room.php',true,303);
    }
}
else{
  $sql = "SELECT * FROM res_detail
  INNER JOIN res ON res_detail.res_id = res.res_id
  WHERE res.user_id ='".$_REQUEST['user_id']."'";
  if ($res = $con->query($sql)) {
    while ($rw = $res->fetch_assoc()) {
      $id[] = $rw['id'];
    }
  }
  foreach ($id as $key) {
    $sql = "DELETE FROM res_detail WHERE id ='".$key."'";
    $con->query($sql);
  }
  $sql = "DELETE FROM res WHERE user_id ='".$_REQUEST['user_id']."'";
  $con->query($sql);
  $sql = "DELETE FROM user WHERE user_id ='".$_REQUEST['user_id']."'";
  $res = $con->query($sql);
  if ($res) {
    echo "<script>alert('ลบข้อมูลผู้ใช้สำเร็จ')</script>";
    header('Refresh:0; url=manage_user.php',true,303);
  }
}
?>
