<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Courses extends CI_Controller {

	/*
	 * get courses
	 */
	public function index()
	{
		$this->load->database();
		$courseSearchTerm = $this->input->get("search");
		if(!empty($courseSearchTerm)){
			$this->db->like('id', $courseSearchTerm);
			$this->db->or_like('course_name', $courseSearchTerm); 
		}

		$query = $this->db->get("courses");

		$data['data'] = $query->result();
		$data['total'] = $this->db->count_all("courses");

		echo json_encode($data);
	}

	/*
	 * store course and its users
	 */
	public function store()
    {		
    	$this->load->database();
    	$_POST = json_decode(file_get_contents('php://input'), true);
		
		$course['course_name'] = $_POST['course_name']; 
    	//$insert = $this->input->post();
		$this->db->insert('courses', $course);
		
		$id = $this->db->insert_id();
		$courseCreated = $this->db->get_where('courses', array('id' => $id));
		
		//get course users
		$users = $_POST['Users'];
		$data_Array = array();
		//insert each courseUser in courses_Users table
		foreach($users as $k=>$v)
		{
			$data_Array['id'] = $id;
			$data_Array['user_id'] = $v;
			
			$this->db->insert('courses_users', $data_Array);
		}
		echo json_encode($courseCreated->row());
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
		$this->load->model('assignment/Courses_Users_Model');
		$_POST = json_decode(file_get_contents('php://input'), true);
		$Users = $_POST['Users'];
		$Course['course_name'] = $_POST['course_name']; 
		
		$data_Array = array();
		
    	$this->db->where('id', $id);
    	$this->db->update('courses', $Course);
		
		$f = array();
		$f['id'] = $id;
		$Users_In_DB_Array = $this->Courses_Users_Model->getAll($f);
		$Users_In_DB = array();
		foreach($Users_In_DB_Array as $k=>$v)
		{
			$Users_In_DB[] = $v['user_id'];
		}
		
		$results_To_Remove = array_diff($Users_In_DB, $Users);
		
		foreach($results_To_Remove as $k=>$v)
		{
			$data_Array['id'] = $id;
			$data_Array['user_id'] = $v;
			
			$this->db->where('id', $id);
			$this->db->where('user_id', $v);
			
			$this->db->delete('courses_Users');
		}
		
		$results_To_Add = array_diff($Users, $Users_In_DB);
		
		foreach($results_To_Add as $k=>$v)
		{
			$data_Array['id'] = $id;
			$data_Array['user_id'] = $v;
			
			$this->db->insert('courses_Users', $data_Array);
		}
		
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
