<?php require_once('../../auth.php'); ?>

<html>
<head>
<title>Welcome to Uttar Pradesh Prakash Bhawan</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../febe/style.css" type="text/css" media="screen" charset="utf-8">
<script src="../argiepolicarpio.js" type="text/javascript" charset="utf-8"></script>
<script src="../js/application.js" type="text/javascript" charset="utf-8"></script>	
<!--sa poip up-->
<link href="../src/facebox.css" media="screen" rel="stylesheet" type="text/css" />
<script src="../lib/jquery.js" type="text/javascript"></script>
<script src="../src/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
  $('a[rel*=facebox]').facebox({
  loadingImage : 'src/loading.gif',
  closeImage   : 'src/closelabel.png'
  })
})
</script>
<script type="text/javascript">
function PrintDiv() 
{
   var html="<html>";
   html+=  document.getElementById('divToPrint').innerHTML;
   html+="</html>";
   var printWin = window.open('','','left=0,top=0,width=1024,height=980,toolbar=0,scrollbars=no,status=0');
   printWin.document.write(html);
   printWin.document.close();
   printWin.focus();
   printWin.print();
   printWin.close();
}
</script>
</head>
<body>
  <div id="container" style="width:95%">
    <div id="adminbar-outer" class="radius-bottom">
      <div id="adminbar" class="radius-bottom">
        <a id="logo" href="dashboard.php"></a>
        <div id="details">
          <a class="avatar" href="javascript: void(0)">
          <img width="36" height="36" alt="avatar" src="../img/avatar.jpg">
          </a>
          <div class="tcenter">
          Hi
          <strong><?php echo $_SESSION['SESS_MEMBER_USERNAME']; ?></strong>
          !
          <br>
          <a class="alight" href="javascript: void(0)">Visit website</a>
          |
          <a class="alightred" href="../../logout.php">Logout</a>
          </div>
        </div>
      </div>
    </div>
    <div id="panel-outer" class="radius" style="opacity: 1;margin-top:-15px;">
      <div id="panel" class="radius">
        <ul class="radius-top clearfix" id="main-menu">
          <li>
            <a href="../dashboard.php">
              <img alt="Dashboard" src="../img/m-dashboard.png">
              <span>Dashboard</span>
            </a>
          </li>
          <li>
            <a href="../../reservation/demo1.php">
              <img alt="Dashboard" src="../img/m-dashboard.png">
              <span>Room Booking</span>
            </a>
          </li>
          <li>
            <a href="../user.php">
              <img alt="Users" src="../img/m-users.png">
              <span>Users</span>
              <span class="submenu-arrow"></span>
            </a>
          </li>
          
          <li>
            <a href="../aboutus.php">
              <img alt="Articles" src="../img/m-articles.png">
              <span>About Us</span>
              <span class="submenu-arrow"></span>
            </a>
          </li>
          <li>
            <a href="../message.php">
              <img alt="Newsletter" src="../img/m-newsletter.png">
              <span>Comments</span>
            </a>
          </li>
          <li>
            <a href="../rooms.php">
              <img alt="Statistics" src="../img/m-statistics.png">
              <span>Rooms</span>
            </a>
          </li>
                     <li>
            <a href="../addservices.php">
              <img alt="Statistics" src="../img/m-statistics.png">
              <span>Add Services</span>
            </a>
          </li>
          <li>
            <a href="../roominventory.php">
              <img alt="Custom" src="../img/m-custom.png">
              <span>Room Inventory</span>
            </a>
          </li>
                    <li>
            <a class="active" href="../billing/billing.php">
              <img alt="Custom" src="../img/m-custom.png">
              <span>Room Billing</span>
            </a>
          </li>
          <li>
            <a href="../stock/stock.php">
              <img alt="Custom" src="../img/m-custom.png">
              <span>Stock Inventory</span>
            </a>
          </li>
                      <li>
            <a  href="../appliances/stock.php">
              <img alt="Custom" src="../img/m-custom.png">
              <span>Stock Appliances</span>
            </a>
          </li>
          <div class="clearfix"></div>
        </ul>
                   
        <div id="divToPrint" style="margin-top:-30px;">                
                  <div id="content" class="clearfix"  >
          <!--<label for="filter">Filter</label> <input type="text" name="filter" value="" id="filter" />-->
          <table cellpadding="0" cellspacing="0" width="100%" style="font-size:14px;">
            
            <?php

            include('../connect.php');

            if(isset($_GET['con'])  and isset($_GET['redirectedfrombilling1']) and $_GET['redirectedfrombilling1']=='YES')
            {
              $c_n=$_GET['con'];
              $sql_bill=mysql_query("SELECT *
              FROM billining where conf_nof='$c_n'");
              $_POST=mysql_fetch_array($sql_bill);
              $_POST['mod'] = $_POST['p_type'];$_POST['cheque_date'] = $_POST['ch_date'];
              $_POST['cheque_no'] = $_POST['ch_no'];
              $_REQUEST['conf_nof'] = $_POST['conf_nof'];
              $_REQUEST['submit'] = true;
              $sql_rss = mysql_query("select*from reservation where confirmation='$c_n'");
              $row_rss = mysql_fetch_array($sql_rss);
              $billnoFromGet = $row_rss['billno'];
              


            }
            
            $record = "";
            global $val;

            $sgstPercent = 6;
            $cgstPercent = 6;
            $igstPercent = 12;
            $overalltax = 12;
            $sep = "";
            $to = "";
            $vn = 0;
            $t = 0;
            $vn2 = 0;
            $vn3 = 0;
            $Prop = 1;
            $Prop1 = 1;
            $Prop2 = 1;
            $rmSer = "";
            $html = array();
            if (isset($_POST['conf_nof'])) {
              $sql = "select count(*) AS c from reservation where confirmation='" . $_REQUEST['conf_nof'] . "'";
              $data = mysql_query($sql);
              if ($row = mysql_fetch_assoc($data)) {
                $record = $row['c'];
              }
              if ((int) $record > 0) {
                $sql1 = "select * from reservation where confirmation='" . $_REQUEST['conf_nof'] . "'";
                $data1 = mysql_query($sql1);
                if ($row1 = @mysql_fetch_array($data1, MYSQL_ASSOC)) {
                  $row1['reservation_id'];
                  $val = getRoomRecord($row1['confirmation']);
                  $rmSer = getRoomServiceRecord($row1['confirmation']);
                  $html = $row1;

                }
              } else {
                $record = 'Record not found !';
              }


              function getRoom_actualpricevvvvv($rmno, $con, $c)
              {
                echo $c;
                echo "<br/>";
                $val = 0;
                $sql = "select * from rooinventory where confirmation='$con'  and room=" . $rmno;
                $data1 = mysql_query($sql);
                while ($row1 = mysql_fetch_assoc($data1)) {
                  $arrival = $row1['arrival'];
                  $departure = $row1['departure'];
                  echo "a" . strtotime($arrival);
                  echo "<br/>";
                  echo "n" . strtotime($c);
                  echo "<br/>";
                  if (strtotime($arrival) <= strtotime($c) && strtotime($departure) >= strtotime($c)) {
                    $val += $row1['price'];
                  }
                }
                //  $id=  $row1['id'];
                //   mysql_query("update rooinventory set status_new='1' where id='$id' ");
                return $val;
              }
              function getRoom_actualprice($fe)
              {
                $fe;
                return $fe;


              }
              function getRoom_actualprice1($rmno, $con)
              {

                $val = 0;
                $sql = "select * from rooinventory where confirmation='$con' and room=" . $rmno;
                $data1 = mysql_query($sql);
                if ($row1 = mysql_fetch_assoc($data1)) {
                  $val += $row1['price'];
                }
                return $val;
              }
            }
            $rowSpan = 6;


            $divide = 'NO';
            if ($html['state'] == 'Jharkhand') {
              $rowSpan = 7;
              $divide = 'YES';
            }
            function getRoomRecord($conf_nof)
            {
              $querystring = "select * from rooinventory where confirmation='" . $conf_nof . "' order by room";
              $result = mysql_query($querystring);
              $resultarr = array();
              $i = 0;
              while ($resar = @mysql_fetch_array($result, MYSQL_ASSOC)) {
                foreach ($resar as $key => $val) {
                  $resultarr[$i][$key] = $val;
                }
                $i++;
              }
              return $resultarr;
            }
            function getMentinanceCharge($arr, $rmNo, $conf, $srName)
            {
              $test = 0;
              $querystring = "select * from room_services where rms_conform_no='" . $conf . "' and createdon='" . strftime("%Y-%m-%d", strtotime("$arr -1 day")) . "' and rms_services='" . $srName . "' order by rms_price";
              $result = mysql_query($querystring);
              while ($resar = @mysql_fetch_array($result)) {
                $test += $resar['rms_price'];
              }
              if ($test > 0)
                echo number_format($test, 2);
              else
                echo '&nbsp;';
              return number_format($test, 2);
            }

            function getRoomServiceRecord($conf)
            {
              $querystring = "select distinct(rms_services),rms_price,rms_no from room_services where rms_conform_no='" . $conf . "'";
              $result = mysql_query($querystring);
              $resServarr = array();
              $i = 0;
              while ($resar = @mysql_fetch_array($result, MYSQL_ASSOC)) {
                foreach ($resar as $key => $val) {
                  $resServarr[$i][$key] = $val;
                }
                $i++;
              }
              return $resServarr;
            }


            function getdiffrence($ariv, $disp)
            {
              $rav = '';
              $ts1 = strtotime($ariv);
              //$ts2 = strtotime($val[$p]['depar']); 
              $ts2 = strtotime($disp);
              $seconds_diff = $ts2 - $ts1;
              $Fildif = ($seconds_diff / (60 * 60 * 24)) % 365;
              $h = ($seconds_diff / (60 * 60)) % 24;

              $count = 1;
              if ($Fildif > 0) {
                for ($i = 0; $i < $Fildif; $i++) {
                  $rav .= strftime("%Y-%m-%d", strtotime("$ariv +" . $count . " day"));
                  $count++;
                }
              }
              echo $rav;
            }
            //echo "<pre/>";print_r($val );
            ?>
                           <tr>
                            <td colspan="3" align="center"><h2>SRI SAMMAD SHIKHARJI VIKAS SAMITI (REGD.)</h2>
              <h3 style="margin-top:-10px">UTTAR PRADESH PRAKASH BHAWAN</h3></td>
                            <tr>
                               <td width="15%"><img src="Logo UPPB (Left Side).jpg" width="100" height="60"></td>
                               <td width="70%" >
                               <div align="center" style="margin-top:-20px">                              
<strong>(ISO 9001:2015)</strong><br>
Kund- Kund Marg Madhuban, Shikharji, Giridih (Jharkhand)<br>
Tel- 9939800471/8789752150 // www.shikharjiup.org <br> Email- info@shikharjiup.org<br>
                               </div>
                               </td>
                               <td width="15%"><img src="AQC IAS Logo ISO Logo 2018.jpg" width="100" height="60" title="Logo ISO"></td>
                               
                            </tr>
                <tr>
                    <td colspan="3">&nbsp;</td>
                </tr>
                <tr>
                           <td colspan="3">&nbsp;</td>
                           </tr>
        
          </table>
                   <?php if (isset($_REQUEST['submit'])): ?>
                                 <?php

                                 $ones = array(
                                   "",
                                   " One",
                                   " Two",
                                   " Three",
                                   " Four",
                                   " Five",
                                   " Six",
                                   " Seven",
                                   " Eight",
                                   " Nine",
                                   " Ten",
                                   " Eleven",
                                   " Twelve",
                                   " Thirteen",
                                   " Fourteen",
                                   " Fifteen",
                                   " Sixteen",
                                   " Seventeen",
                                   " Eighteen",
                                   " Nineteen"
                                 );

                                 $tens = array(
                                   "",
                                   "",
                                   " Twenty",
                                   " Thirty",
                                   " Forty",
                                   " Fifty",
                                   " Sixty",
                                   " Seventy",
                                   " Eighty",
                                   " Ninety"
                                 );

                                 $triplets = array(
                                   "",
                                   " Thousand",
                                   " Million",
                                   " Billion",
                                   " Trillion",
                                   " Quadrillion",
                                   " Quintillion",
                                   " Sextillion",
                                   " Septillion",
                                   " Octillion",
                                   " Nonillion"
                                 );

                                 // recursive fn, converts three digits per pass
                                 function convertTri($num, $tri)
                                 {
                                   global $ones, $tens, $triplets;

                                   // chunk the number, ...rxyy
                                   $r = (int) ($num / 1000);
                                   $x = ($num / 100) % 10;
                                   $y = $num % 100;

                                   // init the output string
                                   $str = "";

                                   // do hundreds
                                   if ($x > 0)
                                     $str = $ones[$x] . " Hundred";

                                   // do ones and tens
                                   if ($y < 20)
                                     $str .= $ones[$y];
                                   else
                                     $str .= $tens[(int) ($y / 10)] . $ones[$y % 10];

                                   // add triplet modifier only if there
                                   // is some output to be modified...
                                   if ($str != "")
                                     $str .= $triplets[$tri];

                                   // continue recursing?
                                   if ($r > 0)
                                     return convertTri($r, $tri + 1) . $str;
                                   else
                                     return $str;
                                 }

                                 // returns the number as an anglicized string
                                 function convertNum($num)
                                 {
                                   $num = (int) $num; // make sure it's an integer
                               
                                   if ($num < 0)
                                     return "Refund " . convertTri(-$num, 0);

                                   if ($num == 0)
                                     return "zero";

                                   return convertTri($num, 0);
                                 }

                                 // Returns an integer in -10^9 .. 10^9
// with log distribution
                                 function makeLogRand()
                                 {
                                   $sign = mt_rand(0, 1) * 2 - 1;
                                   $val = randThousand() * 1000000
                                     + randThousand() * 1000
                                     + randThousand();
                                   $scale = mt_rand(-9, 0);

                                   return $sign * (int) ($val * pow(10.0, $scale));
                                 }
                                 if ($_SESSION['dbname'] == "geniohotel_2") {
									
                                   $bil = mysql_query("select max(billno) As billno from reservation where billno < '1500' ");
                                   while ($row_bil = mysql_fetch_array($bil)) {
                                     $bil_no = $row_bil['billno'];
                                   }
                                   if ($bil_no == 170) {
                                     $bil_no = 224;
                                   }
                                   $next_bilno = sprintf("%03d", $bil_no + 1);
                                   if ($next_bilno == "528") {
                                     $bil = mysql_query("select max(billno) As billno from reservation  ");
                                     while ($row_bil = mysql_fetch_array($bil)) {
                                       $bil_no = $row_bil['billno'];
                                     }
                                     $next_bilno = sprintf("%03d", $bil_no + 1);
                                   }

                                 } else {
                                   $bil = mysql_query("select max(billno) As billno from reservation  ");
                                   while ($row_bil = mysql_fetch_array($bil)) {
                                     $bil_no = $row_bil['billno'];
                                   }
                                   $next_bilno = sprintf("%03d", $bil_no + 1);

                                 }

                                 $date1 = $_POST['arrival'];
                                 $date2 = $_POST['depar'];
                                 $con_fig = $_POST['conf_nof'];
                                 if(!isset($_GET['con'])){
                                  $qw = mysql_query("INSERT INTO billining(bill_id, conf_nof, arrival, depar, add_amount, p_type, bank_name, ch_date, ch_no, comment) VALUES  ('','$con_fig','$date1','$date2','" . $_POST['add_amount'] . "','" . $_POST['mod'] . "','" . $_POST['bank_name'] . "','" . $_POST['cheque_date'] . "','" . $_POST['cheque_no'] . "','" . $_POST['comment'] . "')");

                                 }
                                 $sql_room = mysql_query("select*from rooinventory where confirmation='$con_fig'");
                                 while ($row_room = mysql_fetch_array($sql_room)) {
                                   $room_id = $row_room['id'];
                                   if(!isset($_GET['con'])){
                                   mysql_query("UPDATE rooinventory SET status='HouseKeeping' WHERE id=$room_id");
                                   }
                                 }
                                 $sql_r = mysql_query("select*from reservation where confirmation='$con_fig'");
                                 $row_r = mysql_fetch_array($sql_r);
                                 $room_id1 = $row_r['reservation_id'];
                                 if(!isset($_GET['con'])){
                                 mysql_query("UPDATE reservation SET status='HouseKeeping',tax_percent=12, billno='$next_bilno' WHERE reservation_id=$room_id1");
                                 }

                                 ?>
                            
                                 <table  width="100%"   style=" margin-bottom:20px">
                                 <tr>
                                 <td align="center" width="30%" > <b><u> GSTIN- 20AAFTS4024F1Z0</u></b> </td>
                                 <td align="center"  width="40%" >  </td>
                                 <td align="right" > <b><u> PAN- AAFTS4024F</u></b> </td>
                                 </tr>
                                 </table>
                                 <table  width="20%" align="center" style="margin-top:-20px;margin-bottom:20px">
                                 <tr>
                                 <td align="center" ><u><b>TAX INVOICE</b></u> </td>
                                 </tr>
                                 </table>
                                  <table cellpadding="0" cellspacing="0" border="1" style="width:100%;font-size:13px;" align="center">
                                      <tr>
                                          <td width="97%">
                                            <table cellpadding="0" cellspacing="0" border="1" style="width:100%;font-size:12px;" align="center">
                                                  <tr>
                                                      <td style="width:100px;"><strong>Customer ID:</strong></td>
                                                      <td><?php echo $html['reservation_id']; ?></td>
                                                      <td style="width:100px;"><strong>Invoice No: </strong></td>
                                                      <td style="width:30%;"><?php 
                                                      
                                                      
                                                      if(isset($_GET['con'])){
                                                        
                                                      echo $billnoFromGet=sprintf("%03d", $billnoFromGet ); 
                                                      }else{

                                                        echo $next_bilno; 
                                                      }
                                                      ?></td>
                                                  </tr>
                                                   <tr>
                                                      <td style="width:100px;"><strong>Guest Name:</strong></td><td><?php echo $html['firstname'] . ' ' . $html['lastname']; ?></td>
                                                      <td style="width:100px;"><strong>No.&nbsp;Of&nbsp;Person:</strong></td>
                                                      <td>&nbsp;<?php echo $html['no_pertion']; ?> Member</td>
                                                  </tr>
                                                   <tr>
                                                      <td style="width:100px;"><strong>Company:</strong></td><td>&nbsp;<?php echo $html['company']; ?></td>
                                                      <td style="width:100px;"><strong>Arrival&nbsp;Date&nbsp;&&nbsp;Time:</strong></td>
                                                      <td>&nbsp;<?php echo date("d-m-Y h:i A", strtotime($_POST['arrival'])); ?></td>
                                                  </tr>
                                                   <tr>

                                                      <td style="width:100px;" rowspan="2"><strong>Address:</strong></td>
                                                      <td rowspan="2" valign="top">
                                                          <?php echo $html['address'] . '<br/>City: ' . $html['city'] . '<br/>State: ' . $html['state']; ?>
                                                      </td>
                                                      <td style="width:100px;"><strong>Dept&nbsp;Date&nbsp;&&nbsp;Time:</strong></td>
                                                      <td>&nbsp;<?php echo $_POST['depar']; ?></td>
                                                  </tr>
                                                   <tr>
                                                      <td style="width:100px;"><strong>Total&nbsp;Rooms:</strong></td>
                                                      <td>&nbsp;&nbsp;<?php echo count($val); ?></td>
                                                  </tr>
                                                   <tr>
                                                      <td style="width:100px;"><strong>Room Tariff:</strong></td>
                                                      <td colspan="3"><?php $sepa = 0;
                                                      for ($i = 0; $i < count($val); $i++) { ?>
                                                                            <?php $room = $val[$i]['qty'];
                                                                            $sql_room = "select * from rooinventory where qty='$room' and confirmation='" . $_REQUEST['conf_nof'] . "' ";
                                                                            $data1_room = mysql_query($sql_room);
                                                                            $row1_room = mysql_fetch_assoc($data1_room);

                                                                            $sep1 = $row1_room['price'];
                                                                            $sep2 = $sepa + $sep1;
                                                                            $sepa = $sep2;

                                                                            ?>
                                                          <?php }
                                                      echo $sepa; ?>
                                                
                                                         Rupes Only....</td>
                                                  </tr>
                                                   <tr>
                                                      <td style="width:100px;"><strong>Room No:</strong></td>
                                                      <td colspan="3">
                                        
                                                          <?php $sep = "";
                                                          for ($i = 0; $i < count($val); $i++) { ?>
                                                                            <?php echo $sep . $val[$i]['qty'];
                                                                            $sep = ','; ?>
                                                          <?php } ?>
                                                      </td>
                                                  </tr>
                                              </table>
                                          </td>
                                    </tr>
                                       <tr>
                                          <td>&nbsp;</td>
                                       </tr>                            
                                       <tr>
                                          <td height="220px"   valign="top">
                         
                          
                                      <table cellpadding="0" cellspacing="0" border="1" style="width:100%; font-size:12px;display:none" align="center" >
                             
                                              <tr>
                                                 <td align="center" ><strong>&nbsp;&nbsp;&nbsp;&nbsp;Date&nbsp;</strong></td> 
                                                 <td align="center" ><strong>Room Tariff</strong></td>
                                                 <?php $ser = mysql_query("SELECT * FROM `services`");
                                                 while ($ser_row = mysql_fetch_array($ser)) {
                                                   ?>
                                                                        <td align="center"><strong><?php echo $ser_row['ser_name']; ?></strong></td>
                                                      <?php } ?> 
                                                 <td align="center" ><strong>Sub Total Rs.</strong></td>                          
                                              </tr>
                                              <?php

                                              $today = $_POST['arrival'];
                                              $today = strftime("%Y-%m-%d", strtotime("$today +1 day"));
                                              $disp = $_POST['depar'];
                                              $rec = 1;
                                              $REcTest = 0;
                                              $resultInfo = 0;
                                              $mentiTotal = 0;
                                              $finalPrice = 0;
                                              $ts1 = strtotime($_POST['arrival']);
                                              $menti = 0;
                                              $resultInfoTot = 0;
                                              $ts2 = strtotime($_POST['depar']);
                                              $nextday = $today;

                                              $seconds_diff = $ts2 - $ts1;
                                              @$Fildif1 = ($seconds_diff / (60 * 60 * 24)) % 365;
                                              @$h1 = ($seconds_diff / (60 * 60)) % 24;
                                              if ($Fildif1 == 0) {
                                                @$Fildif = 1;
                                                @$h = 0;
                                              } else {
                                                @$Fildif = @$Fildif1;
                                                @$h = @$h1;
                                              }
                                              for ($r = 0; $r < $Fildif; $r++): ?> 
                                                              <?php for ($p = 0; $p < count($val); $p++): ?>
                                                                                <?php $dateNew = $val[$p]['arrival'];
                                                                                $c = strftime("%d-%m-%Y", strtotime("$nextday -1 day "));
                                                                                if (strtotime($dateNew) <= strtotime($today)) {
                                                                                  // $resultInfo += getRoom_actualprice($val[$p]['room'],$con_fig,$c);
                                                                                  // $resultInfoTot +=$resultInfo;
                                                                                }
                                                                                ?> 
                                                                  <?php endfor; ?>
                                                                    <tr valign="top">
                                 
                                    
                                       
                                        
                                                                        <td align="center" style="width:105px;"><?php echo $c; ?></td>
                                                                        <td align="center" ><?php







                                                                        $dsi = explode(' ', $disp);
                                                                        $dsi[0];


                                                                        $val = 0;
                                                                        $sql = "select * from rooinventory where confirmation='$con_fig'   ";
                                                                        $data1 = mysql_query($sql);


                                                                        $deu = 0;
                                                                        $d = 0;
                                                                        $id = 0;
                                                                        while ($row1 = mysql_fetch_assoc($data1)) {
                                                                          $arrivalggg = $row1['arrival'];
                                                                          $arrivalgggv = explode(' ', $arrivalggg);
                                                                          $arrivalgggv[0];

                                                                          if (strtotime($c) == strtotime($arrivalgggv[0])) {

                                                                            $d = $d + $row1['price'];

                                                                            $deu++;


                                                                          }
                                                                          $departureccc = $row1['departure'];
                                                                          if (strtotime($arrivalggg) <= strtotime($c) && strtotime($departureccc) >= strtotime($c)) {

                                                                            $val += $row1['price'];
                                                                          }
                                                                        }



                                                                        $d;
                                                                        "<br/>";
                                                                        if ($deu == 0) {
                                                                          $deu = 1;
                                                                        }
                                                                        $fe = $val + $d;
                                                                        $domain = strstr($fe, '.');
                                                                        if ($domain == "") {
                                                                          echo $fe . ".00";
                                                                        } else {
                                                                          echo $fe;
                                                                          //546532051  4861
                                                                        }
                                                                        $d = 0;
                                                                        $deu = 0;
                                                                        $resultInfo += getRoom_actualprice($fe);
                                                                        $resultInfoTot += $resultInfo;
                                                                        $payable = $resultInfo;

                                                                        ?> </td>
                                                                      <?php $ser = mysql_query("SELECT * FROM `services`");
                                                                      while ($ser_row = mysql_fetch_array($ser)) {
                                                                        ?>
                                                                                      <td align="center"><strong>
                                                                                        <?php $c_adt = strftime("%Y-%m-%d", strtotime("$nextday -1 day "));
                                                                                        $ser_id = $ser_row['ser_id'];
                                                                                        @$co_sum = "select sum(rms_price) from room_services where rms_conform_no='" . $_REQUEST['conf_nof'] . "' and createdon='$c_adt' and rms_serv_id=$ser_id";
                                                                                        @$sum_1 = mysql_query($co_sum);
                                                                                        while (@$row_sum = mysql_fetch_array($sum_1)) {
                                                                                          @$menti = $row_sum['sum(rms_price)'];
                                                                                        }
                                                                                        echo @$menti;
                                                                                        ?>
                                                                                      </strong></td>
                                                                    <?php } ?>
                                                
                                                
                                                                            <td align="center" style="width:150px;">&nbsp;<?php if ($resultInfo > 0): ?><?php
                                                                                 $c_adt = strftime("%Y-%m-%d", strtotime("$nextday -1 day "));
                                                                                 $ser_id = $ser_row['ser_id'];
                                                                                 @$co_sum = "select sum(rms_price) from room_services where rms_conform_no='" . $_REQUEST['conf_nof'] . "' and createdon='$c_adt'";
                                                                                 @$sum_1 = mysql_query($co_sum);
                                                                                 while (@$row_sum = mysql_fetch_array($sum_1)) {
                                                                                   @$menti1 = $row_sum['sum(rms_price)'];
                                                                                 }
                                                                                 $resultInfo;
                                                                                 echo number_format(($resultInfo + $menti1), 2);
                                                                                 $finalPrice += ($resultInfo + $menti1); ?><?php endif; ?></td>   
                                             
                                        
                                       
                                     
                                                                 <?php $nextday = strftime("%d-%m-%Y", strtotime("$today +" . ($rec) . " day"));
                                                                 $rec++;
                                                                 $resultInfo = 0;
                                                                 $menti = 0; ?>
                                                 <?php endfor; ?> 
                                              </tr>
                                              <?php if ($h > 0) { ?>
                                                            <tr>
                                                            <td align="center"><?php echo strftime("%d-%m-%Y", strtotime("$nextday -1 day ")); ?>
                                                            </td>
                                                            <td align="center" ><?php echo number_format(@$payable, 2); ?> </td>
                                
                                                                        <?php $ser = mysql_query("SELECT * FROM `services`");
                                                                        while ($ser_row = mysql_fetch_array($ser)) {
                                                                          ?>
                                                                                      <td align="center"><strong>
                                                                                        <?php $c_n;
                                                                                        $c_adt = strftime("%Y-%m-%d", strtotime("$nextday -1 day "));
                                                                                        $ser_id = $ser_row['ser_id'];
                                                                                        @$co_sum = "select sum(rms_price) from room_services where rms_conform_no='" . $_REQUEST['conf_nof'] . "' and createdon='$c_adt' and rms_serv_id=$ser_id";
                                                                                        @$sum_1 = mysql_query($co_sum);
                                                                                        while (@$row_sum = mysql_fetch_array($sum_1)) {
                                                                                          @$menti = $row_sum['sum(rms_price)'];
                                                                                        }
                                                                                        echo @$menti;
                                                                                        ?>
                                                                                      </strong></td>
                                                                    <?php } ?>
                                                
                                                             <td align="center" ><?php
                                                             $c_adt = strftime("%Y-%m-%d", strtotime("$nextday -1 day "));
                                                             $ser_id = $ser_row['ser_id'];
                                                             @$co_sum = "select sum(rms_price) from room_services where rms_conform_no='" . $_REQUEST['conf_nof'] . "' and createdon='$c_adt'";
                                                             @$sum_1 = mysql_query($co_sum);
                                                             while (@$row_sum = mysql_fetch_array($sum_1)) {
                                                               @$menti1 = $row_sum['sum(rms_price)'];
                                                             }
                                                             @$exter = @$payable + @$menti1;
                                                             echo number_format(@$payable + @$menti1, 2); ?> </td>
                                                            </tr> 
                                              <?php } else {
                                                $exter = 0;
                                              } ?> 
                         
                                            </table>  
                                            <table cellpadding="0" cellspacing="0" border="1" style="width:100%; font-size:12px;" align="center" >
                             
                             <tr>
                                <td align="center" ><strong>&nbsp;&nbsp;&nbsp;&nbsp;Date&nbsp;</strong></td> 
                                <td align="center" ><strong>Room Tariff	</strong></td>
                                <?php $ser = mysql_query("SELECT * FROM `services`");
                                while ($ser_row = mysql_fetch_array($ser)) {
                                  ?>
                                                       <td align="center"><strong>
                                 <?php

                                 $r = str_replace(' ', '&nbsp;', $ser_row['ser_name']);
                                 echo $r;
                                 ?></strong></td>
                                     <?php } ?> 
                                <td align="center" ><strong>Sub Total Rs.</strong></td>                          
                             </tr>
                             <?php

                               $today = $_POST['arrival'];
                               $today = strftime("%Y-%m-%d", strtotime("$today +1 day"));
                             $disp = $_POST['depar'];
                             $rec = 1;
                             $REcTest = 0;
                             $resultInfo = 0;
                             $mentiTotal = 0;
                             $finalPrice = 0;
                             $ts1 = strtotime($_POST['arrival']);
                             $menti = 0;
                             $resultInfoTot = 0;
                             $ts2 = strtotime($_POST['depar']);
                             $nextday = $today;

                             $seconds_diff = $ts2 - $ts1;
                             @$Fildif1 = ($seconds_diff / (60 * 60 * 24)) % 365;
                             @$h1 = ($seconds_diff / (60 * 60)) % 24;
                             if ($Fildif1 == 0) {
                               @$Fildif = 1;
                               @$h = 0;
                             } else {
                               @$Fildif = @$Fildif1;
                               @$h = @$h1;
                             }

                             $R_Maint_Rs = 0;
                             for ($r = 0; $r < $Fildif; $r++): ?> 
                                             <?php for ($p = 0; $p < count($val); $p++): ?>
                                                               <?php

                                                               $dateNew = $val[$p]['arrival'];
                                                               $c = strftime("%d-%m-%Y", strtotime("$nextday -1 day "));
                                                               if (strtotime($dateNew) <= strtotime($today)) {
                                                                 // $resultInfo += getRoom_actualprice($val[$p]['room'],$con_fig,$c);
                                                                 // $resultInfoTot +=$resultInfo;
                                                               }
                                                               ?> 
                                                 <?php endfor; ?>
                                                   <tr valign="top" style="display:none">
                
                   
                      
                       
                                                       <td  align="center" style="width:105px;"><?php echo
                                                       
                                                       
                                                       $c; 
                                                       
                                                       ?></td>
                                                       <td align="center" ><?php







                                                       $dsi = explode(' ', $disp);
                                                       $dsi[0];


                                                       $val = 0;
                                                       $sql = "select * from rooinventory where confirmation='$con_fig'   ";
                                                       $data1 = mysql_query($sql);


                                                       $deu = 0;
                                                       $d = 0;
                                                       $id = 0;
                                                       while ($row1 = mysql_fetch_assoc($data1)) {
                                                         $arrivalggg = $row1['arrival'];
                                                         $arrivalgggv = explode(' ', $arrivalggg);
                                                         $arrivalgggv[0];

                                                         if (strtotime($c) == strtotime($arrivalgggv[0])) {

                                                           $d = $d + $row1['price'];

                                                           $deu++;


                                                         }
                                                         $departureccc = $row1['departure'];
                                                         if (strtotime($arrivalggg) <= strtotime($c) && strtotime($departureccc) >= strtotime($c)) {

                                                           $val += $row1['price'];
                                                         }
                                                       }



                                                       $d;
                                                       "<br/>";
                                                       if ($deu == 0) {
                                                         $deu = 1;
                                                       }
                                                       $fe = $val + $d;
                                                       $domain = strstr($fe, '.');
                                                       if ($domain == "") {
                                                         echo $fe . ".00";
                                                       } else {
                                                         echo $fe;
                                                         //546532051  4861
                                                       }

                                                       $R_Maint_Rs = $R_Maint_Rs + $fe;
                                                       $d = 0;
                                                       $deu = 0;
                                                       $resultInfo += getRoom_actualprice($fe);
                                                       $resultInfoTot += $resultInfo;
                                                       $payable = $resultInfo;

                                                       ?> </td>
                                                     <?php $ser = mysql_query("SELECT * FROM `services`");
                                                     while ($ser_row = mysql_fetch_array($ser)) {
                                                       ?>
                                                                     <td align="center"><strong>
                                                                       <?php $c_adt = strftime("%Y-%m-%d", strtotime("$nextday -1 day "));
                                                                       $ser_id = $ser_row['ser_id'];
                                                                       @$co_sum = "select sum(rms_price) from room_services where rms_conform_no='" . $_REQUEST['conf_nof'] . "' and createdon='$c_adt' and rms_serv_id=$ser_id";
                                                                       @$sum_1 = mysql_query($co_sum);
                                                                       while (@$row_sum = mysql_fetch_array($sum_1)) {
                                                                         @$menti = $row_sum['sum(rms_price)'];
                                                                       }
                                                                       echo @$menti;
                                                                       ?>
                                                                     </strong></td>
                                                   <?php } ?>
                               
                               
                                                           <td align="center" style="width:150px;">&nbsp;<?php if ($resultInfo > 0): ?><?php
                                                                $c_adt = strftime("%Y-%m-%d", strtotime("$nextday -1 day "));
                                                                $ser_id = $ser_row['ser_id'];
                                                                @$co_sum = "select sum(rms_price) from room_services where rms_conform_no='" . $_REQUEST['conf_nof'] . "' and createdon='$c_adt'";
                                                                @$sum_1 = mysql_query($co_sum);
                                                                while (@$row_sum = mysql_fetch_array($sum_1)) {
                                                                  @$menti1 = $row_sum['sum(rms_price)'];
                                                                }
                                                                $resultInfo;
                                                                echo number_format(($resultInfo + $menti1), 2);
                                                                $finalPrice += ($resultInfo  ); ?><?php endif; ?></td>   
                            
                       
                      
                    
                                                <?php $nextday = strftime("%d-%m-%Y", strtotime("$today +" . ($rec) . " day"));
                                                $rec++;
                                                $resultInfo = 0;
                                                $menti = 0; ?>
                                <?php endfor; ?> 
                             </tr>


              <tr style="display:none">
                                <td align="center" ><strong><?php
                                $vvrf = explode(' ', $_POST['depar']);
                                echo $vvrf[0];

                                ?></strong></td> 
                                <td align="center" ><strong><?php


                                $R_Maint_Rsdomain = strstr($R_Maint_Rs, '.');
                                if ($R_Maint_Rsdomain == "") {
                                  echo $R_Maint_Rs . ".00";
                                } else {
                                  echo $R_Maint_Rs;
                                  //546532051  4861
                                }

                                ?></strong></td>
                               <?php $ser = mysql_query("SELECT * FROM `services`");
                               while ($ser_row = mysql_fetch_array($ser)) {
                                 ?>
                                                       <td align="center"><strong>
                                                         <?php $c_adt = strftime("%Y-%m-%d", strtotime("$nextday -1 day "));
                                                         $ser_id = $ser_row['ser_id'];
                                                         @$co_sum = "select sum(rms_price) from room_services where rms_conform_no='" . $_REQUEST['conf_nof'] . "'  and rms_serv_id=$ser_id";
                                                         @$sum_1 = mysql_query($co_sum);
                                                         while (@$row_sum = mysql_fetch_array($sum_1)) {
                                                           @$menti = $row_sum['sum(rms_price)'];
                                                         }
                                                         echo @$menti;
                                                         ?>
                                                       </strong></td>
                                     <?php } ?>
                                <td align="center" ><strong><?php echo number_format($finalPrice, 2); ?></strong></td>                          
                             </tr>
                             <?php if ($h > 0) { ?>
                                           <tr style="display:none">
                                           <td align="center">


                           <?php echo strftime("%d-%m-%Y", strtotime("$nextday -1 day ")); ?>
                                           </td>
                                           <td align="center" ><?php $payablekp = $payable;
                                           echo number_format(@$payable, 2); ?> </td>
               
                                                       <?php $ser = mysql_query("SELECT * FROM `services`");
                                                       while ($ser_row = mysql_fetch_array($ser)) {
                                                         ?>
                           <td align="center"><strong>
                             <?php $c_n;
                             $c_adt = strftime("%Y-%m-%d", strtotime("$nextday -1 day "));
                             $ser_id = $ser_row['ser_id'];
                             @$co_sum = "select sum(rms_price) from room_services where rms_conform_no='" . $_REQUEST['conf_nof'] . "' and createdon='$c_adt' and rms_serv_id=$ser_id";
                             @$sum_1 = mysql_query($co_sum);
                             while (@$row_sum = mysql_fetch_array($sum_1)) {
                               @$menti = $row_sum['sum(rms_price)'];
                             }
                             echo @$menti;
                             $menti_kp = $menti;
                             ?>
                           </strong></td>
                                                   <?php } ?>
                               
                                            <td align="center" >

                             <?php
                             $c_adt = strftime("%Y-%m-%d", strtotime("$nextday -1 day "));
                             $ser_id = $ser_row['ser_id'];
                             @$co_sum = "select sum(rms_price) from room_services where rms_conform_no='" . $_REQUEST['conf_nof'] . "' and createdon='$c_adt'";
                             @$sum_1 = mysql_query($co_sum);
                             while (@$row_sum = mysql_fetch_array($sum_1)) {
                               @$menti1 = $row_sum['sum(rms_price)'];
                             }
                             @$exter = @$payable + @$menti1;
                             echo number_format(@$payable + @$menti1, 2); ?> 
                            </td>
                                           </tr> 

                             <?php } else {
                               $exter = 0;
                             } ?> 
                       <tr>
                                <td align="center" ><strong><?php

                                echo date('d-m-Y');

                                ?></strong></td> 
                                <td align="center" ><strong><?php


                                $rrt = $payablekp + $R_Maint_Rs;



                                $vv = strstr($rrt, '.');
                                if ($vv == "") {
                                  echo $rrt . ".00";
                                } else {
                                  echo $rrt;
                                  //546532051  4861
                                }


                                ?></strong></td>
                 
                                          <?php $ser = mysql_query("SELECT * FROM `services`");
                                          while ($ser_row = mysql_fetch_array($ser)) {
                                            ?>
           <td align="center"><strong>
           <?php $c_adt = strftime("%Y-%m-%d", strtotime("$nextday -1 day "));
           $ser_id = $ser_row['ser_id'];
           @$co_sum = "select sum(rms_price) from room_services where rms_conform_no='" . $_REQUEST['conf_nof'] . "'  and rms_serv_id=$ser_id";
           @$sum_1 = mysql_query($co_sum);
           while (@$row_sum = mysql_fetch_array($sum_1)) {
             @$menti = $row_sum['sum(rms_price)'];
           }
           echo @$menti;
           $finalPrice = $finalPrice + $menti;
           ?>
           </strong></td>
                                     <?php } ?>
                    
                                <td align="center" ><strong><?php
                                 echo number_format($exter + $finalPrice, 2); ?></strong></td>                          
                             </tr>
                           </table>    
                                          </td>
                                       </tr>
                                
                                       <tr>
                                       <td>
                                        <table cellpadding="0" cellspacing="0" border="1" style="width:100%;font-size:13px;" align="center">
                                                <tr>
                                        
                                                      <?php


                                                      for ($j = 0; $j < count($rmSer); $j++) {
                                                        $Prop++; ?>              <?php } ?>
                                                      <td colspan="<?php if (!empty($rmSer))
                                                        echo $Prop;
                                                      else
                                                        echo '1'; ?>" rowspan="<?php
                                                          echo $rowSpan; ?>" align="left">Mode of Payment- <?php
                                                            echo $_POST['mod'] . "," . $_POST['bank_name'] . "," . $_POST['cheque_date'] . "," . $_POST['cheque_no']; ?>
                                                            <br>Advance Receipt No.<strong><?php echo ($html['re_fax']); ?></strong>
                                                            <br>Pay Rupees -
                                                        <strong>
                                                      <?php
                                                      $taxForF = (($finalPrice + $exter) * $overalltax) / 100;

                                                      $rs_word = round(($finalPrice + $exter + $taxForF) - ($html['re_adv'] - $_POST['add_amount']));

                                                      echo convertNum($rs_word);

                                                      ?>                                       </strong> Only <br>
                                                      Comments:-<strong><?php echo $_POST['comment']; ?></strong></td>  
                                                      <td align="right"><div align="center"><strong>Taxable
        Value</strong></div></td>   
                                                      <td align="center" style="background:#CCC;"><?php $Taxable = $finalPrice + $exter;
                                                      echo number_format($Taxable, 2);
                                                      //mysql_query("UPDATE reservation SET amount='$finalPrice'  WHERE reservation_id=$bill_id");
                                                      ?></td>    
                                                       <?php ?> 
                                         </tr>

        <?php

        if ($divide == 'NO') {
          ?>
                <tr>
                                            
                                                              <td align="right"><div align="center"><strong>IGST <?php echo $igstPercent; ?>%</strong></div></td>  
                                                              <td align="center"  style="background:#CCC;"><?php
                                                              $tax = ($Taxable * $igstPercent) / 100;
                                                              echo number_format($tax, 2);
                                                              ?></td>    
                                              
                                                           </tr>
                  <?php
        } else {
          ?>
             <tr>
                                            
                                                        <td align="right"><div align="center"><strong>SGST <?php echo $sgstPercent; ?>%</strong></div></td>  
                                                        <td align="center"  style="background:#CCC;"><?php
                                                        $tax1 = ($Taxable * $sgstPercent) / 100;
                                                        echo number_format($tax1, 2);
                                                        ?></td>    
                                        
                                                     </tr> <tr>
                                            
                                                        <td align="right"><div align="center"><strong>CGST <?php echo $cgstPercent; ?>%</strong></div></td>  
                                                        <td align="center"  style="background:#CCC;"><?php
                                                        $tax2 = ($Taxable * $cgstPercent) / 100;
                                                        echo number_format($tax2, 2);
                                                        $tax = $tax1 + $tax2;
                                                        ?></td>    
                                        
                                                     </tr>
                  <?php
        }
        ?><tr>
                                            
        <td align="right"><div align="center"><strong>Total  </strong></div></td>  
        <td align="center"  style="background:#CCC;"><?php
        $amount = $Taxable + $tax;
        echo number_format($amount, 2);
        ?></td>    

        </tr>

                                 
                                                    <tr>
                                                      <?php ?>
                                                      <?php for ($j = 0; $j < count($rmSer); $j++) {
                                                        $Prop1++; ?>              <?php } ?>
                                                      <td align="right"><div align="center"><strong>Advance</strong></div></td>  
                                                      <td align="center"  style="background:#CCC;"><?php @$re_adv = ($html['re_adv']);
                                                      if ($re_adv == 0) {
                                                        echo "0.00";
                                                      } else {
                                                        echo number_format($re_adv, 2);
                                                      }
                                                      ?></td>    
                                                       <?php ?> 
                                                   </tr>
                                                   <tr>
                                                      <td align="right"><div align="center"><strong>Other Amount Add or less</strong></div></td>
                                                      <td align="center"  style="background: #999;"><?php $AMOUNT1 = $_POST['add_amount'];
                                                      if ($AMOUNT1 == 0) {
                                                        echo "0.00";
                                                      } else {
                                                        echo number_format($AMOUNT1, 2);
                                                      }

                                                      ?></td>
                                         </tr>
                                                   <tr>
                                                      <td align="right"><div align="center"><strong>Net Amount</strong></div></td>
                                                      <td align="center"  style="background: #999;"><?php echo number_format(round(($amount - $html['re_adv'] + $AMOUNT1)), 2); ?></td>
                                         </tr>
                                    
                                    
                                         </table>
                                       </td>
                                       </tr> <tr>
                                       <td >
                                        <br/>                                  <br/>
                                          </td>
                                       </tr>
                                 
                                       <tr>
                                       <td>
                                        <table cellpadding="0" cellspacing="0" border="1" style="width:100%;font-size:13px;" align="center">
                                                <tr >  <?php

 
                                                for ($j = 0; $j < count($rmSer); $j++) {
                                                  $Prop++; ?>              <?php } ?>
                                                      <td align="center" style="width: 50%;" rowspan="2" colspan="<?php if (!empty($rmSer))
                                                        echo $Prop;
                                                      else
                                                        echo '1'; ?>"  align="left"> <strong> HSN/SAC</strong> </td>  
                                                      <td align="right" rowspan="2"><div align="center"> <strong>Taxable
        Value</strong></div></td>   
                                                      <td align="center" colspan="2" > <strong>Integrated Tax </strong></td>  
                                                        <td align="center" rowspan="2" > <strong> Total Tax  Amount </strong></td>    
                                                
                                         </tr>
                                  
                                 
                                              
                                         <tr  >      
                                         <td align="center" > <strong>Rate </td>  
                                                        <td align="center"  >   <strong>  Amount </strong> </td> 
                                         </tr>


                                         <?php

                                         if ($divide == 'NO') {
                                           ?>
                  <tr  >      
                                               <td align="center" colspan="<?php if (!empty($rmSer))
                                                        echo $Prop;
                                                      else
                                                        echo '1'; ?>" >996311 </td>  
                                               <td align="center" > <?php echo $Taxable; ?> </td>  
                                               <td align="center" ><?php echo $igstPercent; ?>% </td>  
                                                              <td align="center"  > <?php
                                                              $tax = ($Taxable * $igstPercent) / 100;
                                                              echo number_format($tax, 2);
                                                              ?> </td> 
                                                               <td align="center"  >    <?php echo number_format($tax, 2); ?>  </td> 
                                               </tr>
            <?php
                                         } else {
                                           ?>
                                              <tr  >      
                                                                           <td align="center" colspan="<?php if (!empty($rmSer))
                                                        echo $Prop;
                                                      else
                                                        echo '1'; ?>"  >996311 </td>  
                                                                           <td align="center" > <?php echo $Taxable; ?> </td>  
                                                                           <td align="center" ><?php echo $sgstPercent; ?>% </td>  
                                                                                          <td align="center"  > <?php
                                                                                          $tax1 = ($Taxable * $sgstPercent) / 100;
                                                                                          echo number_format($tax1, 2);
                                                                                          ?> </td> 
                                                                                           <td align="center"  >    <?php echo number_format($tax1, 2); ?>  </td> 
                                                                           </tr>
                                                                           <tr  >      
                                                                           <td align="center" colspan="<?php if (!empty($rmSer))
                                                        echo $Prop;
                                                      else
                                                        echo '1'; ?>"  >  </td>  
                                                                           <td align="center" > <?php echo $Taxable; ?> </td>  
                                                                           <td align="center" ><?php echo $cgstPercent; ?>% </td>  
                                                                                          <td align="center"  > <?php
                                                                                          $tax2 = ($Taxable * $cgstPercent) / 100;

                                                                                          $tax = $tax1 + $tax2;
                                                                                          echo number_format($tax2, 2);
                                                                                          ?> </td> 
                                                                                           <td align="center"  >
                                                                                                <?php
                                                                                                echo number_format($tax2, 2); ?>
                                                                                      
                                                                                      
                                                                                              </td> 
                                                                           </tr>
                                        <?php
                                         }
                                         ?>
 
     <tr  >      
                                           <td align="right" colspan="<?php if (!empty($rmSer))
                                                        echo $Prop;
                                                      else
                                                        echo '1'; ?>"  > <strong>TOTAL&nbsp;</strong> </td>  
                                           <td align="center" > <strong><?php echo $Taxable; ?></strong> </td>  
                                           <td align="center" >  </td>  
                                                          <td align="center"  > <strong><?php

                                                          echo number_format($tax, 2);
                                                          ?> </strong></td> 
                                                           <td align="center"  >   <strong> <?php echo number_format($tax, 2); ?></strong>  </td> 
                                           </tr>
  
                                         </table>
                                       </td>
                                       </tr>
                                  </table>                   
                                                     <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                <tr>
                  <td width="45%" align="left" height="50" valign="bottom">
                                                    __________________ <br/>GUEST SIGNATURE</td>
                  <td width="55%" align="right" valign="bottom">  <br/><br/>
                  <br/> ______________________ <br/>AUTHORISED SIGNATURE<br/>  FOR SRI SAMMAD SHIKHARJI VIKAS SAMITI
      </td>
                </tr>
                <tr>
                                                     <td align="center" colspan="2" >&nbsp;<b>
                                                     <br/><br/>
                                                     SUBJECT TO GIRIDIH JURISDICTION
                                        </b><br/> <u>This is Computer Generated Invoice</u></td>
                                                   </tr>
                                  
              </table>
                        
                                    
                             
                          
                   <?php endif;
                   //////////////////////////////////////////////////////            donation//////////////////////////////////////////////				   
                   ?>
            <?php if (isset($_REQUEST['submit2'])): ?>
                                       <?php
                                       //528 // 232
                                       if ($_SESSION['dbname'] == "geniohotel_2") {
                                         $bil = mysql_query("select max(billno) As billno from reservation where billno < '1500' ");
                                         while ($row_bil = mysql_fetch_array($bil)) {
                                           $bil_no = $row_bil['billno'];
                                         }
                                         if ($bil_no == 170) {
                                           $bil_no = 224;
                                         }
                                         $next_bilno = sprintf("%03d", $bil_no + 1);
                                         if ($next_bilno == "528") {
                                           $bil = mysql_query("select max(billno) As billno from reservation  ");
                                           while ($row_bil = mysql_fetch_array($bil)) {
                                             $bil_no = $row_bil['billno'];
                                           }
                                           $next_bilno = sprintf("%03d", $bil_no + 1);
                                         }

                                       } else {
                                         $bil = mysql_query("select max(billno) As billno from reservation  ");
                                         while ($row_bil = mysql_fetch_array($bil)) {
                                           $bil_no = $row_bil['billno'];
                                         }
                                         $next_bilno = sprintf("%03d", $bil_no + 1);

                                       }
                                       $date1 = $_POST['arrival'];
                                       $date2 = $_POST['depar'];
                                       $con_fig = $_POST['conf_nof'];
                                       if(!isset($_GET['con'])){
                                       $qw = mysql_query("INSERT INTO billining(bill_id, conf_nof, arrival, depar, add_amount, p_type, bank_name, ch_date, ch_no, comment) VALUES  ('','$con_fig','$date1','$date2','" . $_POST['add_amount'] . "','" . $_POST['mod'] . "','" . $_POST['bank_name'] . "','" . $_POST['cheque_date'] . "','" . $_POST['cheque_no'] . "','" . $_POST['comment'] . "')");
                                         }  $sql_room = mysql_query("select*from rooinventory where confirmation='$con_fig'");
                                       while ($row_room = mysql_fetch_array($sql_room)) {
                                         $room_id = $row_room['id'];
                                         if(!isset($_GET['con'])){
                                         mysql_query("UPDATE rooinventory SET status='HouseKeeping' WHERE id=$room_id");
                                         }
                                       }
                                       $sql_r = mysql_query("select*from reservation where confirmation='$con_fig'");
                                       $row_r = mysql_fetch_array($sql_r);
                                       $room_id1 = $row_r['reservation_id'];
                                       if(!isset($_GET['con'])){
                                       mysql_query("UPDATE reservation SET status='HouseKeeping',tax_percent=12, billno='$next_bilno' WHERE reservation_id=$room_id1");
                                       }

                                       ?><table border="1" width="20%" align="center" style="margin-top:-20px">
              <tr>
              <td align="center" ><b>ROOM&nbsp;&nbsp;MAINTENANCE</b> </td>
              </tr>
              </table>
                                  <table cellpadding="0" cellspacing="0" border="1" style="width:100%;font-size:13px;" align="center">
                                      <tr>
                                          <td width="97%">
                                            <table cellpadding="0" cellspacing="0" border="1" style="width:100%;font-size:12px;" align="center">
                                                  <tr>
                                                      <td style="width:100px;"><strong>Customer ID:</strong></td><td><?php echo $html['reservation_id']; ?></td>
                                                      <td style="width:100px;"><strong>Serial No..</strong></td>
                                                      <td style="width:30%;"><?php 
                                                      
                                                      echo $next_bilno; ?></td>
                                                  </tr>
                                                   <tr>
                                                      <td style="width:100px;"><strong>Guest Name:</strong></td><td><?php echo $html['firstname'] . ' ' . $html['lastname']; ?></td>
                                                      <td style="width:100px;"><strong>No.&nbsp;Of&nbsp;Person:</strong></td>
                                                      <td>&nbsp;<?php echo $html['no_pertion']; ?> Member</td>
                                                  </tr>
                                                   <tr>
                                                      <td style="width:100px;"><strong>Company:</strong></td><td>&nbsp;<?php echo $html['company']; ?></td>
                                                      <td style="width:100px;"><strong>Arrival&nbsp;Date&nbsp;&&nbsp;Time:</strong></td>
                                                      <td>&nbsp;<?php echo date("d-m-Y h:i A", strtotime($_POST['arrival'])); ?></td>
                                                  </tr>
                                                   <tr>

                                                      <td style="width:100px;" rowspan="2"><strong>Address:</strong></td>
                                                      <td rowspan="2" valign="top">
                                                          <?php echo $html['address'] . '<br/>City: ' . $html['city'] . '<br/>State: ' . $html['state']; ?>
                                                      </td>
                                                      <td style="width:100px;"><strong>Dept&nbsp;Date&nbsp;&&nbsp;Time:</strong></td>
                                                      <td>&nbsp;<?php echo $_POST['depar']; ?></td>
                                                  </tr>
                                                   <tr>
                                                      <td style="width:100px;"><strong>Total&nbsp;Rooms:</strong></td>
                                                      <td>&nbsp;&nbsp;<?php echo count($val); ?></td>
                                                  </tr>
                                                   <tr>
                                                      <td style="width:100px;"><strong>R.Maint.Chg:</strong></td>
                                                      <td colspan="3"><?php $sepa = 0;
                                                      for ($i = 0; $i < count($val); $i++) { ?>
                                                                            <?php $room = $val[$i]['qty'];
                                                                            $sql_room = "select * from rooinventory where qty='$room' and confirmation='" . $_REQUEST['conf_nof'] . "' ";
                                                                            $data1_room = mysql_query($sql_room);
                                                                            $row1_room = mysql_fetch_assoc($data1_room);

                                                                            $sep1 = $row1_room['price'];
                                                                            $sep2 = $sepa + $sep1;
                                                                            $sepa = $sep2;

                                                                            ?>
                                                          <?php }


                                                      $domainvvv = strstr($sepa, '.');
                                                      if ($domainvvv == "") {
                                                        echo $sepa . ".00";
                                                      } else {
                                                        echo $sepa;
                                                        //546532051  4861
                                                      }
                                                      ?>
                                                
                                                         Rupes Only....</td>
                                                  </tr>
                                                   <tr>
                                                      <td style="width:100px;"><strong>Room No:</strong></td>
                                                      <td colspan="3">
                                        
                                                          <?php $sep = "";
                                                          for ($i = 0; $i < count($val); $i++) { ?>
                                                                            <?php echo $sep . $val[$i]['qty'];
                                                                            $sep = ','; ?>
                                                          <?php } ?>
                                                      </td>
                                                  </tr>
                                              </table>
                                          </td>
                                    </tr>
                                       <tr>
                                          <td>&nbsp;</td>
                                       </tr>                            
                                       <tr>
                                          <td height="400px" valign="top">
                         
                          
                                      <table cellpadding="0" cellspacing="0" border="1" style="width:100%; font-size:12px;" align="center" >
                             
                                              <tr>
                                                 <td align="center" ><strong>&nbsp;&nbsp;&nbsp;&nbsp;Date&nbsp;</strong></td> 
                                                 <td align="center" ><strong>R.Maint.</strong></td>
                                                 <?php $ser = mysql_query("SELECT * FROM `services`");
                                                 while ($ser_row = mysql_fetch_array($ser)) {
                                                   ?>
                                                                        <td align="center"><strong>
                                                  <?php

                                                  $r = str_replace(' ', '&nbsp;', $ser_row['ser_name']);
                                                  echo $r;
                                                  ?></strong></td>
                                                      <?php } ?> 
                                                 <td align="center" ><strong>Sub Total Rs.</strong></td>                          
                                              </tr>
                                              <?php

                                                $today = $_POST['arrival'];
                                                $today = strftime("%Y-%m-%d", strtotime("$today +1 day"));
                                              $disp = $_POST['depar'];
                                              $rec = 1;
                                              $REcTest = 0;
                                              $resultInfo = 0;
                                              $mentiTotal = 0;
                                              $finalPrice = 0;
                                              $ts1 = strtotime($_POST['arrival']);
                                              $menti = 0;
                                              $resultInfoTot = 0;
                                              $ts2 = strtotime($_POST['depar']);
                                              $nextday = $today;

                                              $seconds_diff = $ts2 - $ts1;
                                              @$Fildif1 = ($seconds_diff / (60 * 60 * 24)) % 365;
                                              @$h1 = ($seconds_diff / (60 * 60)) % 24;
                                              if ($Fildif1 == 0) {
                                                @$Fildif = 1;
                                                @$h = 0;
                                              } else {
                                                @$Fildif = @$Fildif1;
                                                @$h = @$h1;
                                              }

                                              $R_Maint_Rs = 0;
                                              for ($r = 0; $r < $Fildif; $r++): ?> 
                                                              <?php for ($p = 0; $p < count($val); $p++): ?>
                                                                                <?php

                                                                                $dateNew = $val[$p]['arrival'];
                                                                                $c = strftime("%d-%m-%Y", strtotime("$nextday -1 day "));
                                                                                if (strtotime($dateNew) <= strtotime($today)) {
                                                                                  // $resultInfo += getRoom_actualprice($val[$p]['room'],$con_fig,$c);
                                                                                  // $resultInfoTot +=$resultInfo;
                                                                                }
                                                                                ?> 
                                                                  <?php endfor; ?>
                                                                    <tr valign="top" style="display:none">
                                 
                                    
                                       
                                        
                                                                        <td  align="center" style="width:105px;"><?php echo $c; ?></td>
                                                                        <td align="center" ><?php







                                                                        $dsi = explode(' ', $disp);
                                                                        $dsi[0];


                                                                        $val = 0;
                                                                        $sql = "select * from rooinventory where confirmation='$con_fig'   ";
                                                                        $data1 = mysql_query($sql);


                                                                        $deu = 0;
                                                                        $d = 0;
                                                                        $id = 0;
                                                                        while ($row1 = mysql_fetch_assoc($data1)) {
                                                                          $arrivalggg = $row1['arrival'];
                                                                          $arrivalgggv = explode(' ', $arrivalggg);
                                                                          $arrivalgggv[0];

                                                                          if (strtotime($c) == strtotime($arrivalgggv[0])) {

                                                                            $d = $d + $row1['price'];

                                                                            $deu++;


                                                                          }
                                                                          $departureccc = $row1['departure'];
                                                                          if (strtotime($arrivalggg) <= strtotime($c) && strtotime($departureccc) >= strtotime($c)) {

                                                                            $val += $row1['price'];
                                                                          }
                                                                        }



                                                                        $d;
                                                                        "<br/>";
                                                                        if ($deu == 0) {
                                                                          $deu = 1;
                                                                        }
                                                                        $fe = $val + $d;
                                                                        $domain = strstr($fe, '.');
                                                                        if ($domain == "") {
                                                                          echo $fe . ".00";
                                                                        } else {
                                                                          echo $fe;
                                                                          //546532051  4861
                                                                        }

                                                                        $R_Maint_Rs = $R_Maint_Rs + $fe;
                                                                        $d = 0;
                                                                        $deu = 0;
                                                                        $resultInfo += getRoom_actualprice($fe);
                                                                        $resultInfoTot += $resultInfo;
                                                                        $payable = $resultInfo;

                                                                        ?> </td>
                                                                      <?php $ser = mysql_query("SELECT * FROM `services`");
                                                                      while ($ser_row = mysql_fetch_array($ser)) {
                                                                        ?>
                                                                                      <td align="center"><strong>
                                                                                        <?php $c_adt = strftime("%Y-%m-%d", strtotime("$nextday -1 day "));
                                                                                        $ser_id = $ser_row['ser_id'];
                                                                                        @$co_sum = "select sum(rms_price) from room_services where rms_conform_no='" . $_REQUEST['conf_nof'] . "' and createdon='$c_adt' and rms_serv_id=$ser_id";
                                                                                        @$sum_1 = mysql_query($co_sum);
                                                                                        while (@$row_sum = mysql_fetch_array($sum_1)) {
                                                                                          @$menti = $row_sum['sum(rms_price)'];
                                                                                        }
                                                                                        echo @$menti;
                                                                                        ?>
                                                                                      </strong></td>
                                                                    <?php } ?>
                                                
                                                
                                                                            <td align="center" style="width:150px;">&nbsp;<?php if ($resultInfo > 0): ?><?php
                                                                                 $c_adt = strftime("%Y-%m-%d", strtotime("$nextday -1 day "));
                                                                                 $ser_id = $ser_row['ser_id'];
                                                                                 @$co_sum = "select sum(rms_price) from room_services where rms_conform_no='" . $_REQUEST['conf_nof'] . "' and createdon='$c_adt'";
                                                                                 @$sum_1 = mysql_query($co_sum);
                                                                                 while (@$row_sum = mysql_fetch_array($sum_1)) {
                                                                                   @$menti1 = $row_sum['sum(rms_price)'];
                                                                                 }
                                                                                 $resultInfo;
                                                                                 echo number_format(($resultInfo + $menti1), 2);
                                                                                 $finalPrice += ($resultInfo + $menti1); ?><?php endif; ?></td>   
                                             
                                        
                                       
                                     
                                                                 <?php $nextday = strftime("%d-%m-%Y", strtotime("$today +" . ($rec) . " day"));
                                                                 $rec++;
                                                                 $resultInfo = 0;
                                                                 $menti = 0; ?>
                                                 <?php endfor; ?> 
                                              </tr>
                
                
                               <tr style="display:none">
                                                 <td align="center" ><strong><?php
                                                 $vvrf = explode(' ', $_POST['depar']);
                                                 echo $vvrf[0];

                                                 ?></strong></td> 
                                                 <td align="center" ><strong><?php


                                                 $R_Maint_Rsdomain = strstr($R_Maint_Rs, '.');
                                                 if ($R_Maint_Rsdomain == "") {
                                                   echo $R_Maint_Rs . ".00";
                                                 } else {
                                                   echo $R_Maint_Rs;
                                                   //546532051  4861
                                                 }

                                                 ?></strong></td>
                                                <?php $ser = mysql_query("SELECT * FROM `services`");
                                                while ($ser_row = mysql_fetch_array($ser)) {
                                                  ?>
                                                                        <td align="center"><strong>
                                                                          <?php $c_adt = strftime("%Y-%m-%d", strtotime("$nextday -1 day "));
                                                                          $ser_id = $ser_row['ser_id'];
                                                                          @$co_sum = "select sum(rms_price) from room_services where rms_conform_no='" . $_REQUEST['conf_nof'] . "'  and rms_serv_id=$ser_id";
                                                                          @$sum_1 = mysql_query($co_sum);
                                                                          while (@$row_sum = mysql_fetch_array($sum_1)) {
                                                                            @$menti = $row_sum['sum(rms_price)'];
                                                                          }
                                                                          echo @$menti;
                                                                          ?>
                                                                        </strong></td>
                                                      <?php } ?>
                                                 <td align="center" ><strong><?php echo number_format($finalPrice, 2); ?></strong></td>                          
                                              </tr>
                                              <?php if ($h > 0) { ?>
                                                            <tr style="display:none">
                                                            <td align="center">
                
                
                                            <?php echo strftime("%d-%m-%Y", strtotime("$nextday -1 day ")); ?>
                                                            </td>
                                                            <td align="center" ><?php $payablekp = $payable;
                                                            echo number_format(@$payable, 2); ?> </td>
                                
                                                                        <?php $ser = mysql_query("SELECT * FROM `services`");
                                                                        while ($ser_row = mysql_fetch_array($ser)) {
                                                                          ?>
                                            <td align="center"><strong>
                                              <?php $c_n;
                                              $c_adt = strftime("%Y-%m-%d", strtotime("$nextday -1 day "));
                                              $ser_id = $ser_row['ser_id'];
                                              @$co_sum = "select sum(rms_price) from room_services where rms_conform_no='" . $_REQUEST['conf_nof'] . "' and createdon='$c_adt' and rms_serv_id=$ser_id";
                                              @$sum_1 = mysql_query($co_sum);
                                              while (@$row_sum = mysql_fetch_array($sum_1)) {
                                                @$menti = $row_sum['sum(rms_price)'];
                                              }
                                              echo @$menti;
                                              $menti_kp = $menti;
                                              ?>
                                            </strong></td>
                                                                    <?php } ?>
                                                
                                                             <td align="center" >
                 
                                              <?php
                                              $c_adt = strftime("%Y-%m-%d", strtotime("$nextday -1 day "));
                                              $ser_id = $ser_row['ser_id'];
                                              @$co_sum = "select sum(rms_price) from room_services where rms_conform_no='" . $_REQUEST['conf_nof'] . "' and createdon='$c_adt'";
                                              @$sum_1 = mysql_query($co_sum);
                                              while (@$row_sum = mysql_fetch_array($sum_1)) {
                                                @$menti1 = $row_sum['sum(rms_price)'];
                                              }
                                              @$exter = @$payable + @$menti1;
                                              echo number_format(@$payable + @$menti1, 2); ?> 
                                             </td>
                                                            </tr> 
                
                                              <?php } else {
                                                $exter = 0;
                                              } ?> 
                                        <tr>
                                                 <td align="center" ><strong><?php

                                                 echo date('d-m-Y');

                                                 ?></strong></td> 
                                                 <td align="center" ><strong><?php


                                                 $rrt = $payablekp + $R_Maint_Rs;



                                                 $vv = strstr($rrt, '.');
                                                 if ($vv == "") {
                                                   echo $rrt . ".00";
                                                 } else {
                                                   echo $rrt;
                                                   //546532051  4861
                                                 }


                                                 ?></strong></td>
                                  
                                                           <?php $ser = mysql_query("SELECT * FROM `services`");
                                                           while ($ser_row = mysql_fetch_array($ser)) {
                                                             ?>
                            <td align="center"><strong>
                            <?php $c_adt = strftime("%Y-%m-%d", strtotime("$nextday -1 day "));
                            $ser_id = $ser_row['ser_id'];
                            @$co_sum = "select sum(rms_price) from room_services where rms_conform_no='" . $_REQUEST['conf_nof'] . "'  and rms_serv_id=$ser_id";
                            @$sum_1 = mysql_query($co_sum);
                            while (@$row_sum = mysql_fetch_array($sum_1)) {
                              @$menti = $row_sum['sum(rms_price)'];
                            }
                            echo @$menti;
                            ?>
                            </strong></td>
                                                      <?php } ?>
                                     
                                                 <td align="center" ><strong><?php echo number_format($exter + $finalPrice, 2); ?></strong></td>                          
                                              </tr>
                                            </table>     
                                          </td>
                                       </tr>
                                       <tr>
                                       <td>
                                        <table cellpadding="0" cellspacing="0" border="1" style="width:100%;font-size:13px;" align="center">
                                                <tr>
                                        
                                                      <?php for ($j = 0; $j < count($rmSer); $j++) {
                                                        $Prop++; ?>              <?php } ?>
                                                      <td colspan="<?php if (!empty($rmSer))
                                                        echo $Prop;
                                                      else
                                                        echo '1'; ?>" rowspan="4" align="left">Mode of Payment  - <?php
                                                          echo $_POST['mod'] . "," . $_POST['bank_name'] . "," . $_POST['cheque_date'] . "," . $_POST['cheque_no']; ?>
                                                          <br>Advance Receipt No.<strong><?php echo ($html['re_fax']); ?></strong>
                                                          <br>Pay Rupees -
                                                        <strong>
                                                      <?php $rs_word = ($finalPrice + $exter) - ($html['re_adv'] - $_POST['add_amount']);
                                                      $ones = array(
                                                        "",
                                                        " One",
                                                        " Two",
                                                        " Three",
                                                        " Four",
                                                        " Five",
                                                        " Six",
                                                        " Seven",
                                                        " Eight",
                                                        " Nine",
                                                        " Ten",
                                                        " Eleven",
                                                        " Twelve",
                                                        " Thirteen",
                                                        " Fourteen",
                                                        " Fifteen",
                                                        " Sixteen",
                                                        " Seventeen",
                                                        " Eighteen",
                                                        " Nineteen"
                                                      );

                                                      $tens = array(
                                                        "",
                                                        "",
                                                        " Twenty",
                                                        " Thirty",
                                                        " Forty",
                                                        " Fifty",
                                                        " Sixty",
                                                        " Seventy",
                                                        " Eighty",
                                                        " Ninety"
                                                      );

                                                      $triplets = array(
                                                        "",
                                                        " Thousand",
                                                        " Million",
                                                        " Billion",
                                                        " Trillion",
                                                        " Quadrillion",
                                                        " Quintillion",
                                                        " Sextillion",
                                                        " Septillion",
                                                        " Octillion",
                                                        " Nonillion"
                                                      );

                                                      // recursive fn, converts three digits per pass
                                                      function convertTri($num, $tri)
                                                      {
                                                        global $ones, $tens, $triplets;

                                                        // chunk the number, ...rxyy
                                                        $r = (int) ($num / 1000);
                                                        $x = ($num / 100) % 10;
                                                        $y = $num % 100;

                                                        // init the output string
                                                        $str = "";

                                                        // do hundreds
                                                        if ($x > 0)
                                                          $str = $ones[$x] . " Hundred";

                                                        // do ones and tens
                                                        if ($y < 20)
                                                          $str .= $ones[$y];
                                                        else
                                                          $str .= $tens[(int) ($y / 10)] . $ones[$y % 10];

                                                        // add triplet modifier only if there
                                                        // is some output to be modified...
                                                        if ($str != "")
                                                          $str .= $triplets[$tri];

                                                        // continue recursing?
                                                        if ($r > 0)
                                                          return convertTri($r, $tri + 1) . $str;
                                                        else
                                                          return $str;
                                                      }

                                                      // returns the number as an anglicized string
                                                      function convertNum($num)
                                                      {
                                                        $num = (int) $num; // make sure it's an integer
                                                    
                                                        if ($num < 0)
                                                          return "Refund " . convertTri(-$num, 0);

                                                        if ($num == 0)
                                                          return "zero";

                                                        return convertTri($num, 0);
                                                      }

                                                      // Returns an integer in -10^9 .. 10^9
                                                      // with log distribution
                                                      function makeLogRand()
                                                      {
                                                        $sign = mt_rand(0, 1) * 2 - 1;
                                                        $val = randThousand() * 1000000
                                                          + randThousand() * 1000
                                                          + randThousand();
                                                        $scale = mt_rand(-9, 0);

                                                        return $sign * (int) ($val * pow(10.0, $scale));
                                                      }
                                                      echo convertNum($rs_word);

                                                      ?>                                       </strong> Only /.. <br>
                                                      Comments:-<strong><?php echo $_POST['comment']; ?></strong></td>  
                                                      <td align="right"><div align="center"><strong>Total</strong></div></td>   
                                                      <td align="center" style="background:#CCC;"><?php $amount = $finalPrice + $exter;
                                                      echo number_format($amount, 2);
                                                      //mysql_query("UPDATE reservation SET amount='$finalPrice'  WHERE reservation_id=$bill_id");
                                                      ?></td>    
                                                       <?php ?> 
                                         </tr>
                                                    <tr>
                                                      <?php ?>
                                                      <?php for ($j = 0; $j < count($rmSer); $j++) {
                                                        $Prop1++; ?>              <?php } ?>
                                                      <td align="right"><div align="center"><strong>Security Deposit Refund </strong></div></td>  
                                                      <td align="center"  style="background:#CCC;"><?php @$re_adv = ($html['re_adv']);
                                                      if ($re_adv == 0) {
                                                        echo "0.00";
                                                      } else {
                                                        echo number_format($re_adv, 2);
                                                      }
                                                      ?></td>    
                                                       <?php ?> 
                                                   </Tr>
                                                   <tr>
                                                      <td align="right"><div align="center"><strong>Other Amount Add or less</strong></div></td>
                                                      <td align="center"  style="background: #999;"><?php $AMOUNT1 = $_POST['add_amount'];
                                                      if ($AMOUNT1 == 0) {
                                                        echo "0.00";
                                                      } else {
                                                        echo number_format($AMOUNT1, 2);
                                                      }

                                                      ?></td>
                                         </tr>
                                                   <tr>
                                                      <td align="right"><div align="center"><strong>Net Amount</strong></div></td>
                                                      <td align="center"  style="background: #999;"><B><?php echo number_format(($amount - $html['re_adv'] + $AMOUNT1), 2); ?></b></td>
                                         </tr>
                                                <tr>
                                                      <td colspan="3" align="right"><div align="center"></div></td>
                                                                   </tr>    
                                    
                                         </table>
                                       </td>
                                       </tr>
                                  </table>                   
                                                     <table style="margin-top:-10px" width="100%" border="0" cellspacing="0" cellpadding="0" >
                <tr>
                  <td width="45%" align="left" height="100" valign="bottom">&nbsp;&nbsp;  ____________<br>
                                                    <strong>&nbsp;&nbsp;Guest Signature</strong></td>
                  <td width="55%" align="right" valign="bottom"><br> _________________________&nbsp;&nbsp;
                                                       <br> <strong> Signature
              For UPPB</strong>&nbsp;&nbsp;</td>
                </tr>
                <tr>
                                                     <td align="center" colspan="2" style="padding:15px;">&nbsp;<strong><u>THANK YOU FOR STAYING WITH US</u></strong></td>
                                                   </tr>
                                   <tr>
                                                     <td align="center" colspan="2" style="padding:15px;">&nbsp;<img src="pic.png" width="150" height="23" title="Logo ISO"></td>
                                                   </tr>
              </table>
          <?php endif;

            ?>
        </div>
                </div>
                <div id="content" class="clearfix" align="right">
                    <span style=" font-size:18px;">
                    <strong>Please Generate result</strong></span>
                    <input type="button" value="Generate Slip" onClick="PrintDiv();"  style="height:30px;width:200px;">
                </div>
                
                
        <div id="footer" class="radius-bottom">
          2011-12 ©
          <a class="afooter-link" href="">TurboAdmin 1.1</a>
          by
          <a class="afooter-link" href="">Begie</a>
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
  </div>
  <script src="../js/jquery.js"></script>
  <script type="text/javascript">
$(function() {
$(".delbutton").click(function(){
//Save the link in a variable called element
var element = $(this);
//Find the id of the link that was clicked
var del_id = element.attr("id");
//Built a url to send
var info = 'id=' + del_id;
 if(confirm("Sure you want to delete this update? There is NO undo!"))
      {
 $.ajax({
   type: "GET",
   //url: "deleteroominventory.php",
   data: info,
   success: function(){
   
   }
 });
         $(this).parents(".record").animate({ backgroundColor: "#fbc7c7" }, "fast")
    .animate({ opacity: "hide" }, "slow");
 }
return false;
});
});
</script>
</body>
</html>