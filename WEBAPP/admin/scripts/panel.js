function open_nav() 
{
	document.getElementById("mysidenav").style.width = "250px";
}

function close_nav() 
{
	document.getElementById("mysidenav").style.width = "0";
}

function openAddForm() 
{
	closeAddTrack("myTrack");
	closeAddDepartment("myDepartment");
	closeAddClass("myClass");
	closeAddSubject("mySubject");
	closeAddExam("myExam");

	document.getElementById("myForm").style.display = "block";

}

function closeAddForm() 
{
	document.getElementById("myForm").style.display = "none";
} 
function openAddTrack() 
{
	closeAddForm("myForm");
	closeAddDepartment("myDepartment");
	closeAddClass("myClass");
	closeAddSubject("mySubject");
	closeAddExam("myExam");
	document.getElementById("myTrack").style.display = "block";
}

function closeAddTrack() 
{
	document.getElementById("myTrack").style.display = "none";
} 
function openAddDepartment() 
{
	closeAddForm("myForm");
	closeAddTrack("myTrack");
	closeAddClass("myClass");
	closeAddSubject("mySubject");
	closeAddExam("myExam");
	document.getElementById("myDepartment").style.display = "block";
}

function closeAddDepartment() 
{
	document.getElementById("myDepartment").style.display = "none";
} 

function openAddClass() 
{
	closeAddForm("myForm");
	closeAddTrack("myTrack");
	closeAddDepartment("myDepartment");
	closeAddSubject("mySubject");
	closeAddExam("myExam");
	document.getElementById("myClass").style.display = "block";
}

function closeAddClass() 
{
	document.getElementById("myClass").style.display = "none";
} 

function openAddSubject() 
{
	closeAddForm("myForm");
	closeAddTrack("myTrack");
	closeAddDepartment("myDepartment");
	closeAddClass("myClass");
	closeAddExam("myExam");
	document.getElementById("mySubject").style.display = "block";
}

function closeAddSubject() 
{
	document.getElementById("mySubject").style.display = "none";
} 

function openAddExam() 
{
	closeAddForm("myForm");
	closeAddTrack("myTrack");
	closeAddDepartment("myDepartment");
	closeAddClass("myClass");
	document.getElementById("myExam").style.display = "block";
}



function closeAddExam() 
{
	document.getElementById("myExam").style.display = "none";
} 

