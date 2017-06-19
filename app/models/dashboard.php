<?php
  class Dashboard
  {
    private $conn;
    function __construct()
    {
      require_once '../helpers/dbConnection.php';
      $db = new DbConnection();
      $this->conn = $db->connect();
    }
    function getAdminsLog()
    {
      $RESULT = array();
      $stmt = $this->conn->prepare("SELECT Admin.Admin_username AS name, Admin_Log.The_Actions, Admin_Log.Mac, Admin_Log.Cont_id, Admin_Log.Action_time FROM Admin_Log INNER JOIN Admin on Admin_Log.Admin_id = Admin.Admin_id ORDER BY Admin_Log.Action_time");
      $stmt->execute();
      $stmt->store_result();
      for ($i = 0; $i < $stmt->num_rows; $i++)
      {
        $Metadata = $stmt->result_metadata();
        $PARAMS = array();
        while ($Field = $Metadata->fetch_field())
        {
          $PARAMS[] = &$RESULT[ $i ][ $Field->name ];
        }
        call_user_func_array(array( $stmt, 'bind_result'), $PARAMS );
        $stmt->fetch();
      }
      return $RESULT;
    }
    function getRpSpecs()
  	{
      $RESULT = array();
      $stmt = $this->conn->prepare("SELECT Current_Specs.Mac, Rp_Log.Jobs_Num, Rp_Specs.Location, Current_Specs.Temperature,Current_Specs.CpuUsage, Current_Specs.FreeStorage FROM Rp_Specs INNER JOIN Current_Specs on Rp_Specs.Mac = Current_Specs.Mac INNER JOIN Rp_Log on Rp_Log.Mac = Rp_Specs.Mac ");
      $stmt->execute();
  		$stmt->store_result();
  		for ( $i = 0; $i < $stmt->num_rows; $i++ )
  		{
  			$Metadata = $stmt->result_metadata();
  			$PARAMS = array();
  			while ( $Field = $Metadata->fetch_field() )
  			{
  				$PARAMS[] = &$RESULT[ $i ][ $Field->name ];
  			}
  			call_user_func_array( array( $stmt, 'bind_result' ), $PARAMS );
  			$stmt->fetch();
  		}
  		return $RESULT;
  	}
    function getRunningProcesses()
  	{
  		$RESULT = array();
      $stmt = $this->conn->prepare ("SELECT Rp_Specs.Mac, COUNT(Process.Img_id) AS procs FROM Rp_Specs INNER JOIN Process ON Rp_Specs.Mac = Process.Mac GROUP BY Process.Mac");
      $stmt->execute();
  		$stmt->store_result();
  		for ( $i = 0; $i < $stmt->num_rows; $i++ )
  		{
  			$Metadata = $stmt->result_metadata();
  			$PARAMS = array();
  			while ($Field = $Metadata->fetch_field())
  			{
  				$PARAMS[] = &$RESULT[ $i ][ $Field->name ];
  			}
  			call_user_func_array( array( $stmt, 'bind_result' ), $PARAMS );
  			$stmt->fetch();
  		}
  		return $RESULT;
  	}
  }
