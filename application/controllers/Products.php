<?php
/**
 * Products Controller (CodeIgniter Style)
 */
class Products extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Product_model');
    }

    public function index() {
        $data['title'] = 'All Products (CodeIgniter Clone)';
        $data['products'] = $this->Product_model->get_products();

        $this->load->view('templates/header', $data);
        $this->load->view('products/index', $data);
        $this->load->view('templates/footer');
    }

    public function view(int $id = 0) {
        $data['product'] = $this->Product_model->get_products($id);
        if (empty($data['product'])) {
            $this->session->set_flashdata('error', 'Product not found.');
            redirect('products');
        }

        $data['title'] = 'Product Details: ' . $data['product']->name;
        $this->load->view('templates/header', $data);
        $this->load->view('products/view', $data);
        $this->load->view('templates/footer');
    }

    public function create() {
        $data['title'] = 'Add New Product';
        $this->load->view('templates/header', $data);
        $this->load->view('products/create', $data);
        $this->load->view('templates/footer');
    }

    public function store() {
        $name  = $this->input->post('name');
        $sku   = $this->input->post('sku');
        $price = $this->input->post('price');
        $stock = $this->input->post('stock');
        $description = $this->input->post('description');

        if (empty($name) || empty($sku) || $price === '') {
            $this->session->set_flashdata('error', 'Please fill in all required fields (Name, SKU, Price).');
            redirect('products/create');
        }

        $this->Product_model->insert_product([
            'name'        => $name,
            'sku'         => $sku,
            'price'       => (float)$price,
            'stock'       => (int)$stock,
            'description' => $description,
        ]);

        $this->session->set_flashdata('success', 'Product created successfully!');
        redirect('products');
    }

    public function edit(int $id = 0) {
        $data['product'] = $this->Product_model->get_products($id);
        if (empty($data['product'])) {
            $this->session->set_flashdata('error', 'Product not found.');
            redirect('products');
        }

        $data['title'] = 'Edit Product: ' . $data['product']->name;
        $this->load->view('templates/header', $data);
        $this->load->view('products/edit', $data);
        $this->load->view('templates/footer');
    }

    public function update(int $id = 0) {
        $product = $this->Product_model->get_products($id);
        if (!$product) {
            $this->session->set_flashdata('error', 'Product not found.');
            redirect('products');
        }

        $name  = $this->input->post('name');
        $sku   = $this->input->post('sku');
        $price = $this->input->post('price');
        $stock = $this->input->post('stock');
        $description = $this->input->post('description');

        if (empty($name) || empty($sku) || $price === '') {
            $this->session->set_flashdata('error', 'Please fill in all required fields.');
            redirect('products/edit/' . $id);
        }

        $this->Product_model->update_product($id, [
            'name'        => $name,
            'sku'         => $sku,
            'price'       => (float)$price,
            'stock'       => (int)$stock,
            'description' => $description,
        ]);

        $this->session->set_flashdata('success', 'Product updated successfully!');
        redirect('products');
    }

    public function delete(int $id = 0) {
        $product = $this->Product_model->get_products($id);
        if ($product) {
            $this->Product_model->delete_product($id);
            $this->session->set_flashdata('success', 'Product deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Product not found.');
        }
        redirect('products');
    }
}
