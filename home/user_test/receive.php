<?php
if(isset($_REQUEST['ErrCode']) && $_REQUEST['ErrCode'] != "" && $_REQUEST['ErrCode'] != null){
	header("Location: receive_ng.php");
} else {
	header("Location: receive_ok.php");
}
?>
