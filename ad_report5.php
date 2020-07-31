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
$thai_day_arr=array("อาทิตย์","จันทร์","อังคาร","พุธ","พฤหัสบดี","ศุกร์","เสาร์");
$thai_month_arr=array(
    "0"=>"",
    "1"=>"มกราคม",
    "2"=>"กุมภาพันธ์",
    "3"=>"มีนาคม",
    "4"=>"เมษายน",
    "5"=>"พฤษภาคม",
    "6"=>"มิถุนายน",
    "7"=>"กรกฎาคม",
    "8"=>"สิงหาคม",
    "9"=>"กันยายน",
    "10"=>"ตุลาคม",
    "11"=>"พฤศจิกายน",
    "12"=>"ธันวาคม"
);
$thai_month_arr_short=array(
    "0"=>"",
    "1"=>"ม.ค.",
    "2"=>"ก.พ.",
    "3"=>"มี.ค.",
    "4"=>"เม.ย.",
    "5"=>"พ.ค.",
    "6"=>"มิ.ย.",
    "7"=>"ก.ค.",
    "8"=>"ส.ค.",
    "9"=>"ก.ย.",
    "10"=>"ต.ค.",
    "11"=>"พ.ย.",
    "12"=>"ธ.ค."
);
function thai_date_and_time($time){   // 19 ธันวาคม 2556 เวลา 10:10:43
    global $thai_day_arr,$thai_month_arr;
    $thai_date_return = date("j",strtotime($time));
    $thai_date_return.=" ".$thai_month_arr[date("n",strtotime($time))];
    $thai_date_return.= " ".(date("Y",strtotime($time))+543);
    $thai_date_return.= " เวลา ".date("H:i:s",strtotime($time));
    return $thai_date_return;
}
function thai_date_fullmonth($time){   // 19 ธันวาคม 2556
    global $thai_day_arr,$thai_month_arr;
    $thai_date_return = date("j",strtotime($time));
    $thai_date_return.=" ".$thai_month_arr[date("n",strtotime($time))];
    $thai_date_return.= " ".(date("Y",strtotime($time))+543);
    return $thai_date_return;
}
require 'con1.php';
$sql = "SELECT * FROM ex_equ WHERE NOT id='4' ORDER BY used DESC";
if ($res = $con->query($sql)) {
  while ($rw = $res->fetch_object()) {
    $in_equ[] = array('used'=>$rw->used,'name'=>$rw->ex_name,'id'=>$rw->id);
  }
}
$td = date("Y-m-d");
$mod_date = strtotime($td."+ 1 days");
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
  <li class="nav-item">
    <a class="nav-link" href="manage_room.php">จัดการห้อง</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="manage_user.php">จัดการผู้ใช้</a>
  </li>
  <li class="nav-item dropdown active">
      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        รายงาน
      </a>
      <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="ad_report.php?room_id=1">รายงานการจองห้องประชุม </a>
        <a class="dropdown-item" href="ad_report2.php">รายงานจำนวนการใช้งาน (จำแนกตามกลุ่มผู้ใช้)</a>
        <a class="dropdown-item" href="ad_report3.php">รายงานจำนวนการใช้งาน (จำแนกตามวัตถุประสงค์)</a>
        <a class="dropdown-item" href="ad_report4.php">รายงานจำนวนการใช้งาน (จำแนกตามช่วงเวลา)</a>
        <a class="dropdown-item" href="#">รายงานอุปกรณ์โสต</a>
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
	<title>รายงาน</title>
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
          <div class="container" style="margin-top:30px;display:none;" id="head">
            <div class="row" >
              <img src="pic/icon.png" width="100" height="100">
              <div class="col-6" style="margin-top:30px;">
                <h4>ห้องสมุดคณะแพทยศาสตร์</h4>
                <h5>มหาวิทยาลัยขอนแก่น</h5>
              </div>
            </div>
            <hr>
            <p style="margin-top:30px;">ประจำวันที่ <?php echo thai_date_fullmonth($td); ?></p>
          </div>
          <h3 align='center'style="margin-top:30px;">รายงานเกี่ยวกับอุปกรณ์โสต</h3>
          <center><div class="container" id="Mydiv" style="overflow-y:scroll;  height: 350; margin-top:30px;">
      <table class="table  table-bordered " id="report" style="margin-bottom:  50px;">
        <tr class="text-white bg-success">
          <th> ลำดับ </th>
          <th> ชื่ออุปกรณ์โสต </th>
          <th> จำนวนครั้ง </th>
          <th> จำนวน (ชิ้น) </th>
        </tr>
      </div></center>
      <?php
      $i = 1;
      foreach ($in_equ as $rw) {
        echo "<tr>";
        echo '<th>'.$i.'</th>';
        echo '<th>'.$rw['name'].'</th>';
        echo '<th>'.$rw['used'].'</th>';
        $sql = "SELECT SUM(amount) s FROM res_detail WHERE equ_id = '".$rw['id']."'";
        $res = $con->query($sql);
        $row = $res->fetch_assoc();
        if($row['s']!=NULL){
        echo '<th>'.$row['s'].'</th>';
        }
        else{
        echo '<th>0</th>';
        }
        echo "</tr>";
      $i+=1;
      }
      ?>
    </table></div></center>
    <center class="form-group">
      <div class="col-3">
        <input type="submit"  class="form-control text-white bg-success" value="Print" id="button" onclick="report()">
      </div>
    </center>
</body>
</html>
<script>
  function report() {
    document.getElementById('head').style.display = 'block';
    document.getElementById('button').style.display = 'none';
    var Mydiv = document.getElementById('Mydiv');
    Mydiv.style.overflowY = 'hidden';
    Mydiv.style.height = 'auto';
    window.print()
    document.getElementById('button').style.display = 'block';
    document.getElementById('head').style.display = 'none';
    Mydiv.style.overflowY = 'scroll';
    Mydiv.style.height = '350px';
  }
</script>
