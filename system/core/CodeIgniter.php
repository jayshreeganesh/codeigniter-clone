<?php
/**
 * CodeIgniter Clone Core Kernel (Single-File System Engine)
 * Ultra-low Inode MVC Framework
 */

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

require_once __DIR__ . '/Common.php';

class CI_Benchmark {
    public function elapsed_time() { return '0.015'; }
}

class CI_Input {
    public function post($key = null, $default = null) {
        if ($key === null) return $_POST;
        return isset($_POST[$key]) ? (is_string($_POST[$key]) ? trim($_POST[$key]) : $_POST[$key]) : $default;
    }
    public function get($key = null, $default = null) {
        if ($key === null) return $_GET;
        return isset($_GET[$key]) ? (is_string($_GET[$key]) ? trim($_GET[$key]) : $_GET[$key]) : $default;
    }
    public function server($key) {
        return $_SERVER[$key] ?? null;
    }
}

class CI_Session {
    public function set_flashdata($key, $value) {
        $_SESSION['__ci_flash'][$key] = $value;
    }
    public function flashdata($key = null) {
        if ($key === null) return $_SESSION['__ci_flash'] ?? [];
        $val = $_SESSION['__ci_flash'][$key] ?? null;
        if ($val !== null) {
            unset($_SESSION['__ci_flash'][$key]);
        }
        return $val;
    }
    public function set_userdata($key, $value) {
        $_SESSION[$key] = $value;
    }
    public function userdata($key = null) {
        return $key === null ? $_SESSION : ($_SESSION[$key] ?? null);
    }
}

class CI_DB {
    protected static ?PDO $pdo = null;
    protected string $table = '';
    protected array $wheres = [];
    protected array $bindings = [];
    protected string $orderBy = '';
    protected ?int $limit = null;
    protected ?int $offset = null;

    public function __construct() {
        if (self::$pdo === null) {
            $db_config = require APPPATH . 'config/database.php';
            $driver = $db_config['driver'] ?? 'sqlite';
            
            if ($driver === 'sqlite') {
                $dbPath = $db_config['sqlite_path'] ?? (APPPATH . 'database.sqlite');
                $dir = dirname($dbPath);
                if (!is_dir($dir)) mkdir($dir, 0777, true);
                self::$pdo = new PDO('sqlite:' . $dbPath);
            } else {
                $dsn = "mysql:host={$db_config['hostname']};port={$db_config['port']};dbname={$db_config['database']};charset=utf8mb4";
                self::$pdo = new PDO($dsn, $db_config['username'], $db_config['password']);
            }
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $this->auto_migrate();
        }
    }

    private function auto_migrate() {
        self::$pdo->exec("CREATE TABLE IF NOT EXISTS products (
            id INTEGER PRIMARY KEY " . (self::$pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === 'sqlite' ? 'AUTOINCREMENT' : 'AUTO_INCREMENT') . ",
            name VARCHAR(255) NOT NULL,
            sku VARCHAR(100) NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            stock INT NOT NULL DEFAULT 0,
            description TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
    }

    public function query(string $sql, array $params = []) {
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function get($table = null, $limit = null, $offset = null) {
        if ($table) $this->table = $table;
        if ($limit) $this->limit = (int)$limit;
        if ($offset) $this->offset = (int)$offset;

        $sql = "SELECT * FROM {$this->table}";
        if (!empty($this->wheres)) {
            $sql .= " WHERE " . implode(" AND ", $this->wheres);
        }
        if ($this->orderBy) {
            $sql .= " ORDER BY " . $this->orderBy;
        }
        if ($this->limit !== null) {
            $sql .= " LIMIT " . $this->limit;
            if ($this->offset !== null) $sql .= " OFFSET " . $this->offset;
        }

        $stmt = $this->query($sql, $this->bindings);
        $this->reset();
        return new class($stmt) {
            private $stmt;
            public function __construct($stmt) { $this->stmt = $stmt; }
            public function result() { return $this->stmt->fetchAll(); }
            public function result_array() { return $this->stmt->fetchAll(PDO::FETCH_ASSOC); }
            public function row() { return $this->stmt->fetch(); }
            public function num_rows() { return $this->stmt->rowCount(); }
        };
    }

    public function get_where($table, $where = [], $limit = null, $offset = null) {
        $this->table = $table;
        $this->where($where);
        return $this->get(null, $limit, $offset);
    }

    public function where($key, $val = null) {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->wheres[] = "{$k} = ?";
                $this->bindings[] = $v;
            }
        } else {
            $this->wheres[] = "{$key} = ?";
            $this->bindings[] = $val;
        }
        return $this;
    }

    public function order_by($column, $direction = 'ASC') {
        $this->orderBy = "{$column} {$direction}";
        return $this;
    }

    public function insert($table, array $data) {
        $cols = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        $sql = "INSERT INTO {$table} ({$cols}) VALUES ({$placeholders})";
        $this->query($sql, array_values($data));
        return self::$pdo->lastInsertId();
    }

    public function update($table, array $data, $where = []) {
        if (!empty($where)) $this->where($where);
        $set = implode(", ", array_map(fn($k) => "{$k} = ?", array_keys($data)));
        $sql = "UPDATE {$table} SET {$set}";
        if (!empty($this->wheres)) {
            $sql .= " WHERE " . implode(" AND ", $this->wheres);
        }
        $params = array_merge(array_values($data), $this->bindings);
        $this->query($sql, $params);
        $this->reset();
        return true;
    }

    public function delete($table, $where = []) {
        if (!empty($where)) $this->where($where);
        $sql = "DELETE FROM {$table}";
        if (!empty($this->wheres)) {
            $sql .= " WHERE " . implode(" AND ", $this->wheres);
        }
        $this->query($sql, $this->bindings);
        $this->reset();
        return true;
    }

    private function reset() {
        $this->wheres = [];
        $this->bindings = [];
        $this->orderBy = '';
        $this->limit = null;
        $this->offset = null;
    }
}

class CI_Loader {
    protected CI_Controller $controller;

    public function __construct(CI_Controller $controller) {
        $this->controller = $controller;
    }

    public function view(string $view, array $data = [], bool $return = false) {
        $filePath = APPPATH . 'views/' . ltrim($view, '/') . '.php';
        if (!file_exists($filePath)) {
            throw new Exception("View not found: " . $filePath);
        }
        extract($data);
        if ($return) ob_start();
        include $filePath;
        if ($return) return ob_get_clean();
    }

    public function model(string $model, ?string $name = null) {
        $name = $name ?: $model;
        $modelClass = ucfirst($model);
        $filePath = APPPATH . 'models/' . $modelClass . '.php';
        if (file_exists($filePath)) {
            require_once $filePath;
            $this->controller->$name = new $modelClass();
        }
    }

    public function database() {
        $this->controller->db = new CI_DB();
    }
}

#[\AllowDynamicProperties]
class CI_Controller {
    private static ?CI_Controller $instance = null;
    public CI_Loader $load;
    public CI_Input $input;
    public CI_Session $session;
    public ?CI_DB $db = null;

    public function __construct() {
        self::$instance = $this;
        $this->load = new CI_Loader($this);
        $this->input = new CI_Input();
        $this->session = new CI_Session();
        $this->db = new CI_DB();
    }

    public static function &get_instance(): CI_Controller {
        return self::$instance;
    }
}

class CI_Model {
    public function __get($key) {
        $ci = &get_instance();
        return $ci->$key;
    }
}

class CodeIgniter {
    public static function run() {
        $routes = require APPPATH . 'config/routes.php';
        
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $scriptDir = dirname($scriptName);
        
        // Strip base folder if running in subfolder
        $path = parse_url($requestUri, PHP_URL_PATH);
        if ($scriptDir !== '/' && $scriptDir !== '\\' && str_starts_with($path, $scriptDir)) {
            $path = substr($path, strlen($scriptDir));
        }
        $path = trim($path, '/');
        if ($path === '' || $path === 'index.php') {
            $path = $routes['default_controller'] ?? 'welcome';
        }

        // Check custom routes
        foreach ($routes as $routePattern => $destination) {
            if ($routePattern === 'default_controller' || $routePattern === '404_override') continue;
            $pattern = '#^' . str_replace([':num', ':any'], ['([0-9]+)', '([^/]+)'], $routePattern) . '$#';
            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches);
                $path = $destination;
                foreach ($matches as $i => $match) {
                    $path = str_replace('$' . ($i + 1), $match, $path);
                }
                break;
            }
        }

        $segments = explode('/', $path);
        $controllerName = ucfirst(array_shift($segments) ?: 'Products');
        $method = array_shift($segments) ?: 'index';
        $params = $segments;

        $controllerFile = APPPATH . 'controllers/' . $controllerName . '.php';
        if (!file_exists($controllerFile)) {
            http_response_code(404);
            echo "<h1>404 Page Not Found</h1><p>Controller {$controllerName} does not exist.</p>";
            return;
        }

        require_once $controllerFile;
        if (!class_exists($controllerName)) {
            http_response_code(404);
            echo "<h1>404 Page Not Found</h1><p>Class {$controllerName} not found.</p>";
            return;
        }

        $controller = new $controllerName();
        if (!method_exists($controller, $method)) {
            http_response_code(404);
            echo "<h1>404 Page Not Found</h1><p>Action {$method} not found in {$controllerName}.</p>";
            return;
        }

        call_user_func_array([$controller, $method], $params);
    }
}
