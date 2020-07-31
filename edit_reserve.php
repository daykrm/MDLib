<?php
session_start();
if(!isset($_SESSION['username'])){
    header("location:login.php");
}
else{
    require 'con1.php';
    $sql ="SELECT room.room_id,room.room_name,user.firstname,user.lastname,department.name,user.tel,res.r_date
    ,res.number,res.r_time,purpose.p_name,res.note,ex_equ.ex_name,ex_equ.id,res_detail.amount
    FROM res_detail INNER JOIN res ON res_detail.res_id = res.res_id
    INNER JOIN ex_equ ON res_detail.equ_id = ex_equ.id
    INNER JOIN room ON res.room_id = room.room_id
    INNER JOIN user ON res.user_id = user.user_id
    INNER JOIN purpose ON res.p_id = purpose.id
    INNER JOIN department ON user.dep_id = department.id WHERE res_detail.res_id = '".$_REQUEST['res_id']."'";
    if($res = $con->query($sql)){
    while(($row = mysqli_fetch_array($res,MYSQLI_ASSOC))!=null){
        $firstname = $row['firstname'];
        $lastname = $row['lastname'];
        $user_name = $firstname." ".$lastname;
        $user_tel = $row['tel'];
        $user_department = $row['name'];
        $room_name = $row['room_name'];
        $res_date = $row['r_date'];
        $res_time = $row['r_time'];
        $res_num = $row['number'];
        $res_note = $row['note'];
        $res_subject = $row['p_name'];
        $equ_id[] = $row['ex_name'];
        $id[] = $row['id'];
        $room_id =$row['room_id'];
        $amount[] = $row['amount'];
    }
  }
    $room = array();
    $sql = "SELECT * FROM room";
    if ($res = $con->query($sql)) {
      while ($rw = $res->fetch_object()) {
        $room[] = array('id'=>$rw->room_id,'name'=>$rw->room_name);
      }
    }
    if (isset($_POST['id'])) {
      foreach ($id as $key) {
        $sql = "UPDATE ex_equ SET used = used-1 WHERE id ='".$key."'";
        $con->query($sql);
      }

      $email = "mladda@kku.ac.th"; // เมลล์ผู้รับ
      $name = "Ladda";
      //----------------send mail-------------------------//
        require_once('email/class.phpmailer.php');
        $mail = new PHPMailer();
        $mail->IsHTML(true);
        $mail->IsSMTP();
        $mail->SMTPAuth = true; // enable SMTP authentication
        $mail->SMTPSecure = "ssl"; // sets the prefix to the servier
        $mail->Host = "smtp.gmail.com"; // sets GMAIL as the SMTP server
        $mail->Port = 465; // set the SMTP port for the GMAIL server
        $mail->Username = "a.khangdongkheng@gmail.com"; // GMAIL username
        $mail->Password = "d0854004076"; // MAIL password
        $mail->From = "Onlinebooking@mdlib.com"; // "name@yourdomain.com"
        $mail->AddReplyTo = "mladda@kku.ac.th"; // Reply
        $mail->FromName = "Online Booking";  // set from Name
        $mail->Subject = "Cancled";
        $mail->Body = "

      <table width='100%' border='0' align='center' style='text-align:center; font-size:18px;'>
      <tr>
        <td style='color:#f00'>ยกเลิกการจอง</td>
      </tr>
        <tr>
          <td style='color:#000'>ชื่อผู้ใช้ : '".$_SESSION['name']."'</td>
        </tr>
        <tr>
          <td style='color:#000'>ห้อง : '".$room_name."'</td>
        </tr>
        <tr>
          <td style='color:#000'>วันที่ : '".$res_date."'</td>
        </tr>
        <tr>
          <td style='color:#000'>เวลา : '".$res_time."'</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
      ";
        $mail->AddAddress($email, $name); // to Address
        $mail->set('X-Priority', '1'); //Priority 1 = High, 3 = Normal, 5 = low
        $mail->Send();
        //----------------send mail-------------------------//
      $sql = "DELETE FROM res_detail WHERE res_id = '".$_POST['id']."'";
      if ($res = $con->query($sql)) {
        $sql = "DELETE FROM res WHERE res_id = '".$_POST['id']."'";
        if($res = $con->query($sql)){
          echo "<script>alert('ยกเลิกการจองสำเร็จ');</script>";
          header('Refresh:0.5; url = main2.php',true,303);
        }
      }
    }
}
?>
<html>
<head>
  <script language="javascript">
    function del(){
  if(confirm('ต้องการยกเลิกการจองใช่หรือไม่')){
     return true;
  }
  else {
    return false;
  }
    }
  </script>
        <meta charset="utf-8">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <title>ยกเลิกการจอง</title>
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
        <li class="nav-item active">
          <a class="nav-link" href="main2.php">ตารางการใช้ห้อง/จองห้อง</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="room.php?room_id=1">รายละเอียดห้อง</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="user_report.php">รายงาน</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="user_guide.php">คู่มือการใช้งาน</a>
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
          echo '<button type="button" class="btn btn-outline-success my-2 my-sm-0 dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >'.$_SESSION['name'].'</button>';
          echo '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
          echo '<a class="dropdown-item" href="profile.php">โปรไฟล์</a>';
          echo '<a class="dropdown-item" href="logout.php">ออกจากระบบ</a>';
          echo '</div>';
        }?>
      </div>
      </div>
      </nav>
    </div>
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
    <div style="margin-top:30px;">
      <h3 align = 'center'>ข้อมูลการจอง</h3>
    </div>
    <div style="margin-left:180px;margin-right:180px;" class="body">
    <div class="row" style="margin-top:30px;">
      <div class="col">
        <label>ชื่อ</label>
        <input type="text" class="form-control" value="<?=$firstname?>" disabled/>
      </div>
      <div class="col">
        <label>สกุล</label>
        <input type="text" class="form-control" value="<?=$lastname?>" disabled/>
      </div>
      <div class="col-6">
        <label>สังกัด/แผนก</label>
        <input type="text" class="form-control" value="<?=$user_department?>" disabled/>
      </div>
    </div>
    <div class="row" style="margin-top:30px;">
      <div class="col-6">
        <label>ห้อง</label>
        <input type="text" class="form-control" value="<?=$room_name?>" disabled/>
      </div>
      <div class="col">
        <label>วัน</label>
        <input type="text" class="form-control" value="<?=$res_date?>" disabled/>
      </div>
      <div class="col">
        <label>เวลา</label>
        <input type="text" class="form-control" value="<?=$res_time?>" disabled/>
      </div>
    </div>
    <div class="row" style="margin-top:30px;">
      <div class="col">
        <label>จำนวนผู้ประชุม</label>
        <input type="text" class="form-control" value="<?=$res_num?>" disabled/>
      </div>
      <div class="col">
        <label>วัตถุประสงค์การขอใช้</label>
        <input type="text" class="form-control" value="<?=$res_subject?>" disabled/>
      </div>
      <div class="col-6">
        <label>อุปกรณ์โสตเพิ่มเติม</label>
        <input type="text" class="form-control" value="<?php $i=0;
        foreach ($equ_id as $key) {
          echo "$key:$amount[$i]";
          echo " ";
          $i+=1;
        } ?>" disabled/>
      </div>
    </div>
    <div class="row" style="margin-top:30px;">
    <div class="col">
      <label>หมายเหตุ</label>
      <input type="text" class="form-control" value="<?=$res_note?>" disabled/>
    </div>
  </div>
    <form method="POST" style="margin:30px;">
        <center>
        <input type="hidden" name="id" value="<?=$_REQUEST['res_id']?>">
        <button class="btn btn-danger" type="submit" onclick="return del()"style="margin-right:30px;">ยกเลิกการจอง</button>
        <button class="btn btn-success" type="button" onclick="window.location ='main2.php'">กลับ</button>
      </center>
    </form>
  </div>
</body>
</html>
