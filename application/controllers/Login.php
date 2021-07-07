<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
	}

	public function index()
	{
		if ($this->session->userdata('logged_in') !== TRUE) {
			$this->load->view('login');
		} else {
			redirect('admin/home');
		}
	}

	public function register()
	{
		$this->load->view('register');
	}
	public function register_save()
	{
		$nama    = $this->input->post('nama', TRUE);
		$username    = $this->input->post('username', TRUE);
		$email    = $this->input->post('email', TRUE);
		$password = md5($this->input->post('password', TRUE));
		$role = $this->input->post('role', TRUE);
		if (!isset($role)) {
			$role = 'member';
		}
		$data = [$nama, $username, $email, $password, $role];
		$res = $this->user_model->save($data);
		echo $this->session->set_flashdata('msg', array('success', 'User berhasil ditambahkan, silakan login!'));
		redirect('login');
	}

	public function auth()
	{
		$email    = $this->input->post('email', TRUE);
		$password = md5($this->input->post('password', TRUE));
		$data = $this->user_model->validate($email, $password)->row();
		if (isset($data)) {
			$sesdata = array(
				'username'  => $data->username,
				'email'     => $data->email,
				'role'     => $data->role,
				'id'     => $data->id,
				'created_at'     => $data->created_at,
				'logged_in' => TRUE
			);
			$this->session->set_userdata($sesdata);
			$data->last_login = date("Y-m-d H:i:s");
			$res = $this->user_model->update($data);
			// access login for admin
			if ($data->role === 'admin') {
				echo $this->session->set_flashdata('msg', array('success', 'Selamat datang, ' . $data->nama));
				redirect('admin/home');
				// access login for member
			} else {
				redirect('home/member');
			}
		} else {
			echo $this->session->set_flashdata('msg', array('error', 'Username / Password Salah'));
			redirect('login');
		}
	}

	function logout()
	{
		$this->session->sess_destroy();
		redirect('login');
	}
}
