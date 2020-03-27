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
                echo "</pre>";
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

            $viewAsSurveyIndex = array_keys($randomizedForms, $nextInstrument)[0];
            if (!empty($randomizedForms) && !empty($recordRandom[$index]) && $nextInstrument != "") {
                $recordData = \Records::getData($project_id, 'array', array($record), array($instrument . "_complete", $nextInstrument . '_complete'));
                if ($Proj->forms[$nextInstrument]['survey_id'] != "" && $viewSurveys[$viewAsSurveyIndex] == "yes" && ($recordData[$record][$event_id][$instrument . '_complete'] == "2" || $recordData[$record]['repeat_instances'][$event_id][$instrument][$repeat_instance][$instrument . '_complete'] == "2")) {
                    if ($recordData[$record][$event_id][$nextInstrument . '_complete'] != "" || $recordData[$record]['repeat_instances'][$event_id][$nextInstrument][$repeat_instance][$nextInstrument . '_complete'] != "") {
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
        echo "Trying to go to: $redirectURL<br/>";
        echo "Record Data:<br/>";
        echo "<pre>";
        print_r($recordData);
        echo "</pre>";
        echo "Record: $record<br/>";
        echo "Event: $event_id<br/>";
        echo "First check: ".$recordData[$record][$event_id][$instrument.'_complete']."<br/>";
        echo "Second check: ".$recordData[$record]['repeat_instances'][$event_id][$instrument][$repeat_instance][$instrument.'_complete']."<br/>";
        $intakeForm = $this->getProjectSetting('intake-form');
        $randomizedForms = $this->getProjectSetting('cb-forms');
        $viewSurveys = $this->getProjectSetting('survey-view');
        $randomizeSetting = json_decode($this->getProjectSetting('randomization'),true);
        echo "Intake: $intakeForm<br/>";
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
        exit;
        $this->setProjectSetting("randomization-$record-$event_id", json_encode($recordRandom), $project_id);
        if ($redirectURL != "") {
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