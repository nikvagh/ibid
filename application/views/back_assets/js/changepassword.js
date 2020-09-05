function confirmSubmit(frm)
{
	with(frm)
	{
		if(frm.o_password.value == '')
		{
			alert("Please enter old password.");
			frm.o_password.focus();
			return false;
		}
		if(frm.n_password.value == '')
		{
			alert("Please enter new password.");
			frm.n_password.focus();
			return false;
		}
		if(frm.c_password.value == '')
		{
			alert("Please enter confirmed password.");
			frm.c_password.focus();
			return false;
		}
		if(frm.n_password.value != frm.c_password.value)
		{
			alert("New password and confirmed password do not match.");
			frm.c_password.focus();
			return false;
		}
	}
}
