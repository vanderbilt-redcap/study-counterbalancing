<?php

namespace Vanderbilt\StudyCounterbalancingExternalModule;

use ExternalModules\AbstractExternalModule;
use ExternalModules\ExternalModules;

class StudyCounterbalancingExternalModule extends AbstractExternalModule
{
    function redcap_every_page_top() {
        /*$intakeForms = $this->getProjectSetting('intake-form');
        $randomizedFormsArray = $this->getProjectSetting('cb-forms');
        $viewSurveysArray = $this->getProjectSetting('survey-view');
        $events = $this->getProjectSetting('event-id');
        $randomizeSettings = json_decode($this->getProjectSetting('randomization'),true);

        foreach ($intakeForms as $index => $intakeForm) {
            $event = $events[$index];
            $randomizedForms = $randomizedFormsArray[$index];
            $viewSurveys = $viewSurveysArray[$index];
            $randomizeSetting = $randomizeSettings[$index];
            //if (empty($randomizeSetting)) {
                $randomOptions = $this->randomizeForms($randomizedForms);
                echo "Random options:<br/>";
                echo "<pre>";
                print_r($randomOptions);
                echo "</pre>";
                echo "Existing: <br/>";
                echo "<pre>";
                print_r($randomizeSetting);
                decho "</pre>";
                list($chosenRandomization,$existingIndex) = $this->chooseRandomization($randomOptions,$randomizeSetting);
                echo "Chosen: <br/>";
                echo "<pre>";
                print_r($chosenRandomization);
                echo "</pre>";
                echo "Existing: <br/>";
                echo "<pre>";
                print_r($existingIndex);
                echo "</pre>";
                if (is_numeric($existingIndex)) {
                    echo "Exists<br/>";
                    $randomizeSettings[$index][$existingIndex] = array('form_orders' => $randomOptions[$chosenRandomization], 'count' => $randomizeSettings[$index][$existingIndex]['count']+1);
                }
                else {
                    echo "Doesn't exist<br/>";
                    $randomizeSettings[$index][] = array('form_orders' => $randomOptions[$chosenRandomization], 'count' => 1);
                }
            //}
        }

        echo "Save settings:<br/>";
        echo "<pre>";
        print_r($randomizeSettings);
        echo "</pre>";
        $this->setProjectSetting('randomization',json_encode($randomizeSettings),91);*/


        /*$randomizeSetting = json_decode($this->getProjectSetting('randomization'),true);
        echo "<pre>";
        print_r($randomizeSetting);
        echo "</pre>";
        $testForms = array("first","second","third","fourth","fifth");
        $checkRandom = $this->randomizeForms($testForms);
        echo "Random Options:<br/>";
        echo "<pre>";
        print_r($checkRandom);
        echo "</pre>";
        //$existingRandoms = array(array("first","second","sixth","third","fifth","fourth"),array("second","third","first","fourth","sixth","fifth"));
        for ($i = 0; $i < 10; $i++) {
            $chooseRandom = $this->chooseRandomization($checkRandom,array());
            echo "Select:<br/>";
            echo "<pre>";
            print_r($chooseRandom);
            echo "</pre>";
        }
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";
        $recordData = \Records::getData(67, 'array', array(317), array('vcc_application_survey_initial_complete'));
        echo "<pre>";
        print_r($recordData);
        echo "</pre>";*/
    }

    function redcap_data_entry_form($project_id, $record, $instrument, $event_id, $group_id = NULL, $repeat_instance = 1) {
        //The below code was necessary for resetting module settings for a project so it could be reset with all new records.
/*
        $project = new \Project($project_id);
        $events = $project->events;

        if (in_array($project_id,array(103538,102495,106458,102710,111557,111562,116774,116805,116831))) {
            foreach ($events as $eventData) {
                foreach ($eventData['events'] as $eventID => $idData)
                for ($i = 0; $i < 500; $i++) {
                    $this->removeProjectSetting("randomization-$i-$eventID");
                }
            }
        }*/

        //This code is for switching up where a record goes to fix old, invalid log mapping
        /*$recordChange = array(
            185=>1058,366=>1149,5=>"Laycox, Gloria June",312=>1119,493=>1203,358=>1145,549=>1228,43=>"Wade, Chester A",413=>1172,69=>1002,387=>1163,276=>1100,294=>1109,226=>1072,434=>1179,383=>1161,372=>1152,228=>1074,327=>1133,6=>"Alexander, Ronetta Renee",418=>1174,253=>1088,155=>1050,227=>1073,159=>1051,78=>1020,362=>1153,310=>1117,281=>1102,238=>1081,129=>1036,546=>1222,367=>1150,231=>1079,247=>1085,79=>1007,519=>1216,483=>1199,306=>1115,318=>1130,71=>1004,282=>1108,430=>1180,136=>1040,289=>1105,365=>1148,474=>1202,244=>1083,102=>1026,340=>1140,85=>1011,112=>1027,348=>1142,391=>1165,7=>"Cook, Allen R",233=>1077,328=>1134,73=>1005,167=>1054,475=>1195,339=>1138,432=>1182,191=>1061,293=>1107,117=>1031,41=>"Ruiz, Susie",162=>1052,239=>1082,131=>1037,33=>"Engidaye, Worknesh G",303=>1113,308=>1132,304=>1114,438=>1184,292=>1124,127=>1034,52=>1000,209=>1067,272=>1098,9=>"Eastridge, Joanne T",466=>1193,385=>1162,251=>1087,184=>1057,507=>1207,230=>1075,186=>1059,378=>1159,471=>1201,350=>1158,60=>"Devault, Michael J",63=>"Stephens, Susan",165=>1053,314=>1120,360=>1146,207=>1070,437=>1183,89=>1015,133=>1038,174=>1055,555=>1227,334=>1136,201=>1069,421=>1187,12=>"Tuan, Alh",515=>1213,116=>1030,405=>1171,255=>1090,125=>1033,321=>1127,221=>1086,151=>1049,347=>1141,422=>1175,375=>1156,264=>1096,34=>"Ruiz, Emeterio Medina",389=>1164,263=>1094,178=>1056,417=>1173,331=>1135,218=>1071,14=>"Goodrum, Stephen Carline",70=>1003,527=>1219,363=>1154,149=>1047,351=>1143,486=>1200,92=>1018,371=>1151,467=>1190,35=>"Mina, Mina L",511=>1211,83=>1010,476=>1196,97=>1023,88=>1014,37=>"Younan, Nagwa",119=>1032,113=>1028,145=>1044,220=>1078,192=>1062,462=>1189,544=>1221,287=>1104,86=>1012,439=>1185,379=>1168,16=>"Abdelmalak, Maria",260=>1093,433=>1178,397=>1169,521=>1212,498=>1204,455=>1188,104=>1022,138=>1041,478=>1197,533=>1215,232=>1076,529=>1214,94=>1019,99=>1024,423=>1176,275=>1122,380=>1160,503=>1224,442=>1186,68=>1001,541=>1220,77=>1008,543=>1223,31=>"Granstaff, Mary Eleanor",505=>1206,19=>"Cathcart, Cyril L",325=>1131,20=>"Nelson, John",285=>1106,273=>1099,38=>"Hernandez Perez=> Bertha",74=>1006,27=>"Cook, Jack H",237=>1080,54=>"Akin, James Leon",256=>1091,512=>1209,22=>"Garcia, Eba",29=>"Lian, Do Khan",30=>"Alvarado Cadena, Alfredo",28=>"Bonilla, Manuel De Jesus",75=>1009,39=>"Haji, Bardo",48=>"Franklin, Donnie",59=>"Vargas, Ricardo",134=>1039,90=>1017,87=>1013,91=>1016,114=>1029,100=>1021,106=>1025,111=>1035,206=>1065,141=>1042,190=>1060,144=>1043,146=>1045,197=>1064,200=>1066,150=>1048,210=>1068,195=>1063,148=>1046,254=>1089,357=>1147,317=>1123,284=>1103,297=>1112,280=>1101,245=>1084,311=>1118,309=>1116,266=>1095,271=>1097,295=>1110,299=>1125,257=>1092,404=>1170,377=>1157,343=>1139,355=>1144,424=>1177,315=>1121,320=>1126,374=>1155,396=>1167,322=>1128,316=>1129,338=>1137,392=>1166,469=>1191,479=>1198,470=>1192,444=>1194,563=>1231,427=>1181,499=>1205,508=>1208,560=>1230,520=>1218,552=>1226,536=>1217,514=>1210,556=>1229,548=>1225,

        );
        if (in_array($record,array_keys($recordChange)) && $project_id == "110730") {
            $this->removeLogs("DELETE WHERE message = 'Auto record for $record' AND project_id=$project_id");
            $logID = $this->log("Auto record for " . $record, ["destination_record_id" => $recordChange[$record]]);
        }*/
    }

    function redcap_save_record($project_id, $record, $instrument, $event_id, $group_id = NULL, $survey_hash = NULL, $response_id = NULL, $repeat_instance = 1) {
        global $redcap_version, $Proj;

        $intakeForms = $this->getProjectSetting('intake-form');
        $randomizedFormsArray = $this->getProjectSetting('cb-forms');
        $viewSurveysArray = $this->getProjectSetting('survey-view');
        $events = $this->getProjectSetting('event-id');
        $randomizeSettings = json_decode($this->getProjectSetting('randomization'),true);

        $recordRandom = json_decode($this->getProjectSetting("randomization-$record-$event_id"),true);

        $redirectURL = "";

        foreach ($intakeForms as $index => $intakeForm) {
            $event = $events[$index];
            $randomizedForms = $randomizedFormsArray[$index];
            $viewSurveys = $viewSurveysArray[$index];
            $randomizeSetting = $randomizeSettings[$index];

            if ($instrument == $intakeForm && !empty($randomizedForms) && $event == $event_id) {
                if (empty($recordRandom[$index])) {
                    $randomizeOptions = $this->randomizeForms($randomizedForms);
                    //$chosenIndex = $this->chooseRandomization($randomizeOptions,$randomizeSetting);

                    list($chosenRandomization, $existingIndex) = $this->chooseRandomization($randomizeOptions, $randomizeSetting);

                    if (is_numeric($existingIndex)) {
                        $randomizeSettings[$index][$existingIndex] = array('form_orders' => $randomizeOptions[$chosenRandomization], 'count' => $randomizeSettings[$index][$existingIndex]['count'] + 1);
                    } else {
                        $randomizeSettings[$index][] = array('form_orders' => $randomizeOptions[$chosenRandomization], 'count' => 1);
                    }
                    $this->setProjectSetting('randomization', json_encode($randomizeSettings), $project_id);
                    $recordRandom[$index] = $randomizeOptions[$chosenRandomization];
                }
            }
            $currentRandomIndex = array_keys($recordRandom[$index], $instrument)[0];

            if (in_array($instrument, $recordRandom[$index])) {
                $nextInstrument = $recordRandom[$index][$currentRandomIndex + 1];
            } else {
                $nextInstrument = $recordRandom[$index][0];
            }
            /*if ($project_id == 103538) {
                echo "Instrument is $instrument, and next instrument is $nextInstrument<br/>";
                echo "<pre>";
                print_r($recordRandom[$index]);
                echo "</pre>";
            }*/

            $viewAsSurveyIndex = array_keys($randomizedForms, $nextInstrument)[0];
            if (!empty($randomizedForms) && !empty($recordRandom[$index]) && $nextInstrument != "") {
                $recordData = \Records::getData($project_id, 'array', array($record), array($instrument . "_complete", $nextInstrument . '_complete'));
                if ($Proj->forms[$nextInstrument]['survey_id'] != "" && $viewSurveys[$viewAsSurveyIndex] == "yes" && ($recordData[$record][$event_id][$instrument . '_complete'] == "2" || $recordData[$record]['repeat_instances'][$event_id][$instrument][$repeat_instance][$instrument . '_complete'] == "2" || $recordData[$record]['repeat_instances'][$event_id][''][$repeat_instance][$instrument . '_complete'])) {
                    if ($recordData[$record][$event_id][$nextInstrument . '_complete'] != "" || $recordData[$record]['repeat_instances'][$event_id][$nextInstrument][$repeat_instance][$nextInstrument . '_complete'] != "" || $recordData[$record]['repeat_instances'][$event_id][''][$repeat_instance][$nextInstrument . '_complete'] != "") {
                        $surveyHashCode = $this->surveyHashByInstrument($project_id, $record, $nextInstrument, $event_id, $repeat_instance);
                        $redirectURL = APP_PATH_WEBROOT_FULL . "surveys/?s=" . $surveyHashCode["hash"];
                    } else {
                        //$surveyHashCode = $this->generateUniqueRandomSurveyHash();
                        //$surveyHashCode = \Survey::setHash($Proj->forms[$nextInstrument]['survey_id'],"",$event_id);
                        $surveyHashCode = $this->surveyHashByInstrument($project_id, $record, $nextInstrument, $event_id, $repeat_instance);
                        $redirectURL = APP_PATH_WEBROOT_FULL . "surveys/?s=" . $surveyHashCode["hash"];
                    }
                } elseif ($viewSurveys[$viewAsSurveyIndex] == "no") {
                    $redirectURL = APP_PATH_WEBROOT_FULL . "redcap_v" . $redcap_version . "/DataEntry/index.php?pid=$project_id&event_id=$event_id&page=$nextInstrument&id=$record&instance=$repeat_instance<br/>";
                }
            }
        }
        /*if ($project_id == 103538) {
            echo "Trying to go to: $redirectURL<br/>";
            echo "Record Data:<br/>";
            echo "<pre>";
            print_r($recordData);
            echo "</pre>";
            echo "Record: $record<br/>";
            echo "Event: $event_id<br/>";
            echo "First check: " . $recordData[$record][$event_id][$instrument . '_complete'] . "<br/>";
            echo "Second check: " . $recordData[$record]['repeat_instances'][$event_id][$instrument][$repeat_instance][$instrument . '_complete'] . "<br/>";
            echo "Third check: " . $recordData[$record]['repeat_instances'][$event_id][''][$repeat_instance][$instrument . "_complete"] . "<br/>";
            $intakeForm = $this->getProjectSetting('intake-form');
            $randomizedForms = $this->getProjectSetting('cb-forms');
            $viewSurveys = $this->getProjectSetting('survey-view');
            $randomizeSetting = json_decode($this->getProjectSetting('randomization'), true);
            echo "Intake:<br/>";
            echo "<pre>";
            print_r($intakeForm);
            echo "</pre>";
            echo "Random: <br/>";
            echo "<pre>";
            print_r($randomizedForms);
            echo "</pre>";
            echo "Survey View: <br/>";
            echo "<pre>";
            print_r($viewSurveys);
            echo "</pre>";
            echo "R Setting:<br/>";
            echo "<pre>";
            print_r($randomizeSetting);
            echo "</pre>";
            echo "<pre>";
            print_r($recordRandom);
            echo "</pre>";
            //$this->exitAfterHook();
        }*/
        $this->setProjectSetting("randomization-$record-$event_id", json_encode($recordRandom), $project_id);
        if ($redirectURL != "") {
            //echo "Attempting to redirect to $redirectURL<br/>";
            header("Location: $redirectURL");
            $this->exitAfterHook();
            //exit;
        }
    }

    /*
     * Get full list of possible randomizations based on provided list of forms. Uses basic 'Latin Square' process.
     * @param $formArray List of forms to randomize.
     * @return Array of arrays. All possible randomization options based on provided form list.
     */
    function randomizeForms($formArray) {
        $randomOrders = array();
        $numberOrders = count($formArray);
        $n = 0;
        while ($n < $numberOrders) {
            $x = 0;
            while ($x < $numberOrders) {
                if ($x % 2 == 0 && $x > 0) {
                    /*echo "The X $x<br/>";
                    echo "The n $n<br/>";
                    echo "Number: $numberOrders<br/>";
                    echo "Mod: ".((($numberOrders - 1) - (($x - 2) / 2)) + $n) % $numberOrders."<br/>";*/
                    $randomOrders[$n][$x] = $formArray[((($numberOrders - 1) - (($x - 2) / 2)) + $n) % $numberOrders];
                }
                else {
                    /*echo "The X $x<br/>";
                    echo "The n $n<br/>";
                    echo "Number: $numberOrders<br/>";
                    echo "Mod: ".(((int)$x + (int)$n) % (int)$numberOrders)."<br/>";*/
                    if ($x > 1) {
                        $randomOrders[$n][$x] = $formArray[(($x - (($x - 2) /2)) + $n) % $numberOrders];
                    }
                    else {
                        $randomOrders[$n][$x] = $formArray[($x + $n) % $numberOrders];
                    }
                }
                if ($numberOrders % 2 != 0 && $numberOrders > 1) {
                    $randomOrders[$numberOrders + $n][($numberOrders - 1) - $x] = $randomOrders[$n][$x];
                }
                $x++;
            }
            ksort($randomOrders[$n]);
            if ($numberOrders % 2 != 0) {
                ksort($randomOrders[$numberOrders + $n]);
            }
            $n++;
        }
        return $randomOrders;
    }

    /*
     * Chooses a valid randomization from list of possible, based on least common choices.
     * @param $randomOrder The list of possible randomizations based on forms selected.
     * @param $existingRandomizations How many records exist for each of the possible randomizations.
     * @return Array Selected randomization selected randomly from those with least occurrences.
     */
    function chooseRandomization($randomOrder,$existingRandomizations) {
        $countArray = array();
        $existingArray = array();
        foreach ($randomOrder as $index => $ro) {
            $currentCount = 0;
            $existingIndex = "";
            foreach ($existingRandomizations as $eIndex => $existing) {
                if ($existing['form_orders'] === $ro) {
                    $currentCount = $existing['count'];
                    $existingIndex = $eIndex;
                }
            }

            $countArray[$index] = $currentCount;
            if ($existingIndex !== "") {
                $existingArray[$index] = $existingIndex;
            }
        }

        $randIndex = array_rand((array_keys($countArray,min($countArray))));
        $chosenIndex = array_keys($countArray,min($countArray))[$randIndex];
        //$chosenRandomization = $randomOrder[array_keys($countArray,min($countArray))[$randIndex]];

        //return $chosenRandomization;
        return array($chosenIndex, ($existingArray[$chosenIndex] !== "" ? $existingArray[$chosenIndex] : ""));
    }

    /*
     * Get the hash of an existing survey, and reset it to allow for viewing/submitting.
     * @param $projectId Project ID with the survey.
     * @param $recordId Record ID for to be examined.
     * @param $surveyFormName Form name of the survey.
     * @param $eventId Event ID to use for the survey (default to first event if none provided).
     * @return Array Array with keys of "hash" and "return_code".
     */
    function surveyHashByInstrument($projectId, $recordId, $surveyFormName = "", $eventId = "", $instance = 1) {
        /*$returnArray = $this::resetSurveyAndGetCodes($projectId,$recordId,$surveyFormName,$eventId);
        return $returnArray;*/
        list($surveyId,$surveyFormName) = $this->getSurveyId($projectId,$surveyFormName);

        ## Validate surveyId and surveyFormName were found
        if($surveyId == "" || $surveyFormName == "") return false;

        ## Find valid event ID for form if it wasn't passed in
        if($eventId == "") {
            $eventId = $this->getValidFormEventId($surveyFormName,$projectId);

            if(!$eventId) return false;
        }

        ## Search for a participant and response id for the given survey and record
        list($participantId,$responseId) = $this->getParticipantAndResponseId($surveyId,$recordId,$eventId);

        ## Create participant and return code if doesn't exist yet
        if($participantId == "" || $responseId == "") {
            $hash = self::generateUniqueRandomSurveyHash();

            ## Insert a participant row for this survey
            $sql = "INSERT INTO redcap_surveys_participants (survey_id, event_id, participant_email, participant_identifier, hash)
					VALUES ($surveyId,".prep($eventId).", '', null, '$hash')";

            if(!db_query($sql)) echo "Error: ".db_error()." <br />$sql<br />";
            $participantId = db_insert_id();

            ## Insert a response row for this survey and record
            //$returnCode = generateRandomHash();
            //$firstSubmitDate = "'".date('Y-m-d h:m:s')."'";

            $sql = "INSERT INTO redcap_surveys_response (participant_id, record, instance)
					VALUES ($participantId, '".prep($recordId)."','".prep($instance)."')";

            if(!db_query($sql)) echo "Error: ".db_error()." <br />$sql<br />";
            $responseId = db_insert_id();
        }
        ## Reset response status if it already exists
        else {
            $sql = "SELECT p.participant_id, p.hash, r.return_code, r.response_id, COALESCE(p.participant_email,'NULL') as participant_email
					FROM redcap_surveys_participants p, redcap_surveys_response r
					WHERE p.survey_id = '$surveyId'
						AND p.participant_id = r.participant_id
						AND r.record = '".prep($recordId)."'
						AND p.event_id = '".prep($eventId)."'";

            $q = db_query($sql);
            $rows = [];
            while($row = db_fetch_assoc($q)) {
                $rows[] = $row;
            }

            ## If more than one exists, delete any that are responses to public survey links
            if(db_num_rows($q) > 1) {
                foreach($rows as $thisRow) {
                    if($thisRow["participant_email"] == "NULL" && $thisRow["response_id"] != "") {
                        $sql = "DELETE FROM redcap_surveys_response
								WHERE response_id = ".$thisRow["response_id"];
                        if(!db_query($sql)) echo "Error: ".db_error()." <br />$sql<br />";
                    }
                    else {
                        $row = $thisRow;
                    }
                }
            }
            else {
                $row = $rows[0];
            }
            $returnCode = $row['return_code'];
            $hash = $row['hash'];
            $participantId = "";

            if($returnCode == "") {
                $returnCode = generateRandomHash();
            }

            ## If this is only as a public survey link, generate new participant row
            if($row["participant_email"] == "NULL") {
                $hash = self::generateUniqueRandomSurveyHash();

                ## Insert a participant row for this survey
                $sql = "INSERT INTO redcap_surveys_participants (survey_id, event_id, participant_email, participant_identifier, hash)
						VALUES ($surveyId,".prep($eventId).", '', null, '$hash')";

                if(!db_query($sql)) echo "Error: ".db_error()." <br />$sql<br />";
                $participantId = db_insert_id();
            }

            // Set the response as incomplete in the response table, update participantId if on public survey link
            $sql = "UPDATE redcap_surveys_participants p, redcap_surveys_response r
					SET r.completion_time = null,
						r.first_submit_time = '".date('Y-m-d h:m:s')."',
						r.return_code = '".prep($returnCode)."'".
                ($participantId == "" ? "" : ", r.participant_id = '$participantId'")."
					WHERE p.survey_id = $surveyId
						AND p.event_id = ".prep($eventId)."
						AND r.participant_id = p.participant_id
						AND r.record = '".prep($recordId)."'";
            db_query($sql);
        }

        // Set the response as incomplete in the data table
        $sql = "UPDATE redcap_data
				SET value = '0'
				WHERE project_id = ".prep($projectId)."
					AND record = '".prep($recordId)."'
					AND event_id = ".prep($eventId)."
					AND field_name = '{$surveyFormName}_complete'";

        $q = db_query($sql);
        // Log the event (if value changed)
        if ($q && db_affected_rows() > 0) {
            if(function_exists("log_event")) {
                \log_event($sql,"redcap_data","UPDATE",$recordId,"{$surveyFormName}_complete = '0'","Update record");
            }
            else {
                \Logging::logEvent($sql,"redcap_data","UPDATE",$recordId,"{$surveyFormName}_complete = '0'","Update record");
            }
        }

        @db_query("COMMIT");

        return array("hash" => $hash, "return_code" => $returnCode);
    }
}