<?php

    use ExternalModules\ExternalModules;

    require_once APP_PATH_DOCROOT . 'ProjectGeneral/header.php';
    $project_id = $_GET['pid'];

    if (is_numeric($project_id)) {
        $module = new \Vanderbilt\StudyCounterbalancingExternalModule\StudyCounterbalancingExternalModule($project_id);
        $moduleProject = new \Project($project_id);
        $events = array_flip($moduleProject->getUniqueEventNames());

        $formList = $moduleProject->forms;
        $recordField = $moduleProject->table_pk;
        $recordInfo = json_decode(\Records::getData($project_id, 'json', array(), array($recordField)),true);
        $allRecords = array();
        foreach ($recordInfo as $index => $data) {
            $allRecords[$data[$recordField]][$events[$data['redcap_event_name']]] = $moduleProject->eventInfo[$events[$data['redcap_event_name']]]['name']." (".$data['redcap_event_name'].")";
        }

        $sql = "SELECT `key`,value from `redcap_external_module_settings` where project_id = ".$project_id." AND external_module_id = ".ExternalModules::getIdForPrefix($module->PREFIX)." AND `key` LIKE 'randomization-%' ";
        $result = $module->query($sql);

        $matchingProjects = '';
        $tableString = "<h2>Participant Counterbalancing</h2>
        <table id='counterbalance-table' class='counterbalance-table' style='width:90%;'>
            <thead><tr><th>Record ID</th><th>Event Name</th><th>Order of Form Counterbalancing</th></tr></thead><tbody>";
        while($row = db_fetch_assoc($result)) {
            list($settingKey,$settingRecord,$settingEvent) = explode("-",$row['key']);
            $formOrder = json_decode($row['value'],true);

            if ($settingKey == "randomization" && in_array($settingRecord,array_keys($allRecords)) && isset($allRecords[$settingRecord][$settingEvent])) {
                $tableString .= "<tr><td>$settingRecord</td><td>".$allRecords[$settingRecord][$settingEvent]."</td><td>";
                foreach ($formOrder[0] as $form) {
                    $tableString .= $formList[$form]['menu']."<br/>";
                }
                $tableString .= "</td></tr>";
            }
        }
        $tableString .= "</tbody></table>";
        echo $tableString;
    }
?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#counterbalance-table').DataTable();
    });
</script>
<style>
    .counterbalance-table th {
        background-color: lightblue;
    }

    .counterbalance-table tr,td,th {
        border: solid black 1px;
        text-align:center;
        padding: 2px;
    }
</style>
