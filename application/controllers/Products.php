<?php
class Products extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->library('session');
        if (!$this->session->userdata('user_id')) { redirect('login'); }
    }
        public function index() {
        $q = $this->input->get('q') ?? '';
        $sort = $this->input->get('sort') ?? 'id';
        $dir = $this->input->get('dir') ?? 'desc';
        $trash = $this->input->get('trash') ? true : false;
        
        $this->db->order_by('products.'.$sort, $dir);
        
        if ($trash) { $this->db->where('products.deleted_at IS NOT NULL'); } 
        else { $this->db->where('products.deleted_at IS NULL'); }

        if ($this->session->userdata('role') === 'admin') {
            $this->db->select('products.*, users.email as creator_email');
            $this->db->join('users', 'users.id = products.user_id', 'left');
        } else {
            $this->db->where('products.user_id', $this->session->userdata('user_id'));
        }

        if (!empty($q)) {
            $this->db->group_start()->like('products.name', $q)->or_like('products.sku', $q)->or_like('products.description', $q)->group_end();
        }
        $data['products'] = $this->db->get('products')->result();
        $data['q'] = $q; $data['sort'] = $sort; $data['dir'] = $dir; $data['trash'] = $trash;
        $data['title'] = 'Products (CI)';
        
        $this->load->view('templates/header', $data);
        $this->load->view('products/index', $data);
        $this->load->view('templates/footer');
    }
        public function export() {
        $q = $this->input->get('q') ?? '';
        $format = $this->input->get('format') ?? 'csv';
        $trash = $this->input->get('trash') ? true : false;
        
        $this->db->order_by('products.id', 'desc');
        
        if ($trash) { $this->db->where('products.deleted_at IS NOT NULL'); } 
        else { $this->db->where('products.deleted_at IS NULL'); }

        if ($this->session->userdata('role') === 'admin') {
            $this->db->select('products.*, users.email as creator_email');
            $this->db->join('users', 'users.id = products.user_id', 'left');
        } else {
            $this->db->where('products.user_id', $this->session->userdata('user_id'));
        }

        if (!empty($q)) {
            $this->db->group_start()->like('products.name', $q)->or_like('products.sku', $q)->or_like('products.description', $q)->group_end();
        }
        $products = $this->db->get('products')->result_array();
        
        if ($format === 'json') {
            header('Content-Type: application/json');
            header('Content-Disposition: attachment; filename="products.json"');
            echo json_encode($products, JSON_PRETTY_PRINT); exit;
        }
        if ($format === 'csv') {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="products.csv"');
            $output = fopen('php://output', 'w');
            fputcsv($output, ['ID', 'User ID', 'Name', 'SKU', 'Description', 'Price', 'Stock']);
            foreach ($products as $row) {
                fputcsv($output, [$row['id'], $row['user_id'] ?? '', $row['name'], $row['sku'], $row['description'], $row['price'], $row['stock']]);
            }
            fclose($output); exit;
        }
    }
    public function create() {
        $this->load->view('templates/header', ['title' => 'Add Product']);
        $this->load->view('products/create');
        $this->load->view('templates/footer');
    }
    public function store() {
        $this->Product_model->insert_product([
            'user_id'     => $this->session->userdata('user_id'),
            'name'        => $this->input->post('name'),
            'sku'         => $this->input->post('sku'),
            'price'       => (float)$this->input->post('price'),
            'stock'       => (int)$this->input->post('stock'),
            'description' => $this->input->post('description')
        ]);
        $this->session->set_flashdata('success', 'Product created!');
        redirect('products');
    }
        public function delete($id) {
        $prod = $this->Product_model->get_products($id);
        if ($prod && ($this->session->userdata('role') === 'admin' || $prod->user_id == $this->session->userdata('user_id'))) {
            $this->db->where('id', $id)->update('products', ['deleted_at' => date('Y-m-d H:i:s')]);
        }
        redirect('products');
    }
    public function force_delete($id) {
        $prod = $this->Product_model->get_products($id);
        if ($prod && ($this->session->userdata('role') === 'admin' || $prod->user_id == $this->session->userdata('user_id'))) {
            $this->Product_model->delete_product($id);
        }
        redirect('products?trash=1');
    }
    public function restore($id) {
        $prod = $this->Product_model->get_products($id);
        if ($prod && ($this->session->userdata('role') === 'admin' || $prod->user_id == $this->session->userdata('user_id'))) {
            $this->db->where('id', $id)->update('products', ['deleted_at' => NULL]);
        }
        redirect('products?trash=1');
    }
    public function toggle_active($id) {
        $prod = $this->Product_model->get_products($id);
        if ($prod && ($this->session->userdata('role') === 'admin' || $prod->user_id == $this->session->userdata('user_id'))) {
            $newStatus = $prod->is_active ? 0 : 1;
            $this->db->where('id', $id)->update('products', ['is_active' => $newStatus]);
        }
        redirect('products');
    }
}
