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
foreach ($room as $key) {
  $array[] = $key['id'];
}
$in = implode(',',$array);
$sql = "SELECT * FROM room INNER JOIN room_care ON room.care_id = room_care.care_id WHERE room_id = '".$_REQUEST['room_id']."'";
if ($res = $con->query($sql)) {
  if ($rw = $res->fetch_object()) {
    $room_name = $rw->room_name;
    $care_name = $rw->care_name;
    $care_tel = $rw->care_tel;
  }
}
$td = date("Y-m-d");
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
        <a class="dropdown-item" href="ad_report.php?room_id=1&date=<?=$td?>&date2=<?=date("Y-m-d",$mod_date)?>">รายงานการจองห้องประชุม </a>
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
            <p style="margin-top:30px;">ห้องประชุม : <?php if(isset($_REQUEST['room_id'])){if($_REQUEST['room_id']!=$in){echo $room_name;}else{echo "ทั้งหมด";}}?> </p>
            <p style="margin-top:30px;">ผู้ดูแลห้องประชุม : <?=$care_name?> ติดต่อ <?=$care_tel?></p>
          </div>
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
          </script>
          <h3 align='center'style="margin-top:30px;">รายงานการจองห้องประชุม (รายวัน)</h3>
          <div id ="body" class="body">
          <div class="row" style="margin:30px;">
            <label class="col-form-label">ห้อง : </label>
            <div class="col-sm-2">
              <form method="post" name="selected" action="ad_report.php"><select name ="room_id"  class="form-control">
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
                <option value="<?=$in?>" <?php if (isset($_REQUEST['room_id'])&&$_REQUEST['room_id']==$in){echo "selected";} ?>>ALL</option>
                    </select>
            </div>
            <label class="col-form-label">วันที่</label>
            <div class="col-2">
              <input type="date" name="date"  id="date1" <?php if(isset($_REQUEST["date"])) {echo "value='".$_REQUEST['date']."'";} ?> class="form-control">
            </div>
            <label class="col-form-label">ถึง</label>
            <div class="col-2">
              <input type="date" name="date2" id="date2" <?php if(isset($_REQUEST["date2"])){echo "value='".$_REQUEST['date2']."'";} ?> class="form-control">
            </div>
            <div class="col-1">
              <input type="submit" class="form-control text-white bg-success" onclick="return chkdate();" value="ค้นหา">
            </div>
            </form>
            <label class="col-form-label">ค้นหาจาก :</label>
            <div class="col-1">
            <select class="form-control" name="myselect" id="myselect" onchange="myselect()">
              <option value="3">เวลาจอง</option>
              <option value="4">ชื่อผู้จอง</option>
              <option value="5">สถานะ</option>
              <option value="6">หน่วยงาน</option>
            </select>
          </div>
            <div class="col-2">
              <input type="text" id="myInput" oninput="myFunction()" class="form-control" placeholder="ค้นหา" autocomplete="off">
            </div>
          </div>
        </div>
          <center><div class="container" id="Mydiv" style="overflow-y:scroll; height: 350; margin-top:30px;">
      <table class="table  table-bordered " id="report" style="margin-bottom:30px;">
        <tr class="text-white bg-success">
          <th> ลำดับ </th>
          <th> วันที่ </th>
          <th> ห้อง </th>
          <th> เวลาจอง </th>
          <th>ผู้จอง</th>
          <th>สถานะ</th>
          <th>หน่วยงาน</th>
          <th>เบอร์ติดต่อ</th>
          <th>อุปกรณ์โสตเพิ่มเติม</th>
        </tr>
      </div></center>
      <?php
      if (isset($_REQUEST['date'])) {
        if ($_REQUEST['date']=='' or $_REQUEST['date2']=='') {
          $sql = "SELECT * FROM res
          INNER JOIN room ON res.room_id = room.room_id
          INNER JOIN user ON res.user_id = user.user_id
          INNER JOIN department ON user.dep_id = department.id
          WHERE res.room_id IN "."(".$_REQUEST['room_id'].")";
        }
        else {
          $sql = "SELECT * FROM res
          INNER JOIN room ON res.room_id = room.room_id
          INNER JOIN user ON res.user_id = user.user_id
          INNER JOIN department ON user.dep_id = department.id
          WHERE res.room_id IN "."(".$_REQUEST['room_id'].")"."AND res.r_date BETWEEN '".$_REQUEST['date']."'AND '".$_REQUEST['date2']."'";
        }
      }
      else {
        $sql = "SELECT * FROM res
        INNER JOIN room ON res.room_id = room.room_id
        INNER JOIN user ON res.user_id = user.user_id
        INNER JOIN department ON user.dep_id = department.id
        WHERE res.room_id IN "."(".$_REQUEST['room_id'].")";
      }
      if ($res = $con->query($sql)) {
        $n=1;
        while ($row = $res->fetch_assoc()) {
          $sql = "SELECT * FROM res_detail
          INNER JOIN ex_equ ON res_detail.equ_id = ex_equ.id
          INNER JOIN res ON res_detail.res_id = res.res_id
          WHERE res.res_id ='".$row['res_id']."'";
          $r = $con->query($sql);
          while ($rw = $r->fetch_assoc()) {
            $equ[] = $rw['ex_name'];
            $amount[] = $rw['amount'];
          }
          $arr = array();
          for ($i=0; $i < count($amount); $i++) {
            $arr[$i] = $equ[$i].":".$amount[$i];
          }
          $equ2 = implode(',',$arr);
          echo "<tr>";
            echo "<td>".$n."</td>";
            echo "<td>".$row['r_date']."</td>";
            echo "<td>".$row['room_name']."</td>";
            echo "<td>".$row['r_time']."</td>";
            echo "<td>".$row['firstname']." ".$row['lastname']."</td>";
            echo "<td>".$row['status']."</td>";
            echo "<td>".$row['name']."</td>";
            echo "<td>".$row['tel']."</td>";
            echo "<td>".$equ2."</td>";
          echo "</tr>";
          $n+=1;
          $equ2 = "";
          $equ = array();
          $amount = array();
        }
      }
      ?>
    </table></div></center>
    <center class="form-group">
      <div class="col-3">
        <input type="submit"  class="form-control text-white bg-success" value="Print" id="button" onclick="report()">
      </div>
    </center>
    <script>
    function report(){
      var head = document.getElementById('head');
      var body = document.getElementById('body');
      var but = document.getElementById('button');
      var Mydiv = document.getElementById('Mydiv');
      Mydiv.style.overflowY = 'hidden';
      Mydiv.style.height = 'auto';
      head.style.display = 'block';
      body.style.display = 'none';
      but.style.display = 'none';
      window.print();
      head.style.display = 'none';
      body.style.display = 'block';
      but.style.display = 'block';
      Mydiv.style.overflowY = 'scroll';
      Mydiv.style.height = '350px';
    }
    function myselect(){
      select = document.getElementById('myselect').value;
      return select;
    }
function myFunction() {
  // Declare variables
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("report");
  tr = table.getElementsByTagName("tr");
  select = myselect();
  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[select];
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
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">รายละเอียดการจอง</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
<div class="modal-body">
  <div class="row">
    <label class="col-form-label">ผู้จอง</label>
    <input type="text" class="form-control" value="wowowowowo">
    <label class="col-form-label">ห้องประชุม</label>
    <input type="text" class="form-control" value="wowowowowo">
    <label class="col-form-label">จำนวนผู้ประชุม</label>
    <input type="text" class="form-control" value="wowowowowo">
    <label class="col-form-label">วัตถุประสงค์การขอใช้</label>
    <input type="text" class="form-control" value="wowowowowo">
    <label class="col-form-label">อุปกรณ์โสต</label>
    <input type="text" class="form-control" value="wowowowowo">
  </div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>
</body>
</html>
