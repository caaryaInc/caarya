<?PHP
require_once("class.phpmailer.php");
require_once("formvalidator.php");
class FGMembersite
{
	var $admin_email;
	var $from_address;
	var $address;

	// var $username;
	var $pwd;
	var $database;
	var $tablename;
	var $connection;
	var $rand_key;

	var $error_message;

	//-----Initialization -------
	function FGMembersite()
	{
		$this->sitename = 'YourWebsiteName.com';
		$this->rand_key = '0iQx5oBk66oVZep';
	}

	function InitDB($host,$uname,$pwd,$database,$tablename)
	{
		$this->db_host  = $host;
		$this->username = $uname;
		$this->pwd  = $pwd;
		$this->database  = $database;
		$this->tablename = $tablename;

	}
	function SetAdminEmail($email)
	{
		$this->admin_email = $email;
	}

	function SetWebsiteName($sitename)
	{
		$this->sitename = $sitename;
	}

	function SetRandomKey($key)
	{
		$this->rand_key = $key;
	}

	//-------Main Operations ----------------------
	function RegisterUser()
	{

		if(!isset($_POST['registersubmitted']))
		{
			return false;
		}

		$formvars = array();

		if(!$this->ValidateRegistrationSubmission())
		{
			return false;
		}

		$this->CollectRegistrationSubmission($formvars);

		if(!$this->SaveToDatabase($formvars))
		{
			return false;
		} 

		if(!$this->SendUserConfirmationEmail($formvars))
		{
			return false;
		} 

		$this->SendAdminIntimationEmail($formvars); 

		return true;
	}

	function ResendUserConfirmation($email)
	{
		$formvars = array();
		$formvars['email'] = $this->Sanitize($email);
		if(!$this->DBLogin())	{
			echo "Database login failed";
		}
		$query = "Select confirmcode from user where email= '$email' ";

		$result = mysql_query($query);
		if (!$result) {
			die("Invalid query: " . mysql_error());
		} 

		$row = mysql_fetch_assoc($result); 
		$confirmcode = $row['confirmcode'];
		$formvars['confirmcode']= $confirmcode;
		if(!$this->SendUserConfirmationEmail($formvars))
		{
			return false;
		} 
		return true;
	}

	function ConfirmUser()
	{
		if(empty($_GET['code'])||strlen($_GET['code'])<=10)
		{
			$this->HandleError("Please provide the confirm code");
			return false;
		}
		$user_rec = array();
		if(!$this->UpdateDBRecForConfirmation($user_rec))
		{
			return false;
		}

		//$this->SendUserWelcomeEmail($user_rec);

		$this->SendAdminIntimationOnRegComplete($user_rec);

		return true;
	}    

	function Login()
	{
		if(empty($_POST['emaillogin']))
		{
			$this->HandleError("Email is empty!");
			return false;
		}

		if(empty($_POST['password']))
		{
			$this->HandleError("Password is empty!");
			return false;
		}

		$email = trim($_POST['emaillogin']);
		$password = trim($_POST['password']);

		if(!isset($_SESSION)){ session_start(); }
		if(!$this->CheckLoginInDB($email,$password))
		{
			return false;
		}

		$_SESSION[$this->GetLoginSessionVar()] = $email;

		return true;
	}

	function CheckLogin()
	{
		if(!isset($_SESSION)){ session_start();}

		$sessionvar = $this->GetLoginSessionVar();

		if(empty($_SESSION[$sessionvar]))
		{
			return false;
		}

		return true;
	}

	function UserFullName()
	{
		return isset($_SESSION['name_of_user'])?$_SESSION['name_of_user']:'';

	}

	function UserEmail()
	{
		return isset($_SESSION['email_of_user'])?$_SESSION['email_of_user']:'';
	}

	function LogOut()
	{
		session_start();

		$sessionvar = $this->GetLoginSessionVar();

		$_SESSION[$sessionvar]=NULL;

		unset($_SESSION[$sessionvar]);
	}

	function EmailResetPasswordLink($code)
	{
		if(empty($_POST['frgtemail']))
		{
			$this->HandleError("Email is empty!");
			return false;
		}
		$user_rec = array();
		if(false === $this->GetUserFromEmail($_POST['frgtemail'], $user_rec))
		{
			return false;
		}
		if(false === $this->SendResetPasswordLink($user_rec,$code))
		{
			return false;
		}
		return true;
	}

	function ResetPassword()
	{
		if(empty($_GET['email']))
		{
			$this->HandleError("Email is empty!");
			return false;
		}
		if(empty($_GET['code']))
		{
			$this->HandleError("reset code is empty!");
			return false;
		}
		$email = trim($_GET['email']);
		$code = trim($_GET['code']);

		if($this->GetResetPasswordCode($email) != $code)
		{
			$this->HandleError("Bad reset code!");
			return false;
		}

		$user_rec = array();
		if(!$this->GetUserFromEmail($email,$user_rec))
		{
			return false;
		}

		$new_password = $this->ResetUserPasswordInDB($user_rec);
		if(false === $new_password || empty($new_password))
		{
			$this->HandleError("Error updating new password");
			return false;
		}

		if(false == $this->SendNewPassword($user_rec,$new_password))
		{
			$this->HandleError("Error sending new password");
			return false;
		}
		return true;
	}

	function ChangePassword()
	{
		if(!$this->CheckLogin())
		{
			$this->HandleError("Not logged in!");
			return false;
		}

		if(empty($_POST['oldpwd']))
		{
			$this->HandleError("Old password is empty!");
			return false;
		}
		if(empty($_POST['newpwd']))
		{
			$this->HandleError("New password is empty!");
			return false;
		}

		$user_rec = array();
		if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
		{
			return false;
		}

		$pwd = trim($_POST['oldpwd']);

		if($user_rec['password'] != md5($pwd))
		{
			$this->HandleError("The old password does not match!");
			return false;
		}
		$newpwd = trim($_POST['newpwd']);

		if(!$this->ChangePasswordInDB($user_rec, $newpwd))
		{
			return false;
		}
		return true;
	}

	//-------Public Helper functions -------------
	function GetSelfScript()
	{
		return htmlentities($_SERVER['PHP_SELF']);
	}    

	function SafeDisplay($value_name)
	{
		if(empty($_POST[$value_name]))
		{
			return'Enter your'.' '. $value_name.' '.'here';
		}
		return htmlentities($_POST[$value_name]);
	}

	function RedirectToURL($url)
	{
		header("Location: $url");
		exit;
	}

	function GetSpamTrapInputName()
	{
		return 'sp'.md5('KHGdnbvsgst'.$this->rand_key);
	}

	function GetErrorMessage()
	{
		if(empty($this->error_message))
		{
			return '';
		}
		$errormsg = nl2br(htmlentities($this->error_message));
		return $errormsg;
	}    
	//-------Private Helper functions-----------

	function HandleError($err)
	{
		$this->error_message .= $err."\r\n";
	}

	function HandleDBError($err)
	{
		$this->HandleError($err."\r\n mysqlerror:".mysql_error());
	}

	function GetFromAddress()
	{
		if(!empty($this->from_address))
		{
			return $this->from_address;
		}

		$host = $_SERVER['SERVER_NAME'];

		$from ="srivatsa.nikhil@gmail.com";
		return $from;
	}  

	function GetLoginSessionVar()
	{
		$retvar = md5($this->rand_key);
		$retvar = 'usr_'.substr($retvar,0,10);
		return $retvar;
	}

	function CheckLoginInDB($email,$password)
	{
		if(!$this->DBLogin())
		{
			$this->HandleError("Database login failed!");
			return false;
		}          
		$email = $this->SanitizeForSQL($email);
		$pwdmd5 = md5($password);
		//  $qry = "Select name, email from $this->tablename where username='$username' and password='$pwdmd5' and confirmcode='y'";
		$checkunamequery= "select * from $this->tablename where email='$email' ";
		$result = mysql_query($checkunamequery,$this->connection);
		if(!$result || mysql_num_rows($result) <= 0)
		{
			$this->HandleError("Email not found in database. Please register first");
			return false;
		}
		else
		{
			$qry = "Select * from $this->tablename where email='$email' and password='$pwdmd5'";

			$result = mysql_query($qry,$this->connection);

			if(!$result || mysql_num_rows($result) <= 0)
			{
				$this->HandleError("Error logging in. The Email or password does not match");
				return false;
			}

			$row = mysql_fetch_assoc($result);

			//	$_SESSION['name_of_user']  = $username;
			$_SESSION['email_of_user'] = $email;

			return true;
		}
	}


	function UpdateDBRecForConfirmation(&$user_rec)
	{
		if(!$this->DBLogin())
		{
			$this->HandleError("Database login failed!");
			return false;
		}   
		$confirmcode = $this->SanitizeForSQL($_GET['code']);

		$result = mysql_query("Select name, email from $this->tablename where confirmcode='$confirmcode'",$this->connection);   
		if(!$result || mysql_num_rows($result) <= 0)
		{
			$this->HandleError("Wrong confirm code.");
			return false;
		}
		$row = mysql_fetch_assoc($result);
		$user_rec['name'] = $row['name'];
		$user_rec['email']= $row['email'];

		$qry = "Update $this->tablename Set confirmcode='y' Where  confirmcode='$confirmcode'";

		// $qry = "Update $this->tablename Set confirmcode='y'";

		if(!mysql_query( $qry ,$this->connection))
		{
			$this->HandleDBError("Error inserting data to the table\nquery:$qry");
			return false;
		}      
		return true;
	}

	function update($fld,$value,$email)
	{
		if(!$this->DBLogin())
		{
			$this->HandleError("Database login failed!");
			return false;
		}   

		$qry = "Update user Set $fld='$value' where  email='$email'";


		if(!mysql_query( $qry ,$this->connection))
		{
			$this->HandleDBError("Error inserting data to the table\nquery:$qry");
			return false;
		}      
		return true;


	}


	function ResetUserPasswordInDB($user_rec)
	{
		$new_password = substr(md5(uniqid()),0,10);

		if(false == $this->ChangePasswordInDB($user_rec,$new_password))
		{
			return false;
		}
		return $new_password;
	}

	function ChangePasswordInDB($user_rec, $newpwd)
	{
		$newpwd = $this->SanitizeForSQL($newpwd);

		$qry = "Update $this->tablename Set password='".md5($newpwd)."' Where  userid=".$user_rec['userid']."";

		if(!mysql_query( $qry ,$this->connection))
		{
			$this->HandleDBError("Error updating the password \nquery:$qry");
			return false;
		}     
		return true;
	}

	function GetUserFromEmail($email,&$user_rec)
	{
		if(!$this->DBLogin())
		{
			$this->HandleError("Database login failed!");
			return false;
		}   
		$email = $this->SanitizeForSQL($email);

		$result = mysql_query("Select * from $this->tablename where email='$email'",$this->connection);  

		if(!$result || mysql_num_rows($result) <= 0)
		{
			$this->HandleError("There is no user with email: $email");
			return false;
		}
		$user_rec = mysql_fetch_assoc($result);


		return true;
	}

	function SendUserWelcomeEmail(&$user_rec)
	{
		$mailer = new PHPMailer();

		$mailer->CharSet = 'utf-8';

		$mailer->IsSMTP(); // telling the class to use SMTP

		$mailer->Host = "mail.localhost.com"; // SMTP server
		// enables SMTP debug information (for testing) // 1 = errors and messages // 2 = messages only
		//$mailer->SMTPDebug = 2;

		$mailer->SMTPAuth = true; // enable SMTP authentication

		$mailer->SMTPSecure = "tls"; // sets the prefix to the servier

		$mailer->Host = "smtp.gmail.com"; // sets GMAIL as the SMTP server

		$mailer->Port = 587; // set the SMTP port for the GMAIL server

		$mailer->Username = "caaryainc@gmail.com"; // GMAIL username

		$mailer->Password = "topgear123!"; // GMAIL password 

		$mailer->IsHTML(true); // send as HTML

		$mailer->AddAddress($user_rec['email'],$user_rec['name']);

		$mailer->Subject = "Welcome to Caarya";

		$mailer->From = "caaryainc@gmail.com";     

		$mailer->FromName="Caarya Team";

		$mailer->Body = "<div class='container' style='background-color: #d8d8d8; padding: 20px; font-family: Arial'>".
			"<p style='font-size: 18px;font-family: Arial;'>Welcome! Thanks for registering with Caarya. </p><br/>".
			"<div style='height: 125px; padding: 40px; background-color: #fff; font-size: 13px;'>".
			"Now you can: ".
			"<ul style='height: 80px;'>".
			"<li style='margin: 5px;'>Post daily wage jobs/chores to be done</li>".
			"<li style='margin: 5px;'>Apply for jobs posted by others</li>".
			"<li style='margin: 5px;'>Complete a job to earn quick money</li></ul>".
			"<a href='www.caarya.com/welcomepage.php' style='font-size: 15px; text-decoration: none; color: #fff; margin-left: 40%; padding: 20px;background: #7fc542'>Try it Now!!!</a>".
			"</div>".
			"<br/>".
			"<br/>Regards,<br/>".
			"Caarya Team<br/>";


		if(!$mailer->Send())
		{
			$this->HandleError("Failed sending user welcome email.");
			return false;
		}
		return true;
	}

	function SendAdminIntimationOnRegComplete(&$user_rec)
	{
		if(empty($this->admin_email))
		{
			return false;
		}
		$mailer = new PHPMailer();

		$mailer->IsSMTP(); // telling the class to use SMTP

		$mailer->Host = "mail.localhost.com"; // SMTP server
		// enables SMTP debug information (for testing) // 1 = errors and messages // 2 = messages only
		//$mailer->SMTPDebug = 2;

		$mailer->SMTPAuth = true; // enable SMTP authentication

		$mailer->SMTPSecure = "tls"; // sets the prefix to the servier

		$mailer->Host = "smtp.gmail.com"; // sets GMAIL as the SMTP server

		$mailer->Port = 587; // set the SMTP port for the GMAIL server

		$mailer->Username = "dailychores105@gmail.com"; // GMAIL username

		$mailer->Password = "admin.dk"; // GMAIL password 

		$mailer->CharSet = 'utf-8';

		$mailer->AddAddress($this->admin_email);

		$mailer->Subject = "Registration Completed: ".$user_rec['name'];

		$mailer->From = "dailychores105@gmail.com";         

		$mailer->Body ="A new user registered at ".$this->sitename."\r\n".
			"Name: ".$user_rec['name']."\r\n".
			"Email address: ".$user_rec['email']."\r\n";

		if(!$mailer->Send())
		{
			return false;
		}
		return true;
	}

	function GetResetPasswordCode($email)
	{
		return substr(md5($email.$this->sitename.$this->rand_key),0,10);
	}

	function SendResetPasswordLink($user_rec,$code)
	{
		$email = $user_rec['email'];

		$mailer = new PHPMailer();

		$mailer->CharSet = 'utf-8';


		$mailer->IsSMTP(); // telling the class to use SMTP

		$mailer->Host = "mail.localhost.com"; // SMTP server
		// enables SMTP debug information (for testing) // 1 = errors and messages // 2 = messages only
		//$mailer->SMTPDebug = 2;

		$mailer->SMTPAuth = true; // enable SMTP authentication

		$mailer->SMTPSecure = "tls"; // sets the prefix to the servier

		$mailer->Host = "smtp.gmail.com"; // sets GMAIL as the SMTP server

		$mailer->Port = 587; // set the SMTP port for the GMAIL server

		$mailer->Username = "caaryainc@gmail.com"; // GMAIL username

		$mailer->Password = "topgear123!"; // GMAIL password 


		// optional, comment out and test
		$mailer->IsHTML(true); // send as HTML

		$mailer->AddAddress($email,$formvars['name']);

		$mailer->Subject = "Reset Password Request";

		$mailer->FromName="Caarya Team";

		$link = $this->GetAbsoluteURLFolder().
			'/resetpwd.php?email='.
			urlencode($email).'&code='.
			urlencode($this->GetResetPasswordCode($email));

		$mailer->Body ="Hello,<br/><br/>".
			"You recently requested to reset your caarya password.".
			"Your reset code is:<br/>".
				"<div style='padding: 10px; font-size: 20px;'>".$code."</div>".
			"<br/>".
			"<br/>Regards,<br/>".
			"Caarya Team<br/>";


		if(!$mailer->Send())
		{
			// echo "Reset password link not sent ";
			return false;
		}
		else
		{
			//echo "Reset Password link sent ";
			return true;
		}
	}

	function SendNewPassword($user_rec, $new_password)
	{
		$email = $user_rec['email'];

		$mailer = new PHPMailer();

		$mailer->CharSet = 'utf-8';

		$mailer->IsSMTP(); // telling the class to use SMTP

		$mailer->Host = "mail.localhost.com"; // SMTP server
		// enables SMTP debug information (for testing) // 1 = errors and messages // 2 = messages only
		//$mailer->SMTPDebug = 2;

		$mailer->SMTPAuth = true; // enable SMTP authentication

		$mailer->SMTPSecure = "tls"; // sets the prefix to the servier

		$mailer->Host = "smtp.gmail.com"; // sets GMAIL as the SMTP server

		$mailer->Port = 587; // set the SMTP port for the GMAIL server

		$mailer->Username = "dailychores105@gmail.com"; // GMAIL username

		$mailer->Password = "admin.dk"; // GMAIL password 


		$mailer->AddAddress($email,$user_rec['name']);


		$mailer->Subject = "Your new password for ".$this->sitename;

		$mailer->From = "dailychores105@gmail.com";

		$mailer->Body ="Hello ".$user_rec['name']."\r\n\r\n".
			"Your password is reset successfully. ".
			"Here is your updated login:\r\n".
			"username:".$user_rec['username']."\r\n".
			"password:$new_password\r\n".
			"\r\n".
			"Login here: ".$this->GetAbsoluteURLFolder()."/login.php\r\n".
			"\r\n".
			"Regards,\r\n".
			"Webmaster\r\n".
			$this->sitename;

		if(!$mailer->Send())
		{
			return false;
		}
		return true;
	}    

	function ValidateRegistrationSubmission()
	{
		//This is a hidden input field. Humans won't fill this field.
		if(!empty($_POST[$this->GetSpamTrapInputName()]) )
		{
			//The proper error is not given intentionally
			$this->HandleError("Automated submission prevention: case 2 failed");
			return false;
		}

		$validator = new FormValidator();
		// $validator->addValidation("name","req","Please fill in Name");
		$validator->addValidation("email","email","The input for Email should be a valid email value");
		$validator->addValidation("email","req","Please fill in Email");
		// $validator->addValidation("username","req","Please fill in UserName");
		$validator->addValidation("password","req","Please fill in Password");
		// $validator->addValidation("address","req","Please fill in Address");


		if(!$validator->ValidateForm())
		{
			$error='';
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err)
			{
				$error .= $inpname.':'.$inp_err."\n";
			}
			$this->HandleError($error);
			return false;
		}        
		return true;
	}

	function CollectRegistrationSubmission(&$formvars)
	{
		$formvars['name'] = $this->Sanitize($_POST['name']);
		$formvars['email'] = $this->Sanitize($_POST['email']);
		$formvars['username'] = $this->Sanitize($_POST['username']);
		$formvars['password'] = $this->Sanitize($_POST['password']);
		$formvars['address'] = $this->Sanitize($_POST['address']);
	}

	function SendUserConfirmationEmail(&$formvars)
	{	


		$mailer = new PHPMailer();

		$mailer->CharSet = 'utf-8';


		$mailer->IsSMTP(); // telling the class to use SMTP

		$mailer->Host = "mail.localhost.com"; // SMTP server
		// enables SMTP debug information (for testing) // 1 = errors and messages // 2 = messages only
		//$mailer->SMTPDebug = 2;

		$mailer->SMTPAuth = true; // enable SMTP authentication

		$mailer->SMTPSecure = "tls"; // sets the prefix to the servier

		$mailer->Host = "smtp.gmail.com"; // sets GMAIL as the SMTP server

		$mailer->Port = 587; // set the SMTP port for the GMAIL server

		$mailer->Username = "caaryainc@gmail.com"; // GMAIL username

		$mailer->Password = "topgear123!"; // GMAIL password 


		// optional, comment out and test
		$mailer->IsHTML(true); // send as HTML

		$mailer->AddAddress($formvars['email'],$formvars['name']);

		$mailer->Subject = "Your registration with ".$this->sitename;

		$mailer->FromName="Caarya Team";


		$confirmcode = $formvars['confirmcode']; 
     	//  $confirm_url = $this->GetAbsoluteURLFolder().'/confirmreg.php?code='.$confirmcode;

		$encrypted_mail = $this->encrypt_decrypt('encrypt', $formvars['email']);
		$confirm_url = "http://www.caarya.com/confirmreg.php?code=".$confirmcode."&email=".$encrypted_mail;

		$mailer->Body = 
			"<div class='container' style='background-color: #e8e8e8;font-family: Arial; padding: 1.5%;'>".
				"<p style='font-size: 18px;font-family: Arial; color: #000;'>Hello! Thanks for registering with Caarya. </p><br/>".
				"<div style='height: 200px; padding: 40px;  padding-left:3%;background-color: #fff; font-size: 13px;color: #000;'>".
					"<div style='height: 150px;'>".
						"Now you can: ".
						"<ul style='height: 80px;'>".
							"<li style='margin: 5px;'>Post daily wage jobs/chores to be done</li>".
							"<li style='margin: 5px;'>Apply for jobs posted by others</li>".
							"<li style='margin: 5px;'>Complete a job to earn quick money</li>".
						"</ul>".
					"Please click the link below to confirm your registration.</div>".
					"<div style='margin-top: 20px; width: 300px;margin-left: auto; margin-right: auto; padding: 8px 5px;background: #7fc542; text-align: center;'>".
						"<a href='".$confirm_url."' style='font-size: 15px; text-decoration: none; color: #fff'>Verify email and get started with Caarya</a>".
					"</div>".
				"</div>".
			"</div>".
			"<br/>".
			"<br/>Regards,<br/>".
			"Caarya Team<br/>";

		if(!$mailer->Send())
		{
			$this->HandleError("Failed sending registration confirmation Email.");
			return false;
		} 
		else
		{
			return true; 
		} 


	} 
	function GetAbsoluteURLFolder()
	{
		$scriptFolder = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) ? 'https://' : 'http://';
		$scriptFolder .= $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);
		return $scriptFolder;
	}



	function SendAdminIntimationEmail(&$formvars)
	{
		if(empty($this->admin_email))
		{
			return false;
		}
		$mailer = new PHPMailer();

		$mailer->CharSet = 'utf-8';

		$mailer->IsSMTP(); // telling the class to use SMTP

		$mailer->Host = "mail.localhost.com"; // SMTP server
		// enables SMTP debug information (for testing) // 1 = errors and messages // 2 = messages only
		//$mailer->SMTPDebug = 2;

		$mailer->SMTPAuth = true; // enable SMTP authentication

		$mailer->SMTPSecure = "tls"; // sets the prefix to the servier

		$mailer->Host = "smtp.gmail.com"; // sets GMAIL as the SMTP server

		$mailer->Port = 587; // set the SMTP port for the GMAIL server

		$mailer->Username = "caaryainc@gmail.com"; // GMAIL username

		$mailer->Password = "topgear123!"; // GMAIL password 

		$mailer->AddAddress($this->admin_email);

		$mailer->Subject = "New registration: ".$formvars['name'];

		$mailer->From = "caaryainc@gmail.com";         

		$mailer->Body ="A new user registered at ".$this->sitename."\r\n".
			"Name: ".$formvars['name']."\r\n".
			"Email address: ".$formvars['email']."\r\n".
			"UserName: ".$formvars['username'];

		if(!$mailer->Send())
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	function SaveToDatabase(&$formvars)
	{
		if(!$this->DBLogin())
		{
			$this->HandleError("Database login failed!");
			return false;
		}
		if(!$this->Ensuretable())
		{
			return false;
		}
		if(!$this->IsFieldUnique($formvars,'email'))
		{
			$this->HandleError("This email is already registered");
			return false;
		}

		/* if(!$this->IsFieldUnique($formvars,'username'))
		   {
		// $this->HandleError("This UserName is already used. Please try another username");
		//return false;
		return true;
		}     */  
		if(!$this->InsertIntoDB($formvars))
		{
			$this->HandleError("Inserting to Database failed!");
			return false;
		}
		return true;
	}

	function IsFieldUnique($formvars,$fieldname)
	{
		$field_val = $this->SanitizeForSQL($formvars[$fieldname]);
		$qry = "select userid, email from $this->tablename where $fieldname='".$field_val."'";
		$result = mysql_query($qry,$this->connection);   
		if($result && mysql_num_rows($result) > 0)
		{
			return false;
		}
		return true;
	}

	function DBLogin()
	{

		$this->connection = mysql_connect($this->db_host,$this->username,$this->pwd);

		if(!$this->connection)
		{   
			$this->HandleDBError("Database Login failed! Please make sure that the DB login credentials provided are correct");
			return false;
		}
		if(!mysql_select_db($this->database, $this->connection))
		{
			$this->HandleDBError('Failed to select database: '.$this->database.' Please make sure that the database name provided is correct');
			return false;
		}
		if(!mysql_query("SET NAMES 'UTF8'",$this->connection))
		{
			$this->HandleDBError('Error setting utf8 encoding');
			return false;
		}
		return true;
	}    

	function Ensuretable()
	{
		$result = mysql_query("SHOW COLUMNS FROM $this->tablename");   
		if(!$result || mysql_num_rows($result) <= 0)
		{
			return $this->CreateTable();
		}
		return true;
	}

	function CreateTable()
	{
		$qry = "Create Table $this->tablename (".
			"userid INT NOT NULL AUTO_INCREMENT ,".
			"name VARCHAR( 128 ) NOT NULL ,".
			"email VARCHAR( 64 ) NOT NULL ,".
			"username VARCHAR( 16 ) NOT NULL ,".
			"password VARCHAR( 32 ) NOT NULL ,".
			"confirmcode VARCHAR(32) ,".
			"address VARCHAR(256) NOT NULL ,".
			"PRIMARY KEY ( userid )".
			")";

		if(!mysql_query($qry,$this->connection))
		{
			$this->HandleDBError("Error creating the table \nquery was\n $qry");
			return false;
		}
		return true;
	}

	function InsertIntoDB(&$formvars)
	{

		$confirmcode = $this->MakeConfirmationMd5($formvars['email']);

		$formvars['confirmcode'] = $confirmcode;

		$insert_query = 'insert into '.$this->tablename.'(
				name,
				email,
				username,
				password,
				confirmcode,
				address
				)
			values
			(
			 "' . $this->SanitizeForSQL($formvars['name']) . '",
			 "' . $this->SanitizeForSQL($formvars['email']) . '",
			 "' . $this->SanitizeForSQL($formvars['username']) . '",
			 "' . md5($formvars['password']) . '",
			 "' . $confirmcode . '",
			 "' . $this->SanitizeForSQL($formvars['address']) . '"

			)';      
		if(!mysql_query( $insert_query ,$this->connection))
		{
			$this->HandleDBError("Error inserting data to the table\nquery:$insert_query");
			return false;
		}        
		return true;
	}

	function encrypt_decrypt($action, $string) {
		$output = false;

		$encrypt_method = "AES-256-CBC";
		$secret_key = 'caaryAInc';
		$secret_iv = '!321Topgear';

		// hash
		$key = hash('sha256', $secret_key);
		$iv = hash('sha256', $secret_iv);

		if( $action == 'encrypt' ) {
			$output = @openssl_encrypt($string, $encrypt_method, $key, $iv);
			$output = base64_encode($output);
		}
		else if( $action == 'decrypt' ){
			$output = $decryptedMessage = @openssl_decrypt(base64_decode($string), $encrypt_method, $key, $iv);
		}

		return $output;
	}

	function MakeConfirmationMd5($email)
	{
		$randno1 = rand();
		$randno2 = rand();
		return md5($email.$this->rand_key.$randno1.''.$randno2);
	}

	function stringhash($string){
		$md5hash =  md5($string);
		$strhash = substr($md5hash,0,6);
		$inthash = base_convert($strhash,16,10);
		return $inthash;
	}

	function SanitizeForSQL($str)
	{
		if( function_exists( "mysqli_real_escape_string" ) )
		{
			$ret_str = mysql_real_escape_string( $str );
		}
		else
		{
			$ret_str = addslashes( $str );
		}
		return $ret_str;
	}

	/*
	   Sanitize() function removes any potential threat from the
	   data submitted. Prevents email injections or any other hacker attempts.
	   if $remove_nl is true, newline chracters are removed from the input.
	 */
	function Sanitize($str,$remove_nl=true)
	{
		$str = $this->StripSlashes($str);

		if($remove_nl)
		{
			$injections = array('/(\n+)/i',
					'/(\r+)/i',
					'/(\t+)/i',
					'/(%0A+)/i',
					'/(%0D+)/i',
					'/(%08+)/i',
					'/(%09+)/i'
					);
			$str = preg_replace($injections,'',$str);
		}

		return $str;
	}    
	function StripSlashes($str)
	{
		if(get_magic_quotes_gpc())
		{
			$str = stripslashes($str);
		}
		return $str;
	} 

	function getConfirmCode(){

		if(!$this->DBLogin())	{
			echo "Database login failed";
		}
		$query = "Select confirmcode from user where email= '$email' ";

		$result = mysql_query($query);
		if (!$result) {
			die("Invalid query: " . mysql_error());
		} 

		$row = mysql_fetch_assoc($result); 

		if($row['confirmcode']!='y')
			return false;
		else
			return true;
	}

	function checkConfirmCode($email){

		if(!$this->DBLogin())	{
			echo "Database login failed";
		}
		$query = "Select confirmcode from user where email= '$email' ";

		$result = mysql_query($query);
		if (!$result) {
			die("Invalid query: " . mysql_error());
		} 

		$row = mysql_fetch_assoc($result); 

		if($row['confirmcode']!='y')
			return false;
		else
			return true;
	}

}
?>
