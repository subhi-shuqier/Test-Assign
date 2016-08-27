<?php
class User_Model extends CI_Model {
  /* class attributes */
  public $user_id;
  public $name;
  public $age;
  public $email;

// constructor
function __construct()
{
  parent::__construct();
}

function escapeAttributes() {
  $this->user_id = $this->db->escape($this->user_id);
  $this->name = $this->db->escape($this->name);
  $this->age = $this->db->escape($this->age);
  $this->email = $this->db->escape($this->email);
}

public function add() {
  if(!empty($this->Ins_Time))
    $this->Ins_Time = $this->Ins_Time->format($this->config->item('date_format'));

  $this->escapeAttributes();
  $query = "Insert into Users ( name,age,email) VALUES (".
    " $this->name, $this->age, $this->email)";

  return $this->db->query($query);
}


public function update($id) { 
  $id = $this->db->escape($id);
  $this->escapeAttributes();
  $query = "UPDATE Users SET name = $this->name, age = $this->age, email = $this->email where user_id= $id";


  return $this->db->query($query);

}

 /* removes a record with a given id */
public function remove($id) {
  $id = $this->db->escape($id);
// create the query string
  $query = "DELETE FROM Users WHERE user_id = $id";
  return $this->db->query($query);
}

public function getById($id) {
  $id = $this->db->escape($id);
  $query = $this->db->query("Select * From Users WHERE user_id = $id");
  if ($query->num_rows() == 1) {
    $result = $query->result_array();
    $result = $result[0];
    $this->user_id= $result['user_id'];
    $this->name= $result['name'];
    $this->age= $result['age'];
    $this->email= $result['email'];
    

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
  $query = $this->db->query("Select * From Users WHERE 1 = 1 " . $filter_query ." order by user_id desc");
  $Users= array();
  if ($query->num_rows()) {
    foreach($query->result_array() as $key => $value) {
    $Users[] = $value;
    }
  }
  return $Users;
}

public function getAllUsersEdit($id,$limit) {
  $id = $id;
  
  $query = $this->db->query("Select 
						*
						,'1' as y
					From 
						Users 
					WHERE 
						user_id in (select user_id from courses_users where id = $id)
					Union
					Select
						* 
						,'0' as y
					From 
						Users
					WHERE 
						user_id not in (select user_id from courses_users where id = $id)
					limit 5 offset ".$limit." ");
					
  $Users= array();
  if ($query->num_rows()) {
    foreach($query->result_array() as $key => $value) {
  if(!empty($value['Ins_Time']))
        $value['Ins_Time'] = DateTime::createFromFormat($this->config->item('date_format'), $value['Ins_Time']);
    $Users[] = $value;
    }
  }

  return $Users;
}

}
?>