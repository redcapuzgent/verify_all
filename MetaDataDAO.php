<?php

/**
 * Description of DataValue
 *
 * @author lveeckha
 */
class MetaDataDAO {
        /**
     * @var resource
     */
    private $conn;

    /**
     *
     * @param resource $conn
     */
    public function __construct($conn)
    {
        if ($conn === null) throw new Exception("Connection cannot be null");
        $this->conn = $conn;
    }
    
    /**
     * 
     * @param int $projectid
     * @param string $instrument_name
     * @return string[]
     */
    public function getFieldNames($projectid, $instrument)
    {
        $sql = "SELECT field_name FROM redcap_metadata WHERE project_id=" . $projectid . " AND form_name='" . $instrument . "'";
        $startTreatmentResult = mysqli_query($this->conn, $sql);
        $queryResult = mysqli_fetch_assoc($startTreatmentResult);
        $names = [];
        while ($queryResult !== null) {
            $names[] = $queryResult["field_name"];
            $queryResult = mysqli_fetch_assoc($startTreatmentResult);
        }
        mysqli_free_result($startTreatmentResult);
        return $names;
    }
    
}
