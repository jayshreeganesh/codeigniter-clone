<?php
/**
 * Product Model
 */
class Product_model extends CI_Model {
    protected string $table = 'products';

    public function get_products(?int $id = null) {
        if ($id === null) {
            return $this->db->order_by('id', 'DESC')->get($this->table)->result();
        }
        return $this->db->where('id', $id)->get($this->table)->row();
    }

    public function insert_product(array $data) {
        return $this->db->insert($this->table, [
            'user_id'     => $data['user_id'] ?? 1,
            'name'        => $data['name'],
            'sku'         => $data['sku'],
            'price'       => $data['price'],
            'stock'       => $data['stock'] ?? 0,
            'description' => $data['description'] ?? '',
        ]);
    }

    public function update_product(int $id, array $data) {
        return $this->db->update($this->table, [
            'user_id'     => $data['user_id'] ?? 1,
            'name'        => $data['name'],
            'sku'         => $data['sku'],
            'price'       => $data['price'],
            'stock'       => $data['stock'] ?? 0,
            'description' => $data['description'] ?? '',
        ], ['id' => $id]);
    }

    public function delete_product(int $id) {
        return $this->db->delete($this->table, ['id' => $id]);
    }
}
