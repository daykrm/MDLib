<?php
session_start();
if(!isset($_SESSION['username'])){
    header("location:login.php");
}
if (isset($_SESSION['auth'])) {
  if($_SESSION['auth'] != "ADMIN")
{
  echo "<script>alert('ไม่สามารถเข้าถึงหน้านี้ได้');</script>";
  header('Refresh:0; url = main2.php',true,303);
}
}
require 'con1.php';
$sql = "SELECT * FROM room";
if ($res = $con->query($sql)) {
  while ($rw = $res->fetch_object()) {
    $room[] = array('id'=>$rw->room_id,'name'=>$rw->room_name);
  }
}
$sql = "SELECT * FROM room_care";
if ($res = $con->query($sql)) {
  while ($rw = $res->fetch_object()) {
    $room_care[] = array('id'=>$rw->care_id,'name'=>$rw->care_name);
  }
}
$sql = "SELECT * FROM room
INNER JOIN room_care ON room.care_id = room_care.care_id
WHERE room_id = '".$_REQUEST['room_id']."'";
$res = $con->query($sql);
if ($rw = $res->fetch_assoc()) {
  $room_name = $rw['room_name'];
  $location = $rw['location'];
  $capacity = $rw['capacity'];
  $note = $rw['note'];
  $care_name = $rw['care_name'];
  $img = $rw['img'];
}
$td = date('Y-m-d');$mod_date = strtotime($td."+ 1 days");
?>
<html>
<head>
  <link rel="stylesheet" href="styles.css">
  <div class="header">
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#" style="margin-left:10px;">
      <div class="row">
        <img src="pic/icon.png" width="60" height="60" class="d-inline-block align-top" alt="">
        <div style="margin-left:12px;text-align:center;">ระบบจองห้องประชุม<br>ห้องสมุดคณะแพทยศาสตร์ มหาวิทยาลัยขอนแก่น</div>
      </div>
      </a>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
<span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navbarSupportedContent">
<ul class="navbar-nav mr-auto">
  <li class="nav-item">
    <a class="nav-link" href="main2.php">ตารางการใช้ห้อง/จองห้อง</a>
  </li>
  <li class="nav-item active">
    <a class="nav-link" href="manage_room.php">จัดการห้อง</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="manage_user.php">จัดการผู้ใช้</a>
  </li>
  <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        รายงาน
      </a>
      <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="ad_report.php?room_id=1">รายงานการจองห้องประชุม </a>
        <a class="dropdown-item" href="ad_report2.php">รายงานจำนวนการใช้งาน (จำแนกตามกลุ่มผู้ใช้)</a>
        <a class="dropdown-item" href="ad_report3.php">รายงานจำนวนการใช้งาน (จำแนกตามวัตถุประสงค์)</a>
        <a class="dropdown-item" href="ad_report4.php">รายงานจำนวนการใช้งาน (จำแนกตามช่วงเวลา)</a>
        <a class="dropdown-item" href="ad_report5.php">รายงานอุปกรณ์โสต</a>
<a class="dropdown-item" href="ad_report6.php">รายงานจำนวนการใช้งาน (จำแนกตามภาควิชา)</a>
      </div>
    </li>
  <li class="nav-item">
    <a class="nav-link" href="admin_guide.php">คู่มือการใช้งาน</a>
  </li>
</ul>
<div class="dropdown">
  <?php
  if(!isset($_SESSION['username'])||$_SESSION['username']==""){
    echo "<span>";
    echo "You're not logged in.";
    echo '<button type="button" class="btn btn-outline-success my-2 my-sm-0" onclick="window.location ='."'login.php'".'">'.'Login'.'</button>';
    echo "</span>";
  }
  else {
    echo "<div class='btn-group dropleft'>";
    echo '<button type="button" class="btn btn-outline-success my-2 my-sm-0 dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >'.$_SESSION['username'].'</button>';
    echo '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
    echo '<a class="dropdown-item" href="profile.php">โปรไฟล์</a>';
    echo '<a class="dropdown-item" href="logout.php">ออกจากระบบ</a>';
    echo '</div>';
    echo '</div>';
  }?>
</div>
</div>
</nav>
</div>
        <meta charset="utf-8">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body class="bg">
         <!-- Optional JavaScript -->
         <!-- jQuery first, then Popper.js, then Bootstrap JS -->
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <h4 align = 'center' style="margin:12px;">แก้ไขข้อมูลห้องประชุม</h4>
        <center><form name="DD" id="DD" action="edit_room.php" method="post">
          <div class="form-group">
            <div class="col-4">
              <select name ="room_id" class="form-control" onChange="this.form.submit()">
              <?php
                foreach ($room as $key) {
                  if (isset($_REQUEST['room_id'])&&$_REQUEST['room_id']==$key['id']) {
                      echo '<option value='.$key['id'].' selected>'.$key['name'].'</option>';
                  }
                  else{
                    echo '<option value='.$key['id'].'>'.$key['name'].'</option>';
                  }
                }
              ?>
                  </select>
            </div>
          </div>
              <img src="pic/<?=$img?>" style="width : 550px; height:300px; margin-top:10px;" >
    </form></center>
        <form method="POST" action="edit_room_met.php" name="room" enctype="multipart/form-data">
    <div class="container">
    <div class="form-group row">
                <label class="col-form-label">ชื่อห้อง </label>
                <div class="col-3">
                  <input type="text" name='room_name' class="form-control" placeholder="ชื่อห้อง" value="<?=$room_name?>">
                  <input type="hidden" name="room_id" value="<?=$_REQUEST['room_id']?>">
                </div>
                <label class="col-form-label">ที่ตั้ง</label>
                <div class="col">
                  <input type="text" name='location' class="form-control"  placeholder="ตำแหน่งที่ตั้งของห้อง" value="<?=$location?>">
                </div>
                <label class="col-form-label">ความจุ</label>
                <div class="col-1">
                  <input type="text" name='capacity' class="form-control" placeholder="ความจำห้อง" value="<?=$capacity?>">
                </div>
    </div>
    <div class="form-group row">
      <label class="col-form-label">ผู้ดูแล</label>
      <div class="col-3">
        <select name ="room_care" class="form-control">
          <?php
            foreach ($room_care as $key) {
              if ($care_name==$key['name']) {
                echo '<option value='.$key['id'].' selected>'.$key['name'].'</option>';
              }
              else {
                echo '<option value='.$key['id'].'>'.$key['name'].'</option>';
              }
            }
          ?>
        </select>
      </div>
      <div class="col-2">
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal">เพิ่มผู้ดูแล</button>
      </div>
      <label class="col-form-label">หมายเหตุ</label>
      <div class="col">
        <input type="text" name='note' class="form-control" placeholder="หมายเหตุ" value="<?=$note?>">
      </div>
      </div>
      <div class="form-group row">
        <div class="col">
          <input type="file" class="custom-file-input" id="customFile" name="filename">
          <label class="custom-file-label" for="customFile">เลือกรูปภาพ</label>
        </div>
      </div>
              <script>
// Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>
            <center>
            <button type="submit" name ="send" class="btn btn-success" style = "margin-right:30px;">บันทึกข้อมูล</button>
            <button type="button" class="btn btn-primary" onclick="window.location='manage_room.php'">กลับ</button>
            </center>
        </div>
</form>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">เพิ่มผู้ดูแล</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
<div class="modal-body">
  <form method="post" action="add_roomcare.php">
    <label>ชื่อ</label>
    <input type="text" name="care_name" placeholder="ชื่อผู้ดูแล" class="form-control" required>
    <label>เบอร์ติดต่อ</label>
    <input type="text" name="care_tel" required placeholder="เบอร์ติดต่อผู้ดูแล" class="form-control">
    <input type="hidden" name="room_id" value="<?=$_REQUEST['room_id']?>">
</div>
<div class="modal-footer">
<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
<button type="submit" name="save_care" class="btn btn-success">Save</button>
</form>
</div>
</div>
</div>
</div>
</div>
</body>
</html>
