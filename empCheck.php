<?php
  require 'con1.php';
  $sql = "SELECT * FROM res where room_id = '".$_POST['room_id']."'
  AND r_date = '".$_POST['r_date']."' AND r_time = '".$_POST['r_time']."'";
  $res = $con->query($sql);
  if (($rw = mysqli_num_rows($res))>0) {
    echo "ห้องไม่ว่างจ้า";
  }
?>
