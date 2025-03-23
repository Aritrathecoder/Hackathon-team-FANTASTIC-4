<?php
// Database configuration (aligned with connect.php)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ui";

// Create connection class with error handling
class Database {
    private $conn;
    
    public function __construct($servername, $username, $password, $dbname) {
        try {
            $this->conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            // Set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            die();
        }
    }
    
    public function getConnection() {
        return $this->conn;
    }
}

// User model class to handle CRUD operations
class User {
    private $conn;
    private $table_name = "users";
    
    // User properties (added username and password)
    public $id;
    public $name;
    public $email;
    public $username;
    public $password;
    public $created_at;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Create a new user
    public function create() {
        try {
            // Insert query (updated to include username and password)
            $query = "INSERT INTO " . $this->table_name . " (name, email, username, password, created_at) VALUES (:name, :email, :username, :password, :created_at)";
            
            // Prepare statement
            $stmt = $this->conn->prepare($query);
            
            // Sanitize and bind values
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->username = htmlspecialchars(strip_tags($this->username));
            $this->password = password_hash($this->password, PASSWORD_DEFAULT); // Hash the password
            $this->created_at = date('Y-m-d H:i:s');
            
            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":username", $this->username);
            $stmt->bindParam(":password", $this->password);
            $stmt->bindParam(":created_at", $this->created_at);
            
            // Execute query
            if($stmt->execute()) {
                $this->id = $this->conn->lastInsertId();
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    // Read all users
    public function readAll() {
        try {
            // Select query
            $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
            
            // Prepare statement
            $stmt = $this->conn->prepare($query);
            
            // Execute query
            $stmt->execute();
            
            return $stmt;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    // Read one user
    public function readOne() {
        try {
            // Select query
            $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";
            
            // Prepare statement
            $stmt = $this->conn->prepare($query);
            
            // Bind parameters
            $stmt->bindParam(":id", $this->id);
            
            // Execute query
            $stmt->execute();
            
            // Fetch data
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($row) {
                $this->name = $row['name'];
                $this->email = $row['email'];
                $this->username = $row['username'];
                $this->password = $row['password'];
                $this->created_at = $row['created_at'];
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    // Update user
    public function update() {
        try {
            // Update query (updated to include username and password)
            $query = "UPDATE " . $this->table_name . " SET name = :name, email = :email, username = :username, password = :password WHERE id = :id";
            
            // Prepare statement
            $stmt = $this->conn->prepare($query);
            
            // Sanitize input
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->username = htmlspecialchars(strip_tags($this->username));
            $this->password = password_hash($this->password, PASSWORD_DEFAULT); // Hash the password
            $this->id = htmlspecialchars(strip_tags($this->id));
            
            // Bind parameters
            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":username", $this->username);
            $stmt->bindParam(":password", $this->password);
            $stmt->bindParam(":id", $this->id);
            
            // Execute query
            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    // Delete user
    public function delete() {
        try {
            // Delete query
            $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
            
            // Prepare statement
            $stmt = $this->conn->prepare($query);
            
            // Sanitize input
            $this->id = htmlspecialchars(strip_tags($this->id));
            
            // Bind parameter
            $stmt->bindParam(":id", $this->id);
            
            // Execute query
            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    // Validate login (new method for login page)
    public function validateLogin($username, $password) {
        try {
            // Select query to find user by username
            $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username LIMIT 0,1";
            
            // Prepare statement
            $stmt = $this->conn->prepare($query);
            
            // Bind parameters
            $stmt->bindParam(":username", $username);
            
            // Execute query
            $stmt->execute();
            
            // Fetch data
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($row && password_verify($password, $row['password'])) {
                $this->id = $row['id'];
                $this->name = $row['name'] ?? null;
                $this->email = $row['email'] ?? null;
                $this->username = $row['username'];
                $this->password = $row['password'];
                $this->created_at = $row['created_at'] ?? null;
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}

// Initialize database connection
$database = new Database($servername, $username, $password, $dbname);
$db = $database->getConnection();
$user = new User($db);

// API Router - Simple routing based on request method and parameters
$request_method = $_SERVER["REQUEST_METHOD"];

switch($request_method) {
    case 'GET':
        if(!empty($_GET["id"])) {
            $user->id = $_GET["id"];
            if($user->readOne()) {
                $user_data = array(
                    "id" => $user->id,
                    "name" => $user->name,
                    "email" => $user->email,
                    "username" => $user->username,
                    "created_at" => $user->created_at
                );
                echo json_encode($user_data);
            } else {
                // Set response code - 404 Not found
                http_response_code(404);
                echo json_encode(array("message" => "User not found."));
            }
        } else {
            $stmt = $user->readAll();
            $users_arr = array();
            
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $user_item = array(
                    "id" => $id,
                    "name" => $name,
                    "email" => $email,
                    "username" => $username,
                    "created_at" => $created_at
                );
                array_push($users_arr, $user_item);
            }
            echo json_encode($users_arr);
        }
        break;
        
    case 'POST':
        // Get posted data
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->name) && !empty($data->email) && !empty($data->username) && !empty($data->password)) {
            $user->name = $data->name;
            $user->email = $data->email;
            $user->username = $data->username;
            $user->password = $data->password;
            
            if($user->create()) {
                // Set response code - 201 created
                http_response_code(201);
                echo json_encode(array("message" => "User was created.", "id" => $user->id));
            } else {
                // Set response code - 503 service unavailable
                http_response_code(503);
                echo json_encode(array("message" => "Unable to create user."));
            }
        } else {
            // Set response code - 400 bad request
            http_response_code(400);
            echo json_encode(array("message" => "Unable to create user. Data is incomplete."));
        }
        break;
        
    case 'PUT':
        // Get posted data
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->id) && !empty($data->name) && !empty($data->email) && !empty($data->username) && !empty($data->password)) {
            $user->id = $data->id;
            $user->name = $data->name;
            $user->email = $data->email;
            $user->username = $data->username;
            $user->password = $data->password;
            
            if($user->update()) {
                // Set response code - 200 ok
                http_response_code(200);
                echo json_encode(array("message" => "User was updated."));
            } else {
                // Set response code - 503 service unavailable
                http_response_code(503);
                echo json_encode(array("message" => "Unable to update user."));
            }
        } else {
            // Set response code - 400 bad request
            http_response_code(400);
            echo json_encode(array("message" => "Unable to update user. Data is incomplete."));
        }
        break;
        
    case 'DELETE':
        // Get posted data
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->id)) {
            $user->id = $data->id;
            
            if($user->delete()) {
                // Set response code - 200 ok
                http_response_code(200);
                echo json_encode(array("message" => "User was deleted."));
            } else {
                // Set response code - 503 service unavailable
                http_response_code(503);
                echo json_encode(array("message" => "Unable to delete user."));
            }
        } else {
            // Set response code - 400 bad request
            http_response_code(400);
            echo json_encode(array("message" => "Unable to delete user. No ID provided."));
        }
        break;
        
    default:
        // Invalid request method
        http_response_code(405);
        echo json_encode(array("message" => "Method not allowed."));
        break;
}
?>