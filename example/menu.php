<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 10.02.2016
 * Time: 23:16
 */

use StepanSib\AlmClient\AlmCurlCookieStorage;

?>

<a style='margin-right: 15px;' href="login.php">Login</a>
<a style='margin-right: 15px;' href="logout.php">Logout</a>
<a style='margin-right: 15px;' href="status.php">Connection status</a>
<a style='margin-right: 15px;' href="query.php">Query</a>
<a style='margin-right: 15px;' href="links.php">Defect links</a>
<a style='margin-right: 15px;' href="attachment.php">Attachments</a>
<a style='margin-right: 15px;' href="query_xml.php" target="_blank">XML Query</a>
<a style='margin-right: 15px;' href="query_wrong.php">Wrong query</a>
<a style='margin-right: 15px;' href="fields.php">Defect fields</a>
<a style='margin-right: 15px;' href="fields_full_xml.php" target="_blank">XML Defect fields</a>
<a style='margin-right: 15px;' href="req_fields.php">Defect required fields</a>
<a style='margin-right: 15px;' href="fields_editable.php">Defect editable fields</a>
<a style='margin-right: 15px;' href="lists.php" target="_blank">XML Lists</a>
<a style='margin-right: 15px;' href="create.php">Create defect</a>
<a style='margin-right: 15px;' href="lock_status.php">Defect lock status</a>
<a style='margin-right: 15px;' href="update.php">Update defect</a>
<a style='margin-right: 15px;' href="delete.php">Delete defect</a>

<?php

// You can get cookie storage file path
$cookieStorage = new AlmCurlCookieStorage();
if ($cookieStorage->isCurlCookieFileExist()) {
    $cookieStorageFile = $cookieStorage->getCurlCookieFile();
}
?>
<hr>
