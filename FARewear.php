<?php
namespace Stanford\FARewear;

require_once "emLoggerTrait.php";

class FARewear extends \ExternalModules\AbstractExternalModule {

    use emLoggerTrait;

    public function __construct() {
		parent::__construct();
		// Other code to run when object is instantiated
	}

    public function ensureRecordExists($recordId){
        // Get the data from REDCap for the given record ID
        $existingData = \REDCap::getData('array', $recordId);
        if(empty($existingData[$recordId])) {
            // No record exists, so let's make a placeholder.
            $placeholderData = array(
                'record_id' => $recordId
            );

            $r = \REDCap::saveData(PROJECT_ID, 'json', json_encode(array($placeholderData)), "overwrite");
            $this->emDebug('Placeholder record created : $recordId');
            return $r;
        }

        $this->emDebug("Record $recordId already exists.");
        return null;
    }


    public function parseSave($incoming_data){
        $this->emDebug("parseSave() function");

        foreach($incoming_data as $record) {
            // Ensure that a main record exists before trying to save data into it
            // $this->ensureRecordExists($record['record_id']);

            $data = array(
                'record_id' => $record['record_id'],
                'redcap_repeat_instrument' => $record['redcap_repeat_instrument'],
                'redcap_repeat_instance' => $record['redcap_repeat_instance'],
                'timestamp' => $record['timestamp'],
                'h_command' => $record['h_command'],
                'total_patterns_played' => $record['total_patterns_played'],
                'current_pattern_num' => $record['current_pattern_num'],
                'duration_minutes' => $record['duration_minutes'],
                'duration_seconds' => $record['duration_seconds'],
                'start_device_type' => $record['start_device_type'],
                'stop_device_type' => $record['stop_device_type'],
                'start_connection_type' => $record['start_connection_type'],
                'stop_connection_type' => $record['stop_connection_type']
            );

            // Save the data for the repeating form
            $r = \REDCap::saveData(PROJECT_ID, 'json', json_encode(array($data)), "overwrite");
            $this->emDebug("Data saved to record " . $record['record_id'] , $data);
        }

        return json_encode($r);
    }
}
