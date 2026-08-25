<?php
class Auth extends CI_Controller {
    public function __construct() { parent::__construct(); $this->load->library('session'); $this->load->database(); }
    public function login() {
        if ($this->input->method() === 'post') {
            $email = $this->input->post('email');
            $user = $this->db->where('email', $email)->get('users')->row();
            if ($user && password_verify($this->input->post('password'), $user->password)) {
                $this->session->set_userdata(['user_id' => $user->id, 'user_name' => $user->name, 'role' => $user->role]);
                redirect('products');
            }
            $this->session->set_flashdata('error', 'Invalid credentials');
            redirect('login');
        }
        $this->load->view('templates/header', ['title' => 'Login']);
        $this->load->view('auth/login');
        $this->load->view('templates/footer');
    }
    public function register() {
        if ($this->input->method() === 'post') {
            $data = [
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
                'role' => 'user'
            ];
            $this->db->insert('users', $data);
            $this->session->set_userdata(['user_id' => $this->db->insert_id(), 'user_name' => $data['name'], 'role' => 'user']);
            redirect('products');
        }
        $this->load->view('templates/header', ['title' => 'Register']);
        $this->load->view('auth/register');
        $this->load->view('templates/footer');
    }
    public function logout() { $this->session->sess_destroy(); redirect('login'); }
}