//====================================================================================================
//	Function Name	:	Form_Submit()
//----------------------------------------------------------------------------------------------------
function Form_Submit(frm)
{
	with(frm)
    {
    	if(!IsEmpty(username, 'Please enter Username.'))
        {
			return false;
        }
		
    	if(!IsEmpty(password, 'Please enter Password.'))
        {
			return false;
        }
		
        return true;
    }
}

//====================================================================================================
//	Function Name	:	ChangePassword_Form()
//----------------------------------------------------------------------------------------------------
function ChangePassword_Form(frm)
{
	with(frm)
    {
		if(!IsEmpty(user_old_password, 'Please enter Old Password.'))
        {
			return false;
        }
		
		if(!IsEmpty(user_new_password, 'Please enter New Password.'))
        {
			return false;
        }
		else if(!IsLen(user_new_password, 6, 15, 'Password must be at least 6 characters long, \nand should not exceed more than 15 characters.'))
		{
			return false;
		}
		
		if(!IsEmpty(retype_password, 'Please retype New Password'))
        {
			return false;
        }
		else if(user_new_password.value != retype_password.value)
        {
			alert('Password confirmation does not match');
			return false;
        }
		
        return true;
    }
}