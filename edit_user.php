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
        $sql = "SELECT * FROM user INNER JOIN department ON user.dep_id = department.id WHERE user_id = '".$_GET['user_id']."'";
        if ($res = $con->query($sql)) {
          while($row = $res->fetch_assoc()){
              $username = $row['username'];
              $password = $row['password'];
              $firstname = $row['firstname'];
              $lastname = $row['lastname'];
              $status = $row['status'];
              $user_dep = $row['name'];
              $email = $row['email'];
              $tel = $row['tel'];
              $user_id = $row['user_id'];
              $auth = $row['auth'];
          }
        }
    if(isset($_POST['send'])){
        $sql2 = "UPDATE user SET username ='".$_POST['username']."',password ='".$_POST['password']."'
        ,firstname ='".$_POST['firstname']."',lastname ='".$_POST['lastname']."',status ='".$_POST['user_position']."'
        ,dep_id ='".$_POST['user_department']."',tel ='".$_POST['user_tel']."',email ='".$_POST['user_email']."',auth ='".$_POST['auth']."' where user_id ='".$_GET['user_id']."'";
        $res2 = mysqli_query($con,$sql2);
        if($res2){
            echo "<script>alert('แก้ไขข้อมูลสำเร็จ')</script>";
            header('Refresh:0; url = manage_user.php',true,303);
        }
    }
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
  <li class="nav-item active">
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
    echo '<button type="button" class="btn btn-outline-success my-2 my-sm-0 dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >'.$_SESSION['name'].'</button>';
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
                <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
                <script>
                    function telChk() {
                        x = document.getElementById('tel');
                        if(x.length<=10){
                            document.getElementById('tel').style.display = 'block';
                        }
                    }
                </script>
        <h4 align = 'center' style="margin:12px;">แก้ไขข้อมูลสมาชิก</h4>
        <form method="POST">
    <div class="container" style="margin-top:50px;">
    <div class="form-row">
      <div class="form-group col-md-6">
        <label>Username</label>
        <input type="username" name='username' class="form-control" placeholder="Username" value="<?=$username?>">
      </div>
      <div class="form-group col-md-6">
        <label>Password</label>
        <input type="password" name='password' class="form-control"  placeholder="Password" value="<?=$password?>">
      </div>
    </div>
    <div class="form-row">
      <div  class="form-group col-md-6">
        <label>ชื่อ</label>
        <input type="text" name='firstname' class="form-control" placeholder="Firstname" value="<?=$firstname?>">
      </div>
      <div class="form-group col-md-6">
        <label>สกุล</label>
        <input type="text" name='lastname' class="form-control" placeholder="Lastname" value="<?=$lastname?>">
      </div>
    </div>
    <div class="form-row">
      <div  class="form-group col-md-6">
        <label>ตำแหน่ง</label>
        <select name='user_position'class="form-control">
             <?php
             $user_status = array("นักศึกษา","อาจารย์","เจ้าหน้าที่");
             foreach ($user_status as $key) {
               if ($key == $status) {
                 echo '<option value='.$key.' selected>'.$key.'</option>';
               }
               else {
                 echo '<option value='.$key.'>'.$key.'</option>';
               }
             }
             ?>
        </select>
      </div>
      <div  class="form-group col-md-6">
        <label>สังกัด</label>
        <select name='user_department' class="form-control">
            <?php
            $sql = "SELECT * FROM department";
            if ($res = $con->query($sql)) {
              while ($rw = $res->fetch_object()) {
                $dep[]=array('id'=>$rw->id,'name'=>$rw->name);
              }
            }
            foreach ($dep as $key) {
              if ($user_dep==$key['name']) {
                echo '<option value='.$key['id'].' selected>'.$key['name'].'</option>';
              }
              else {
                  echo '<option value='.$key['id'].'>'.$key['name'].'</option>';
              }
            }
             ?>
        </select>
      </div>
    </div>
    <div class="form-row">
      <div  class="form-group col-md-5">
        <label>E-mail</label>
        <input  type="email" name='user_email'class="form-control" placeholder="Example@kkumail.com" value="<?=$email?>">
      </div>
      <div  class="form-group col-md-5">
        <label>เบอร์โทรศัพท์</label>
        <input type="text"name='user_tel' minlength="9" maxlength="10" id="tel" class="form-control" placeholder="0899999999" value="<?=$tel?>">
      </div>
      <div  class="form-group col-md-2">
        <label>สิทธิ์</label>
        <select name="auth" class="form-control">
          <option value="ADMIN" <?php if($auth=='ADMIN'){echo "selected";} ?>>ADMIN</option>
          <option value="USER" <?php if($auth=='USER'){echo "selected";} ?>>USER</option>
        </select>
      </div>
    </div>
            <center style="margin-top:30px;">
            <button type="submit" name ="send" class="btn btn-success" style = "margin-right:30px;">บันทึกข้อมูล</button>
            <button type="button" class="btn btn-primary" onclick="window.location='manage_user.php'">กลับ</button>
            </center>
    </div>
</form>
</body>
</html>
