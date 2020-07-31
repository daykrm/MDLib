<?php
  require 'con1.php';
  if (isset($_REQUEST['room_name'])) {
    $sql = "SELECT * FROM room WHERE room_name = '".$_POST['room_name']."'";
    $res = $con->query($sql);
    if (($rw = mysqli_num_rows($res))>0) {
      echo "ชื่อห้องซ้ำจ้า!!";
    }
  }
  else if (isset($_POST['room_id'])) {
    $sql ="SELECT * FROM room WHERE room_id='".$_POST['room_id']."'";
    $res = $con->query($sql);
    if ($rw = $res->fetch_assoc()) {
      if ($rw['status']=='0') {
        $sql ="UPDATE room SET status ='1' WHERE room_id='".$_POST['room_id']."'";
        $res = $con->query($sql);
        echo "เปิด";
      }
      else {
        $sql ="UPDATE room SET status ='0' WHERE room_id='".$_POST['room_id']."'";
        $res = $con->query($sql);
        echo "ปิด";
      }
    }
  }
  elseif (isset($_POST['newText'])) {
    if ($_POST['text_id']=='1') {
      $sql = "UPDATE mytext SET div1 ='".$_POST['newText']."' WHERE id ='1'";
      $res = $con->query($sql);
      echo "Updated";
    }
    else {
      $sql = "UPDATE mytext SET div2 ='".$_POST['newText']."' WHERE id ='1'";
      $res = $con->query($sql);
      echo "Updated";
    }
  }
  else {
    $sql = "SELECT * FROM user where username = '".$_POST['username']."'";
    $res = $con->query($sql);
    if (($rw = mysqli_num_rows($res))>0) {
      echo "Sorry, That username is taken!!";
    }
  }
?>
