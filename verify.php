<?php

require_once __DIR__."/../../redcap_connect.php";
require_once __DIR__."/MetaDataDAO.php";
require_once __DIR__."/DataResolutionDAO.php";

$HtmlPage = new HtmlPage();
$HtmlPage->PrintHeaderExt();

$projectid = $_POST["projectid"];
$record = $_POST["recordid"];
$instrument = $_POST["instrument"];
$eventid = $_POST["eventid"];
$instanceid = $_POST["instanceid"];

echo "<h1>Updating for project $projectid on record $record</h1>";

$metadataDao = new MetaDataDAO($conn);
$resolutionDao = new DataResolutionDAO($conn);

$userInitiator = User::getUserInfo(USERID);
$skipped = [];
$verified = [];

foreach ($metadataDao->getFieldNames($projectid, $instrument) as $field)
{
    if ($field == "record_id") {
        $skipped []= $field;
    } else if ($resolutionDao->fieldHasComments($projectid, $record, $field, $instanceid))
    { 
        $skipped []= $field;
    } else {
        $verified []= $field;
        $resolutionDao->markFieldAsVerified($projectid, $record, $field, $instanceid, $userInitiator['ui_id'], $eventid);        
    } 

}
if (count($verified) > 0)
{
    echo "<h2>The following fields have been set to verified: </h2>";
    echo "<ul>";
    foreach($verified as $verifiedField)
    {
        echo "<li>$verifiedField</li>";
    }
    echo "</ul>";
}
if (count($skipped) > 0)
{
    echo "<h2>The following fields have been skipped: </h2>";
    echo "<ul>";
    foreach($skipped as $skippedField)
    {
        echo "<li>$skippedField</li>";
    }
    echo "</ul>";    
}

?>

<button class="btn btn-success" onclick="window.history.go(-1); return false;">OK</button>
