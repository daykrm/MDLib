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
$sql = "SELECT * FROM room";
if ($res = $con->query($sql)) {
  while ($rw = $res->fetch_object()) {
    $room[] = array('id'=>$rw->room_id,'name'=>$rw->room_name);
  }
}
$sql = "SELECT * FROM purpose";
if ($res = $con->query($sql)) {
  while ($rw = $res->fetch_object()) {
    $purpose[] = array('id'=>$rw->id,'name'=>$rw->p_name);
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
        <a class="dropdown-item" href="#">รายงานจำนวนการใช้งาน (จำแนกตามวัตถุประสงค์)</a>
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
                <script>
                function chkdate(){
                  var date1 = document.getElementById('date1');
                  var date2 = document.getElementById('date2');
                  if (date1.value > date2.value) {
                    alert('กรุณาตรวจสอบวันที่ให้ถูกต้อง')
                    date1.focus();
                    return false;
                  }
                  else {
                    return true;
                  }
                }
                  function myFunction() {
                    // Declare variables
                    var input, filter, table, tr, td, i, txtValue;
                    input = document.getElementById("myInput");
                    filter = input.value.toUpperCase();
                    table = document.getElementById("report");
                    tr = table.getElementsByTagName("tr");
                    // Loop through all table rows, and hide those who don't match the search query
                    for (i = 0; i < tr.length; i++) {
                      td = tr[i].getElementsByTagName("td")[1];
                      if (td) {
                        txtValue = td.textContent || td.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                          tr[i].style.display = "";
                        } else {
                          tr[i].style.display = "none";
                        }
                      }
                    }
                  }
                </script>
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
          <h3 align='center'style="margin-top:30px;">รายงานจำนวนการใช้งาน (จำแนกตามวัตถุประสงค์)</h3>
          <div id ="body" class="body">
            <form method="post" name="selected" action="ad_report3.php">
          <div class="row" style="margin:12px;">
            <div class="col"></div>
            <label class="col-form-label">วันที่</label>
            <div class="col-2">
              <input type="date" name="date"  id="date1" <?php if(isset($_REQUEST["date"])) {echo "value='".$_REQUEST['date']."'";} ?> class="form-control">
            </div>
            <label class="col-form-label">ถึง</label>
            <div class="col-2">
              <input type="date" name="date2" id="date2" <?php if(isset($_REQUEST["date2"])){echo "value='".$_REQUEST['date2']."'";} ?> class="form-control">
            </div>
            <div class="col-1">
              <input type="submit" name="submit" class="form-control text-white bg-success" onclick="return chkdate();" value="ค้นหา">
            </div>
            </form>
            <label class="col-form-label">กรอง</label>
            <div class="col-2">
              <input type="text" id="myInput" oninput="myFunction()" class="form-control" placeholder="กรอกชื่อห้อง" autocomplete="off">
            </div>
            <div class="col"></div>
          </div>
        </div>
          <center><div class="container" id="Mydiv" style="overflow-y:scroll; height: 350px;margin-top:30px;">
      <table class="table  table-bordered " id="report" style="margin-bottom:  50px;">
        <tr class="text-white bg-success">
          <th> ลำดับ </th>
          <th> ห้อง </th>
          <?php
            foreach ($purpose as $key) {
              echo "<th>".$key['name']."</th>";
            }
          ?>
        </tr>
      </div></center>
      <?php
      $i = 1;
      foreach ($room as $rw) {
        echo "<tr>";
        echo '<td>'.$i.'</td>';
        echo '<td>'.$rw['name'].'</td>';
        foreach ($purpose as $key) {
          if (isset($_REQUEST['date'])) {
            if ($_REQUEST['date']=='' || $_REQUEST['date2']=='') {
              $sql = "SELECT COUNT(p_id) c FROM res WHERE p_id = '".$key['id']."' AND room_id ='".$rw['id']."'";
            }
            else {
                $sql = "SELECT COUNT(p_id) c FROM res WHERE p_id = '".$key['id']."' AND room_id ='".$rw['id']."' AND r_date BETWEEN '".$_REQUEST['date']."'AND '".$_REQUEST['date2']."'";
            }
          }
          else {
              $sql = "SELECT COUNT(p_id) c FROM res WHERE p_id = '".$key['id']."' AND room_id ='".$rw['id']."'";
          }
          $res = $con->query($sql);
          $row = $res->fetch_assoc();
          echo '<td>'.$row['c'].'</td>';
        }
        echo "</tr>";
      $i+=1;
      }
      ?>
      <tr>
        <th colspan="2">รวม</th>
        <?php
        foreach ($purpose as $key) {
        if (isset($_REQUEST['date'])) {
          if ($_REQUEST['date']=='' || $_REQUEST['date2']=='') {
            $sql = "SELECT COUNT(p_id) c FROM res WHERE p_id = '".$key['id']."'";
          }
          else {
              $sql = "SELECT COUNT(p_id) c FROM res WHERE p_id = '".$key['id']."'  AND r_date BETWEEN '".$_REQUEST['date']."'AND '".$_REQUEST['date2']."'";
          }
        }
        else {
            $sql = "SELECT COUNT(p_id) c FROM res WHERE p_id = '".$key['id']."'";
        }
        $res = $con->query($sql);
        $row = $res->fetch_assoc();
        echo '<td>'.$row['c'].'</td>';
      }
        ?>
      </tr>
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
    document.getElementById('body').style.display = 'none';
    var Mydiv = document.getElementById('Mydiv');
    Mydiv.style.overflowY = 'hidden';
    Mydiv.style.height = 'auto';
    window.print()
    document.getElementById('button').style.display = 'block';
    document.getElementById('head').style.display = 'none';
    document.getElementById('body').style.display = 'block';
    Mydiv.style.overflowY = 'scroll';
    Mydiv.style.height = '350px';
  }
</script>
