<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	public function index()
	{
		$this->load->database();

		/*if(!empty($this->input->get("search"))){
			$this->db->like('id', $this->input->get("search"));
			$this->db->or_like('age', $this->input->get("search")); 
		}*/

		$this->db->limit(5, ($this->input->get("page",1) - 1) * 5);
		$query = $this->db->get("users");

		$data['users_data'] = $query->result();
		$data['users_total'] = $this->db->count_all("users");

		echo json_encode($data);
	}

	public function getCourseUsers()
	{
		$this->load->database();
		$this->load->model("query");
		$this->load->model("assignment/User_Model");
		
		
		
		$courseId = $this->input->get("courseId");

		/*if(!empty($this->input->get("search"))){
			$this->db->like('id', $this->input->get("search"));
			$this->db->or_like('age', $this->input->get("search")); 
		}*/

		$Users_Data = $this->User_Model->getAllUsersEdit($courseId,($this->input->get("page",1) - 1) * 5);
		//$Users_Data_Total = $this->users_Model->getAll();
		
		$data['users_data_Edit'] = $Users_Data;
		//$data['users_total_Edit'] = count($Users_Data_Total);
		
		echo json_encode($data);
	}
	
	public function getCourseUsersAttached()
	{
		$this->load->database();
		$this->load->model("query");
		$this->load->model("assignment/Courses_Users_Model");
		
		$courseId = $this->input->get("courseId");

		/*if(!empty($this->input->get("search"))){
			$this->db->like('id', $this->input->get("search"));
			$this->db->or_like('age', $this->input->get("search")); 
		}*/
		
		$f = array();
		$f['id'] = $courseId;
		$Users_Data = $this->Courses_Users_Model->getAll($f);
		
		$data['users_data_Attached'] = $Users_Data;

		echo json_encode($data);
	}

	public function store()
    {
		
    	$this->load->database();
    	$_POST = json_decode(file_get_contents('php://input'), true);
    	$insert = $this->input->post();
		$this->db->insert('courses', $insert);

		$id = $this->db->insert_id();
		$q = $this->db->get_where('courses', array('id' => $id));
		echo json_encode($q->row());
    }

    public function edit($id)
    {
    	$this->load->database();

		$q = $this->db->get_where('courses', array('id' => $id));
		echo json_encode($q->row());
    }

    public function update($id)
    {
    	$this->load->database();
    	$_POST = json_decode(file_get_contents('php://input'), true);

    	$insert = $this->input->post();
    	$this->db->where('id', $id);
    	$this->db->update('courses', $insert);

        $q = $this->db->get_where('courses', array('id' => $id));
		echo json_encode($q->row());
    }

    public function delete($id)
    {
    	$this->load->database();
        $this->db->where('id', $id);
		$this->db->delete('courses');
		echo json_encode(['success'=>true]);
    }
}
