<br/>
<?php


require_once __DIR__."/../../../../redcap_connect.php";

global $conn;



$project_users = REDCap::getUserRights($userid);
$resolution = $project_users[USERID]['data_quality_resolution'];
/**
 * resolution should be Open queries only OR Open and respond to queries OR Open, close, and respond to queries
 */
if ($resolution < 3 || $resolution > 5)
{
    return;
}
if ($record !== null)
{
?>
    
<form action="../../hooks/framework/resources/verify/verify.php" method="get">
    <input type="hidden" name="projectid" value="<?php echo $project_id; ?>">
    <input type="hidden" name="recordid" value="<?php echo $record; ?>">
    <input type="hidden" name="eventid" value="<?php echo $event_id; ?>">
    <input type="hidden" name="instrument" value="<?php echo $instrument; ?>">
    <input type="hidden" name="instanceid" value="<?php echo $repeat_instance; ?>">
    <input type="submit" class="btn btn-warning" value="Verify all fields"/>
</form>

<?php

}
?>