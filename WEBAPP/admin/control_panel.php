<?php

session_start();
print("<body bgcolor='#000000'></body>");
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["superuser"] == true &&  $_SESSION["loggedin"]== true)
{
	print("
		<!DOCTYPE html>
		<html>
		<head>
		<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
		<link rel='stylesheet' type='text/css' href='stylesheets/panel.css' />
		<script src='scripts/panel.js'></script>

		<script language='javascript'>
		function openDialog()
		{
			document.getElementById('appear1').style.display='block';
		}
		function openDialog1()
		{
			document.getElementById('appear').style.display='block';
		}
		function closeDialog()
		{
			document.getElementById('appear1').style.display='none';
		}
		function closeDialog1()
		{
			document.getElementById('appear').style.display='none';
		}
		function logout()
		{
			var status = confirm('Do you want to logout?');
			if(status == true)
			{
				window.location = '../login/logout.php'
			}
		}
		function prevent_load_parent()
		{
			var doc = document.getElementById('myframe').contentWindow.location.pathname;
			var file = doc.split('/').slice(-1);

			if(file == 'control_panel.php')
				document.getElementById('myframe').contentWindow.location = 'dummy.html';

		}
		</script>
		
		</head>

		<body>

		<!--deletion of tutors and students by username-->
		<div id='appear' class='container'>
		<form action='scripts/del/del_name.php' class='form-box' method='POST'>
		<a onClick='closeDialog1()' class='cancel'>&times</a>
		<h3 align='center'>Delete Student by Username</h3>
		<input  name='s_username' placeholder='Enter username' maxlength='12' pattern='[a-zA-Z0-9.]{12}' class='finput' type='text' required></input>
		<input  value='remove' type='submit'></input>
		</form>
		</div>

		<div id='appear1' class='container'>
		<form action='scripts/del/del_name.php' class='form-box' method='POST'>
		<a onClick='closeDialog()' class='cancel'>&times</a>
		<h3 align='center'>Delete Tutor by Username</h3>
		<input  name='t_username' placeholder='Enter username' maxlength='14' pattern='[a-zA-Z0-9.]{14}' class='finput' type='text' required></input>
		<input  value='remove' type='submit'></input>
		</form>
		</div>


		<!--Add new form field input-->
		<div class='form-popup' id='myForm'>
		<form action='scripts/config/config.php' class='form-container' method='post'>
		<a href='javascript:void(0)' class='closebtn1' onclick='closeAddForm()'>&times;</a>
		<h1>Add Form</h1>

		<input  pattern='[a-zA-Z0-9\-_() ]{1,}' name='FORM' type='text' placeholder='eg (form1)' required >
		<button type='submit' name='submit' class='btn'>Add</button>
		</form>
		</div>


		<!--Add new track input-->
		<div class='form-popup' id='myTrack'>
		<form action='scripts/config/config.php' class='form-container' method='post'>
		<a href='javascript:void(0)' class='closebtn1' onclick='closeAddTrack()'>&times;</a>
		<h1>Add Track</h1>
		<input  pattern='[a-zA-Z0-9\-_() ]{1,}' name='TRACK' type='text' placeholder='eg (gold)' required>
		<button type='submit' name='submit' class='btn'>Add</button>
		</form>
		</div>

		<!--Add new department input-->
		<div class='form-popup' id='myDepartment'>
		<form action='scripts/config/config.php' class='form-container' method='post'>
		<a href='javascript:void(0)' class='closebtn1' onclick='closeAddDepartment()'>&times;</a>
		<h1>Add Department</h1>

		<input pattern='[a-zA-Z0-9\-_() ]{1,}' name='DEPARTMENT' type='text' placeholder='eg (science)' required>

		<button type='submit' name='submit' class='btn'>Add</button>
		</form>
		</div>

		<!--Add new class input-->
		<div class='form-popup' id='myClass'>
		<form action='scripts/config/config.php' class='form-container' method='post'>
		<a href='javascript:void(0)' class='closebtn1' onclick='closeAddClass()'>&times;</a>
		<h1>Add Class</h1>

		<input  pattern='[a-zA-Z0-9\-_() ]{1,}' name='CLASS' type='text' placeholder='eg (1S2)' required>
		<button type='submit' name='submit' class='btn'>Add</button>
		</form>
		</div>

		<!--Add new subject input-->
		<div class='form-popup' id='mySubject'>
		<form action='scripts/config/config.php' class='form-container' method='post'>
		<a href='javascript:void(0)' class='closebtn1' onclick='closeAddSubject()'>&times;</a>
		<h1>Add Subject</h1>

		<input  pattern='[a-zA-Z0-9\-_() ]{1,}' name='SUBJECT' type='text' placeholder='eg (ICT)' required>
		<button type='submit' name='submit' class='btn'>Add</button>
		</form>
		</div>


		<!--Add new exam-->
		<div class='form-popup' id='myExam'>
		<form action='scripts/config/addexam.php' class='form-container' method='post'>
		<a href='javascript:void(0)' class='closebtn1' onclick='closeAddExam()'>&times;</a>
		<h1>Add Latest Exam</h1>
		<input  pattern='[a-zA-Z0-9\-_() ]{1,}' name='EXAM' type='text' placeholder='eg (2019 form 1 term 1)' required >
		<button type='submit' name='submit' class='btn'>Add</button>
		</form>
		</div>



		<div id='mysidenav' class='sidenav'>
		<a href='javascript:void(0)' class='closebtn' onclick='close_nav()'>&times;</a>
		<a onClick='openAddForm()' href='#'>Add Form</a>
		<a onClick='openAddTrack()' href='#'>Add Track</a>
		<a onClick='openAddDepartment()' href='#'>Add Department</a>
		<a onClick='openAddClass()' href='#'>Add Class</a>
		<a onClick='openAddSubject()' href='#'>Add Subject</a>
		<a onClick='openAddExam()' href='#'>Add Latest Exam</a>
		<a href='scripts/config/vconfig.php' target='frame'>View Configurations</a>
		<a href='scripts/config/dconfig.php' target='frame'>Remove Fields</a>
		<br /><br />
		<a href='../login/change/pass.php' target='frame'><i class='fa fa-key'></i> Change Password</a>
		<a onclick='logout()'><i class='fa fa-sign-out'></i> logout</a>
		</div>

		<div class='header' >

		<i><b class='finf'>CONFIGURATION PANEL</b><br /><i>
		<span class='sshow' style='font-size:30px;cursor:pointer' onclick='open_nav()'>&#9776;</span>

		<!--menu for deleting users-->

		<!--menu for deleting users-->
		<div class='dropdown'>
		<br />
		<button class='dropbtn'><i class='fa fa-trash-o'></i> DELETE <i class='fa fa-caret-down'></i></button>
		
		<div class='dropdown-content'>
		<a style='color:#88ffff;' href='#'>STUDENTS AND TUTORS</a>
		<a onClick='openDialog()'>Tutor by Username</a>
		<a onClick='openDialog1()'>Student by Username</a>
		<a href='scripts/del/form.php' target='frame'>by Form</a>
		<a href='scripts/del/track.php' target='frame'>by Track</a>
		<a href='scripts/del/department.php' target='frame'>by Department</a>
		<a href='scripts/del/class.php' target='frame'>by Class</a>
		<a href='scripts/del/subject.php' target='frame'>by Subject</a>
		<a href='scripts/del/exam.php' target='frame'>Students by Exam</a>

		</div>
		</div>



		<!--menu for updating users-->
		<div class='dropdown'>
		<br />
		<button class='dropbtn'><i class='fa fa-pencil-square-o'></i> UPDATE <i class='fa fa-caret-down'></i></button>
		
		<div class='dropdown-content'>
		<a href='scripts/update/tutor.php'   target='frame' target='frame'>Tutor</a>
		<a href='scripts/update/student.php' target='frame'>Students</a>
		<a href='scripts/status/tstatus.php' target='frame'>Tutor de/activation</a>
		<a href='scripts/status/sstatus.php' target='frame'>Student de/activation</a>
		<a></a>
		<a href='scripts/update/name.php' target='frame'>Change Name of User</a>
		</div>
		</div>

		<!--menu for  credentials-->
		<div class='dropdown'>
		<br />
		<button class='dropbtn'><i class='fa fa-key'></i> CREDS <i class='fa fa-caret-down'></i></button>    
		<div class='dropdown-content'>
		<a href='scripts/cred/tutor.php'   target='frame' target='frame'>View Tutors Creds</a>
		<a href='scripts/cred/stud.php' target='frame'>View Students Creds</a>
		<a href='scripts/cred/lock.php?cmd=tlock' target='frame'>Block all Tutors</a>
		<a href='scripts/cred/lock.php?cmd=tunlock' target='frame'>Unblock all Tutors</a>
		<a href='scripts/cred/lock.php?cmd=slock' target='frame'>Block all Students</a>
		<a href='scripts/cred/lock.php?cmd=sunlock' target='frame'>Unblock all Students</a>

		</div>
		</div>


		<div class='navbar'>
		<a href='scripts/add/add_student.php' target='frame'> <i class='fa fa-plus'></i> NEW STUDENTS</a>
		<a href='scripts/add/add_tutor.php' target='frame'><i class='fa fa-plus-circle'></i> NEW TUTOR</a>
		<a href='scripts/view/vstud.php' target='frame'><i class='fa fa-list'></i> VIEW STUDENTS</a>
		<a href='scripts/view/vtut.php' target='frame'><i class='fa fa-th-list'></i> SHOW TUTORS</a>
		<a href='scripts/view/stats.php' target='frame' ><i class='fa fa-area-chart'></i> DB-STATS</a>
		</div> 
		</div>
        
		<iframe src='dummy.html' class='fr' name='frame' id='myframe' onload='prevent_load_parent()'></iframe>
		</body>
		</html>");
}
else
{
	header("location: ../login/index.php");
}
?>

