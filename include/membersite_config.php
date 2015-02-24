<?PHP
require_once("./include/fg_membersite.php");
$fgmembersite = new FGMembersite($mylang);
//Provide your site name here
$fgmembersite->SetWebsiteName('grancum.honor.es');
//Provide the email address where you want to get notifications
$fgmembersite->SetAdminEmail('markareno@gmail.com');
//Provide your database login details here:
//hostname, user name, password, database name and table name
//note that the script will create the table (for example, fgusers in this case)
//by itself on submitting register.php for the first time
$fgmembersite->InitDB(/*hostname*/'mysql.2freehosting.com',
                      /*username*/'u613237816_admin',
                      /*password*/'Power2013',
                      /*database name*/'u613237816_login',
                      /*table name*/'fgusers3');
//For better security. Get a random string from this link: http://tinyurl.com/randstr
// and put it here
$fgmembersite->SetRandomKey('qSRcVS6DrTzrPvr');
?>