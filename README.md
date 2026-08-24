# CodeIgniter Clone (Ultra-Low Inode MVC)

A lightweight CodeIgniter 3/4 style MVC CRUD application crafted specifically for shared/free hosting environments (e.g. **InfinityFree**, **Aeon**, cPanel) where strict inode (file count) limits exist.

## 📊 Inode Stats
- **Total Files:** ~15 files
- **Total Inodes:** < 20
- **Standard CodeIgniter 3/4 Inodes:** 250 – 3,000+ files
- **Savings:** **99% fewer inodes**

## 🚀 Directory Structure
```text
codeigniter-clone/
├── application/
│   ├── config/
│   │   ├── config.php
│   │   ├── database.php
│   │   └── routes.php
│   ├── controllers/
│   │   └── Products.php
│   ├── models/
│   │   └── Product_model.php
│   └── views/
│       ├── templates/
│       │   ├── header.php
│       │   └── footer.php
│       └── products/
│           ├── index.php
│           ├── create.php
│           ├── edit.php
│           └── view.php
├── system/
│   └── core/
│       ├── CodeIgniter.php  (Core Router, Database PDO Active Record, Loader, Session)
│       └── Common.php       (site_url, base_url, redirect, flashdata)
├── index.php
└── .htaccess
```

## 🛠 Features & Conventions
- **Controllers:** Extends `CI_Controller` with `$this->load->view()`, `$this->load->model()`, `$this->input->post()`, `$this->session->set_flashdata()`.
- **Models:** Extends `CI_Model` with active record queries (`$this->db->get()`, `$this->db->insert()`, `$this->db->update()`, `$this->db->delete()`).
- **Database:** Auto-configured **SQLite** (0-setup) with instant toggle for **MySQL** in `application/config/database.php`.
- **Full CRUD:** Create, Read, View Details, Edit, Delete, with form validation & flash notifications.

## 💻 Local Testing
Run with PHP built-in server:
```bash
cd codeigniter-clone
php -S localhost:8000
```
Open `http://localhost:8000` in your browser.
