<?php

namespace uzgent\VerifyClass;

// Declare your module class, which must extend AbstractExternalModule
class VerifyClass extends \ExternalModules\AbstractExternalModule {

    public function redcap_data_entry_form($project_id, $record, $instrument, $event_id, $group_id, $repeat_instance)
    {
        $debug = $this->getProjectSetting("debug");
        $project_users = \REDCap::getUserRights(USERID);
        $resolution = $project_users[USERID]['data_quality_resolution'];
        /**
         * resolution should be Open queries only OR Open and respond to queries OR Open, close, and respond to queries
         */
        if ($resolution < 3 || $resolution > 5)
        {
            if ($debug){
                echo "You don't have the appropriate Data Resolution Workflow rights.";
            }
            return;
        }

        if ($record !== null) {
            echo '<form action="'.$this->getUrl("verify.php").'" method="post">';
            ?>
                <input type="hidden" name="projectid" value="<?php echo $project_id; ?>">
                <input type="hidden" name="recordid" value="<?php echo $record; ?>">
                <input type="hidden" name="eventid" value="<?php echo $event_id; ?>">
                <input type="hidden" name="instrument" value="<?php echo $instrument; ?>">
                <input type="hidden" name="instanceid" value="<?php echo $repeat_instance; ?>">
                <input type="submit" class="btn btn-warning" value="Verify all fields"/>
            </form>
        <?php

        } else
        {
            if ($debug === true){
                echo "Record needs to be saved first";
            }
        }
    }

}
