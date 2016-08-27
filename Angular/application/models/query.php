<?php

class Query extends CI_Model {

  /* class attributes */
  public $dedu_id;

  // constructor
  function __construct()
  {
    parent::__construct();
  }

  /*
   * this method escapes the attributes before querying the database
   * to prevent sql injections
   */

  public function execute($query, $insert) {
  
  //echo $query." : ".$insert." = insert value<br/>";
    // create the query and execute it
    $query = $this->db->query($query);

    $data = array();
	
    if ($insert != '1') {
		if($query->num_rows())
		{
			foreach($query->result_array() as $key => $value) {
			$data[] = $value;
		}
      }
    }
    // return results
    return $data;
  }

}
?>