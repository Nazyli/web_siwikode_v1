<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
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
		$this->load->model('profesi_model');
		if ($this->session->userdata('logged_in') !== TRUE) {
			echo $this->session->set_flashdata('msg', array('warning', 'Anda tidak memiliki akses!, silakan login'));
			redirect('login');
		}
		$this->load->model('wisata_model');
		$this->load->model('testimoni_model');
		$this->load->model('gallery_wisata_model');
	}

	public function index()
	{
		$data['gallery'] = $this->gallery_wisata_model->getAll();
		$data['rekreasi'] = $this->wisata_model->getAllRekreasi();
		$data['kuliner'] = $this->wisata_model->getAllKuliner();
		$data['testimoni'] = $this->testimoni_model->getAll();
		$data['new_testimoni'] = $this->testimoni_model->findAllLimit(3);
		if ($this->session->userdata('role') === 'admin') {
			$this->load->view("admin/layout/header");
			$this->load->view("admin/dashboard", $data);
			$this->load->view("admin/layout/footer");
		} else {
			$data['err'] = array('403', '403 Forbidden');
			$this->load->view("errors/error_app", $data);
		}
	}

	function member(){
		//Allowing akses to member only
		if($this->session->userdata('role')==='member'){
			$this->load->view("admin/layout/header");
			$this->load->view("admin/member");
			$this->load->view("admin/layout/footer");
		}else{
			$data['err'] = array('403', '403 Forbidden');
			$this->load->view("errors/error_app", $data);
		}
	  }
}
