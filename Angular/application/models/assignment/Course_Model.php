<?php
class Course_Model extends CI_Model {
  /* class attributes */
  public $id;
  public $course_name;

// constructor
function __construct()
{
  parent::__construct();
  $user_data = $this->session->all_userdata();
}

function escapeAttributes() {
  $this->id = $this->db->escape($this->id);
  $this->course_name = $this->db->escape($this->course_name);
}

public function add() {
  if(!empty($this->Ins_Time))
    $this->Ins_Time = $this->Ins_Time->format($this->config->item('date_format'));

  $this->escapeAttributes();
  $query = "Insert into Courses ( course_name) VALUES (".
    " $this->course_name)";

  return $this->db->query($query);
}


public function update($id) { 
  $id = $this->db->escape($id);
  $this->escapeAttributes();
  $query = "UPDATE Courses SET course_name = $this->course_name where id= $id";


  return $this->db->query($query);

}

 /* removes a record with a given id */
public function remove($id) {
  $id = $this->db->escape($id);
// create the query string
  $query = "DELETE FROM Courses WHERE id = $id";
  return $this->db->query($query);
}

public function getById($id) {
  $id = $this->db->escape($id);
  $query = $this->db->query("Select * From Courses WHERE id = $id");
  if ($query->num_rows() == 1) {
    $result = $query->result_array();
    $result = $result[0];
    $this->id= $result['id'];
    $this->course_name= $result['course_name'];


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
  $query = $this->db->query("Select * From Courses WHERE 1 = 1 " . $filter_query ." order by id desc");
  $Courses= array();
  if ($query->num_rows()) {
    foreach($query->result_array() as $key => $value) {
  if(!empty($value['Ins_Time']))
        $value['Ins_Time'] = DateTime::createFromFormat($this->config->item('date_format'), $value['Ins_Time']);
    $Courses[] = $value;
    }
  }
  return $Courses;
}
}
?>