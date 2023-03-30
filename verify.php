<?php

require_once __DIR__."/../../redcap_connect.php";
require_once __DIR__."/MetaDataDAO.php";
require_once __DIR__."/DataResolutionDAO.php";

$HtmlPage = new HtmlPage();
$HtmlPage->PrintHeaderExt();

$projectid = htmlspecialchars($_POST["projectid"], ENT_QUOTES);
$record = htmlspecialchars($_POST["recordid"], ENT_QUOTES);
$instrument = htmlspecialchars($_POST["instrument"], ENT_QUOTES);
$eventid = htmlspecialchars($_POST["eventid"], ENT_QUOTES);
$instanceid = htmlspecialchars($_POST["instanceid"], ENT_QUOTES);

echo "<h1>Updating for project $projectid on record $record</h1>";
echo "<p><a class='btn btn-info' data-toggle='collapse' href='#tableDiv' role='button' aria-expanded='false' aria-controls='tableDiv'>Show details</a></p>";
echo "<div class='collapse' id='tableDiv'>";
echo "<table class='table table-bordered'>";
echo "<tbody>";
echo "<tr>";
echo "<td>project_id</td>";
echo "<td>$projectid</td>";
echo "</tr>";
echo "<tr>";
echo "<td>record</td>";
echo "<td>$record</td>";
echo "</tr>";
echo "<tr>";
echo "<td>event_id</td>";
echo "<td>$eventid</td>";
echo "</tr>";
echo "<tr>";
echo "<td>instrument</td>";
echo "<td>$instrument</td>";
echo "</tr>";
echo "<tr>";
echo "<td>instance</td>";
echo "<td>$instanceid</td>";
echo "</tr>";
echo "</tbody>";
echo "</table>";
echo "</div>";
$metadataDao = new uzgent\VerifyClass\MetaDataDAO($conn);
$resolutionDao = new uzgent\VerifyClass\DataResolutionDAO($conn);

$userInitiator = User::getUserInfo(USERID);
$skipped = [];
$verified = [];

foreach ($metadataDao->getFieldNames($projectid, $instrument) as $field)
{
    if ($field == "record_id") {
        $skipped []= $field;
    } else if ($resolutionDao->fieldHasComments($projectid, $record, $field, $instanceid, $eventid))
    { 
        $skipped []= $field;
    } else {
        $verified []= $field;
        $resolutionDao->markFieldAsVerified($projectid, $record, $field, $instanceid, $userInitiator['ui_id'], $eventid);        
    } 

}
if (count($verified) > 0)
{
    echo "<h3>The following fields have been set to verified: </h3>";
    echo "<ul>";
    foreach($verified as $verifiedField)
    {
        echo "<li>" . htmlspecialchars($verifiedField, ENT_QUOTES) . "</li>";
    }
    echo "</ul>";
}
if (count($skipped) > 0)
{
    echo "<h3>The following fields have been skipped: </h3>";
    echo "<ul>";
    foreach($skipped as $skippedField)
    {
        echo "<li>" . htmlspecialchars($skippedField, ENT_QUOTES) . "</li>";
    }
    echo "</ul>";    
}

?>

<button class="btn btn-success" onclick="window.history.go(-1); return false;">OK</button>
