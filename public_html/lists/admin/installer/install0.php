<?php
/* this script is called by install.php
   that parent-script have serveral included files
   witch contain some functions used in this one.
   i.e.:
   steps-lib.php
   languages.php
   mysql.inc
   install-texts.inc
*/

if ($_SESSION["session_ok"] != 1){
   header("Location:?");
}



/***************************************************
  This script use only the structure DATABASE_DEF
***************************************************/

include("lib/parameters.inc");

genPHPVariables($database_def); 

if ($submited){
   /* The code above take the mission to validate the database data
      enter by the user in the Step 0.
   */

   getPostVariables($database_def);

   $errno     = check_connection($database_host, $database_user, $database_password, $database_name);
   $errno_arr = explode("|", $errno);

   $msg = $errno_arr[1];
   $_SESSION['dbCreatedSuccesfully'] = $errno_arr[2];


   if ($errno_arr[0] > 0){
      $HTMLElements = getHTMLElements($database_def); 
      $inTheSame = 1;
   }
   else{
      setSessionData($database_def);
      $inTheSame = 0;

      header("Location:?page=".($page+1));
   }
}
else{
   $msg = "";
   $inTheSame = 1;

   getSessionData($database_def);

   $HTMLElements = getHTMLElements($database_def); 
}


include("installer/lib/js_nextPage.inc");

$dbname = $GLOBALS["I18N"]->get($GLOBALS['strJsDbName']);
$dbhost = $GLOBALS["I18N"]->get($GLOBALS['strJsDbHost']);
$dbuser = $GLOBALS["I18N"]->get($GLOBALS['strJsDbUser']);
$dbpass = $GLOBALS["I18N"]->get($GLOBALS['strJsDbPass']);

?>

<br>
<br>
<div class="wrong"><?echo $msg?></div>
<style type="text/css">
table tr td input { float:right; }
</style>

<table width=500>
  <tr>
    <td>
    <div class="explain"><?echo $GLOBALS["I18N"]->get($GLOBALS['strDbExplain'])?></div>
    </td>
  </tr>
</table>

<script type="text/javascript">
function validation(){
   var frm = document.pageForm;
   
   if (frm.database_name.value == ""){
      alert("<?echo $dbname?>");
      frm.database_name.focus();

      return false;
   }
   
   if (frm.database_host.value == ""){
      alert("<?echo $dbhost?>");
      frm.database_host.focus();

      return false;
   }
   
   if (frm.database_user.value == ""){
      alert("<?echo $dbuser?>");
      frm.database_user.focus();

      return false;
   }
   
   if (frm.database_password.value == ""){
      alert("<?echo $dbpass?>");
      frm.database_password.focus();

      return false;
   }

   return true;
}
</script>

<form method="post" name="pageForm">
  <input type="hidden" name="page" value="<?echo $nextPage?>"/>
  <input type="hidden" name="submited" value="<?echo $inTheSame?>"/>

  <table border=0 width=500>
    <?echo $HTMLElements?>
  </table>
</form>
<?php
include("installer/lib/nextStep.inc");
?>