<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <script language="JavaScript" type="text/javascript">
  
/**************************************************************************************
  htmlDatePicker v0.1
  
  Copyright (c) 2005, Jason Powell
  All Rights Reserved

  Redistribution and use in source and binary forms, with or without modification, are 
    permitted provided that the following conditions are met:

    * Redistributions of source code must retain the above copyright notice, this list of 
      conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright notice, this list 
      of conditions and the following disclaimer in the documentation and/or other materials 
      provided with the distribution.
    * Neither the name of the product nor the names of its contributors may be used to 
      endorse or promote products derived from this software without specific prior 
      written permission.

  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS 
  OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF 
  MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL 
  THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, 
  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE 
  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED 
  AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING 
  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED 
  OF THE POSSIBILITY OF SUCH DAMAGE.
  
  -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-==-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-

  
***************************************************************************************/
// User Changeable Vars
var HighlightToday  = true;    // use true or false to have the current day highlighted
var DisablePast    = false;    // use true or false to allow past dates to be selectable
// The month names in your native language can be substituted below
var MonthNames = new Array("January","February","March","April","May","June","July","August","September","October","November","December");

// Global Vars
var now = new Date();
var dest = null;
var ny = now.getFullYear(); // Today's Date
var nm = now.getMonth();
var nd = now.getDate();
var sy = 0; // currently Selected date
var sm = 0;
var sd = 0;
var y = now.getFullYear(); // Working Date
var m = now.getMonth();
var d = now.getDate();
var l = 0;
var t = 0;
var MonthLengths = new Array(31,28,31,30,31,30,31,31,30,31,30,31);

/*
  Function: GetDate(control)

  Arguments:
    control = ID of destination control
*/
function GetDate() {
  EnsureCalendarExists();
  DestroyCalendar();
  // One arguments is required, the rest are optional
  // First arguments must be the ID of the destination control
  if(arguments[0] == null || arguments[0] == "") {
    // arguments not defined, so display error and quit
    alert("ERROR: Destination control required in funciton call GetDate()");
    return;
  } else {
    // copy argument
    dest = arguments[0];
  }
  y = now.getFullYear();
  m = now.getMonth();
  d = now.getDate();
  sm = 0;
  sd = 0;
  sy = 0;
  var cdval = dest.value;
  if(/\d{1,2}.\d{1,2}.\d{4}/.test(dest.value)) {
    // element contains a date, so set the shown date
    var vParts = cdval.split("/"); // assume mm/dd/yyyy
    sm = vParts[0] - 1;
    sd = vParts[1];
    sy = vParts[2];
    m=sm;
    d=sd;
    y=sy;
  }
  
//  l = dest.offsetLeft; // + dest.offsetWidth;
//  t = dest.offsetTop - 125;   // Calendar is displayed 125 pixels above the destination element
//  if(t<0) { t=0; }      // or (somewhat) over top of it. ;)

  /* Calendar is displayed 125 pixels above the destination element
  or (somewhat) over top of it. ;)*/
  l = dest.offsetLeft + dest.offsetParent.offsetLeft;
  t = dest.offsetTop - 125;
  if(t < 0) t = 0; // >
  DrawCalendar();
}

/*
  function DestoryCalendar()
  
  Purpose: Destory any already drawn calendar so a new one can be drawn
*/
function DestroyCalendar() {
  var cal = document.getElementById("dpCalendar");
  if(cal != null) {
    cal.innerHTML = null;
    cal.style.display = "none";
  }
  return
}

function DrawCalendar() {
  DestroyCalendar();
  cal = document.getElementById("dpCalendar");
  cal.style.left = l + "px";
  cal.style.top = t + "px";
  
  var sCal = "<table><tr><td class=\"cellButton\"><a href=\"javascript: PrevMonth();\" title=\"Previous Month\">&lt;&lt;</a></td>"+
    "<td class=\"cellMonth\" width=\"80%\" colspan=\"5\">"+MonthNames[m]+" "+y+"</td>"+
    "<td class=\"cellButton\"><a href=\"javascript: NextMonth();\" title=\"Next Month\">&gt;&gt;</a></td></tr>"+
    "<tr><td>S</td><td>M</td><td>T</td><td>W</td><td>T</td><td>F</td><td>S</td></tr>";
  var wDay = 1;
  var wDate = new Date(y,m,wDay);
  if(isLeapYear(wDate)) {
    MonthLengths[1] = 29;
  } else {
    MonthLengths[1] = 28;
  }
  var dayclass = "";
  var isToday = false;
  for(var r=1; r<7; r++) {
    sCal = sCal + "<tr>";
    for(var c=0; c<7; c++) {
      var wDate = new Date(y,m,wDay);
      if(wDate.getDay() == c && wDay<=MonthLengths[m]) {
        if(wDate.getDate()==sd && wDate.getMonth()==sm && wDate.getFullYear()==sy) {
          dayclass = "cellSelected";
          isToday = true;  // only matters if the selected day IS today, otherwise ignored.
        } else if(wDate.getDate()==nd && wDate.getMonth()==nm && wDate.getFullYear()==ny && HighlightToday) {
          dayclass = "cellToday";
          isToday = true;
        } else {
          dayclass = "cellDay";
          isToday = false;
        }
        if(((now > wDate) && !DisablePast) || (now <= wDate) || isToday) { // >
          // user wants past dates selectable
          sCal = sCal + "<td class=\""+dayclass+"\"><a href=\"javascript: ReturnDay("+wDay+");\">"+wDay+"</a></td>";
        } else {
          // user wants past dates to be read only
          sCal = sCal + "<td class=\""+dayclass+"\">"+wDay+"</td>";
        }
        wDay++;
      } else {
        sCal = sCal + "<td class=\"unused\"></td>";
      }
    }
    sCal = sCal + "</tr>";
  }
  sCal = sCal + "<tr><td colspan=\"4\" class=\"unused\"></td><td colspan=\"3\" class=\"cellCancel\"><a href=\"javascript: DestroyCalendar();\">Cancel</a></td></tr></table>"
  cal.innerHTML = sCal; // works in FireFox, opera
  cal.style.display = "inline";
}

function PrevMonth() {
  m--;
  if(m==-1) {
    m = 11;
    y--;
  }
  DrawCalendar();
}

function NextMonth() {
  m++;
  if(m==12) {
    m = 0;
    y++;
  }
  DrawCalendar();
}

function ReturnDay(day) {
  cDest = document.getElementById(dest);
//  dest.value = (m+1)+"/"+day+"/"+y;
  dest.value = ("0" + (m+1)).slice(-2)+"/"+("0" + day).slice(-2)+"/"+y;
  DestroyCalendar();
}

function EnsureCalendarExists() {
  if(document.getElementById("dpCalendar") == null) {
    var eCalendar = document.createElement("div");
    eCalendar.setAttribute("id", "dpCalendar");
    document.body.appendChild(eCalendar);
  }
}

function isLeapYear(dTest) {
  var y = dTest.getYear();
  var bReturn = false;
  
  if(y % 4 == 0) {
    if(y % 100 != 0) {
      bReturn = true;
    } else {
      if (y % 400 == 0) {
        bReturn = true;
      }
    }
  }
  
  return bReturn;
}  
function ForceNumericInput(field,DotIncl) {
	if (DotIncl == true) {var regExpr = /^[0-9]*([\.]?)[0-9]*$/;} else var regExpr = /^[0-9]*$/;
	if (!regExpr.test(field.value)) {field.value = field.value.substr(0,field.value.length-1);}
}
function validate() {
	if(document.getElementById('set_date').value=="") {
		document.getElementById("ErrorMessage").innerHTML = "Please set your appointment date";
		return false;			
	}
	if(document.getElementById('mailto').value!="") {
		var re = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
		if (!re.test(document.getElementById('mailto').value)){
			document.getElementById("ErrorMessage").innerHTML = "Please provide valid mailto email address!";
			return false;			
		}
	}
	if(document.getElementById('cc').value!="") {
		var re = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
		if (!re.test(document.getElementById('cc').value)){
			document.getElementById("ErrorMessage").innerHTML = "Please provide valid CC email address!";
			return false;			
		}
	}
}	
//document.getElementById('set_date').value = ("0" + (mydate.getMonth() + 1)).slice(-2)+"/"+("0" + mydate.getDate()).slice(-2)+"/"+mydate.getFullYear();
</script>
  <style type="text/css">
/**************************************************************************************
  htmlDatePicker CSS file
  
  Feel Free to change the fonts, sizes, borders, and colours of any of these elements
***************************************************************************************/
/* The containing DIV element for the Calendar */
#dpCalendar {
  display: none;          /* Important, do not change */
  position: absolute;        /* Important, do not change */
  background-color: #eeeeee;
  color: black;
  font-size: xx-small;
  font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
  width: 150px;
}
/* The table of the Calendar */
#dpCalendar table {
  border: 1px solid black;
  background-color: #eeeeee;
  color: black;
  font-size: xx-small;
  font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
  width: 100%;
}
/* The Next/Previous buttons */
#dpCalendar .cellButton {
  background-color: #ddddff;
  color: black;
}
/* The Month/Year title cell */
#dpCalendar .cellMonth {
  background-color: #ddddff;
  color: black;
  text-align: center;
}
/* Any regular day of the month cell */
#dpCalendar .cellDay {
  background-color: #ddddff;
  color: black;
  text-align: center;
}
/* The day of the month cell that is selected */
#dpCalendar .cellSelected {
  border: 1px solid red;
  background-color: #ffdddd;
  color: black;
  text-align: center;
}
/* The day of the month cell that is Today */
#dpCalendar .cellToday {
  background-color: #ddffdd;
  color: black;
  text-align: center;
}
/* Any cell in a month that is unused (ie: Not a Day in that month) */
#dpCalendar .unused {
  background-color: transparent;
  color: black;
}
/* The cancel button */
#dpCalendar .cellCancel {
  background-color: #cccccc;
  color: black;
  border: 1px solid black;
  text-align: center;
}
/* The clickable text inside the calendar */
#dpCalendar a {
  text-decoration: none;
  background-color: transparent;
  color: blue;
}  
input.wide {display:block; width: 98%} 
</style>
<link type="text/css" rel="stylesheet" href="/css/MyResource.css" />
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Reminder</title>
</head>
<body style="font-size:25px;">
<form name="MyForm" enctype="application/x-www-form-urlencoded" method="post" onsubmit="return validate();">
<table width="100%" border="1">
	<tr>
	<td rowspan="2" width="112">
		<input type="image" src="/images/save.jpg" name="Save" value="Save" width="112">
	</td>
    <td style="text-align:right;white-space: nowrap;">
	<div style="display:inline-block;vertical-align:top;">
	Date: mm/dd/yyyy<br/>
	<input type="text" name="set_date" id="set_date" maxlength="10" style="border-style:ridge;border-color:#5050FF;border-width: 3px;">
	</div>
	<div style="display:inline-block;">
	<input type="image" src="/images/calendar.jpg" name="calender" id="calender" width="50px" value="calender" onClick="GetDate(document.getElementById('set_date'));return false;">
	</div>
	</td>
	<td style="vertical-align:middle;text-align:center;white-space: nowrap;">
	Time:<br/> 
	<select name="hour" id="hour" style="border-color:#5050FF;border-width: 3px;" onChange="SetDisp(6);">
	<?php
		for ($i = 1; $i<=12; $i++) {
			echo "<option value='$i'>$i</option>";
		}
	?>
	</select>:
	<select name="minute" id="minute" style="border-color:#5050FF;border-width: 3px;" onChange="SetDisp(6);">
	<?php
		for ($i = 0; $i<=60; $i++) {
			if($i<10) $minute="0".$i; else $minute=$i;
			echo "<option value='$i'>$minute</option>";
		}
	?>
	</select>:
	<select name="AM" id="AM" style="border-color:#5050FF;border-width: 3px;" onChange="SetDisp(6);">
	  <option value='0'>AM</option>
	  <option value='1'>PM</option>
	</select>	
	</td>
	<td style="vertical-align:top;text-align:center;" width="100%">
	Description: <input type="text" name="description"  class="wide" maxlength="200" style="border-color:#5050FF;border-style:ridge;border-width: 3px;"  value="<?php if (isset($description)){ echo htmlspecialchars($description); } else ''; ?>">
	</td>
	<td style="vertical-align:top;text-align:center;">
	Location: <input type="text" name="location"  class="wide" maxlength="200" style="border-color:#5050FF;border-style:ridge;border-width: 3px;"  value="<?php if (isset($location)){ echo htmlspecialchars($location); } else ''; ?>">
	</td>
  </tr>
	<tr>
    <td style="text-align:right;white-space: nowrap;vertical-align:top;">
	<div style="display:inline-block;vertical-align:top;">
	Rmind Date: mm/dd/yyyy<br/>
	<input type="text" name="remind_date" id="remind_date" maxlength="10" style="border-style:ridge;border-color:#5050FF;border-width: 3px;">
	</div>
	<div style="display:inline-block;">
	<input type="image" src="/images/calendar.jpg" name="calender" id="calender" width="50px" value="calender" onClick="GetDate(document.getElementById('remind_date'));return false;">
	</div>	
	</td>
	<td style="vertical-align:top;text-align:center;">
	Repetition: 
	<select name="repetition" id="repetition" style="border-color:#5050FF;border-width: 3px;" onChange="SetDisp(6);">
	<?php
		foreach ($optionfq as $key=>$name) {
			echo "<option value='$key'>$name</option>";
		}
	?>
	</select>
	</td>
	<td style="vertical-align:top;text-align:center;">
	To(e-mail): <input type="text" name="mailto"  id="mailto" class="wide" maxlength="200" style="border-color:#5050FF;border-style:ridge;border-width: 3px;"  value="<?php if (isset($to)){ echo htmlspecialchars($to); } else ''; ?>">
	</td>
	<td style="vertical-align:top;text-align:center;">
	CC(e-mail): <input type="text" name="cc" id="cc"  class="wide" maxlength="200" style="border-color:#5050FF;border-style:ridge;border-width: 3px;"  value="<?php if (isset($cc)){ echo htmlspecialchars($cc); } else ''; ?>">
	</td>
  </tr>

</table>    
</form>
<?php
	while($nt=mysql_fetch_array($resultRm)){
		echo "<font color=red style='text-align:left;'>You have an appointment on $nt[set_date] at $nt[hour]:$nt[minute]$APM[$nt['AM']] ($nt[description]) Location: $nt[location]</font></br>";
	}
?>
	<p text-align="right" >
	<font color=red><b id="ErrorMessage"><?php if (isset($ErrorMessage)){ echo '**'.htmlspecialchars($ErrorMessage).'**   '; } else ''; ?></b></font>
	</p>

<table width="100%" style="border-style: solid;border-color:#5050FF;border-width: 3px;">
	<tr>
    <th style='border-style: solid;border-width: 3px;text-align:center;white-space: nowrap;'>
	<p>Appointment Date</p>
	</th>
	<th style='border-style: solid;border-width: 3px;text-align:center;'>
	<p>Time</p>
	</th>
	<th style='border-style: solid;border-width: 3px;text-align:center;'>
	<p>Description</p>
	</th>
	<th style='border-style: solid;border-width: 3px;text-align:center;'>
	<p>Location</p>
	</th>
	<th style='border-style: solid;border-width: 3px;text-align:center;'>
	<p>Repetition</p>
	</th>
	</tr>
<?php	
while($nt=mysql_fetch_array($result)){
if($nt[AM]==0) $APM="AM"; else $APM="PM";
$repetition=$optionfq[$nt['repetition']];
$date=date('l jS \of F Y h:i:s A',strtotime ($nt['set_date']));
echo "
<tr>
    <td style='border-style: solid;border-width: 3px;text-align:right;'>
	$date
	</td><td style='border-style: solid;border-width: 3px;text-align:right;'>
	$nt[hour]:$nt[minute]$APM[$nt['AM']]
	</td>
	<td style='border-style: solid;border-width: 3px;text-align:left;' width='100%'>
	$nt[description]
	</td>
	<td style='border-style: solid;border-width: 3px;text-align:left;'>
	$nt[location]
	</td>
	<td style='border-style: solid;border-width: 3px;text-align:left;white-space: nowrap;'>
	$repetition
	</td>
</tr>
";
}
?>
</table>
</body>
</html>