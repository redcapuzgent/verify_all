<?php

namespace uzgent\VerifyClass;

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
        $sql = "SELECT field_name FROM redcap_metadata WHERE project_id=? AND form_name=?";
        $prepared = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($prepared, "is", $projectid, $instrument);
        mysqli_stmt_execute($prepared);
        if (mysqli_stmt_error($prepared) != "")
        {
            throw new Exception("Unable to execute query " . mysqli_stmt_error($prepared) . " $sql");
        }
        $result = mysqli_stmt_get_result($prepared);
        $queryResult = mysqli_fetch_assoc($result);
        $names = [];
        while ($queryResult !== null) {
            $names[] = $queryResult["field_name"];
            $queryResult = mysqli_fetch_assoc($result);
        }
        return $names;
    }
    
}
