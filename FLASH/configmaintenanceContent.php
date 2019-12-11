
<?php


//echo $role; 


// if (strcmp( $role, 'zlf')==0) SQL 
//error_reporting(E_ALL);
//// speed things up with gzip plus ob_start() is required for csv export
//if(!ob_start('ob_gzhandler'))
//	ob_start();
//header('Content-Type: text/html; charset=utf-8');
include('lazy_mofo.php');


require("inc/dbinfo.inc");
$db_host=$servername;
$db_user=$dbusername;
$db_pass=$dbpassword;
$db_name=$dbname;





//##########################################################################################

$table="setup"; 
$primarykey="id"; 

$wherecond = "";

$gridsql="
select 
id,
type,
value,
id from $table
where (coalesce(type, '') like :_search )
";




$formsql="
select
id,
type,
value
from $table 
where  
id = :id
";




//##########################################################################################


// connect with pdo 
try {
	$dbh = new PDO("mysql:host=$db_host;dbname=$db_name;", $db_user, $db_pass,
	              array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
}
catch(PDOException $e) {
	die('pdo connection error: ' . $e->getMessage());
}


// create LM object, pass in PDO connection, see i18n folder for country + language options 
$lm = new lazy_mofo($dbh, 'en-us'); 
//$lm = new lazy_mofo($dbh, 'utf8'); 
// table name for updates, inserts and deletes
$lm->table = $table;


// identity / primary key for table
$lm->identity_name = $primarykey;


// optional, make friendly names for fields
//$lm->rename['country_id'] = 'Country';


// optional, define input controls on the form
//$lm->form_input_control['photo'] = '--image';
//$lm->form_input_control['is_active'] = "select 1, 'Yes' union select 0, 'No' union select 2, 'Maybe'; --radio";
//$lm->form_input_control['country_id'] = 'select country_id, country_name from country; --select';


// optional, define editable input controls on the grid
//$lm->grid_input_control['is_active'] = '--checkbox';
$lm->form_input_control['enable'] = "select '1', '1.' union select '0', '0.'  ; --radio";
$lm->form_input_control['role'] = "select 'm', 'master.' union select 't', 'teacher.' union select 'a', 'assistant.' union select 's', 'student.' ; --radio";

// optional, define output control on the grid 
$lm->grid_output_control['Email'] = '--email'; // make email clickable
//$lm->grid_output_control['photo'] = '--image'; // image clickable  


// new in version >= 2015-02-27 all searches have to be done manually
$lm->grid_show_search_box = true;

// optional, query for grid().
// ** IMPORTANT - last column must be the identity/key for [edit] and [delete] links to appear **
$lm->grid_sql = $gridsql;
$lm->grid_sql_param[':_search'] = '%' . trim(@$_REQUEST['_search']) . '%';



// optional, define what is displayed on edit form. identity id must be passed in also.  
$lm->form_sql = $formsql;
$lm->form_sql_param[":$lm->identity_name"] = @$_REQUEST[$lm->identity_name]; 


// optional, validation. input:  regular expression (with slashes), error message, tip/placeholder
// first element can also be a user function or 'email'
$lm->on_insert_validate['CName'] = array('/.+/', 'Missing Chinese Name', 'this is required'); 
$lm->on_insert_validate['email'] = array('email', 'Invalid Email', 'this is optional', true); 


// copy validation rules to update - same rules
$lm->on_update_validate = $lm->on_insert_validate;  

//########################################################################################## START





//########################################################################################## END


echo " <div style='overflow-x:auto;'>";
// use the lm controller
$lm->run();
//echo "</body></html>";
echo " </div>";


?>



