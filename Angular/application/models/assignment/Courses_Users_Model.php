<?php
class Courses_Users_Model extends CI_Model {
  /* class attributes */
  public $id;
  public $user_id;

// constructor
function __construct()
{
  parent::__construct();
}

function escapeAttributes() {
  $this->id = $this->db->escape($this->id);
  $this->user_id = $this->db->escape($this->user_id);
}

public function add() {
  if(!empty($this->Ins_Time))
    $this->Ins_Time = $this->Ins_Time->format($this->config->item('date_format'));

  $this->escapeAttributes();
  $query = "Insert into Courses_Users ( id, user_id) VALUES (".
    " $this->id, $this->user_id)";

  return $this->db->query($query);
}


public function update($id,$user_id) { 
  $id = $this->db->escape($id);
  $user_id = $this->db->escape($user_id);
  $this->escapeAttributes();
  $query = "UPDATE Courses_Users SET user_id = $this->user_id where id= $id and user_id= $user_id ";


  return $this->db->query($query);

}

 /* removes a record with a given id */
public function remove($id,$user_id) {
  $id = $this->db->escape($id);
  $user_id = $this->db->escape($user_id);
// create the query string
  $query = "DELETE FROM Courses_Users WHERE id = $id and user_id = $user_id";
  return $this->db->query($query);
}

public function getByid($id,$user_id) {
  $id = $this->db->escape($id);
  $user_id = $this->db->escape($user_id);
  $query = $this->db->query("Select * From Courses_Users WHERE id = $id and user_id = $user_id");
  if ($query->num_rows() == 1) {
    $result = $query->result_array();
    $result = $result[0];
    $this->id= $result['id'];
    $this->user_id= $result['user_id'];


    return true;
  }
  else { 
    return false;
  }
}

public function getAll($filters) {
  $filter_query ="";
  foreach($filters as $k => $v) {
    if(isset($v) && !empty($v) && trim($v) != "")
      $filter_query .= ' AND ' . $k . '=' . $this->db->escape($v);
  }
  $query = $this->db->query("Select user_id From Courses_Users WHERE 1 = 1 " . $filter_query ." order by id desc");
  $Courses_Users= array();
  if ($query->num_rows()) {
    foreach($query->result_array() as $key => $value) {
  if(!empty($value['Ins_Time']))
        $value['Ins_Time'] = DateTime::createFromFormat($this->config->item('date_format'), $value['Ins_Time']);
    $Courses_Users[] = $value;
    }
  }
  return $Courses_Users;
}
}
?>