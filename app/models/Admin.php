<?php

class Admin
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function login($email, $password)
    {
        $this->db->query('SELECT * FROM users where email = :email');
        // Bind values
        $this->db->bind(':email', $email);
        $row = $this->db->single();

        $hashed_password = $row->password;
        if (password_verify($password, $hashed_password)) {
            return $row;
        } else {
            return false;
        }
    }

    public function getUsers()
    {
        $sql = "SELECT userid, first_name, last_name, email, ";
        $sql .= "DATE_FORMAT(registration_date, '%M %d, %Y')";
        $sql .= " AS regdat FROM users WHERE user_level != 1 and status = 1 ORDER BY registration_date DESC";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getUsersLimit($start_from, $limitPerPage)
    {
        $sql = "SELECT userid, first_name, last_name, email, ";
        $sql .= "DATE_FORMAT(registration_date, '%M %d, %Y')";
        $sql .= " AS regdat FROM users WHERE user_level != 1 and status = 1 ORDER BY registration_date DESC";
        $sql .= " LIMIT :start, :limitPerPage";
        $this->db->query($sql);
        $this->db->bind(':start', $start_from);
        $this->db->bind(':limitPerPage', $limitPerPage);
        return $this->db->resultSet();
    }

    public function getProducts()
    {
        $this->db->query('SELECT * FROM products WHERE status = 1 ORDER BY productId DESC ');
        return $this->db->resultSet();
    }

    public function getProductsLimit($start_from, $limitPerPage)
    {

        $this->db->query('SELECT * FROM products WHERE status = 1 ORDER BY productId DESC LIMIT :start, :limitPerPage ');
        $this->db->bind(':start', $start_from);
        $this->db->bind(':limitPerPage', $limitPerPage);
        return $this->db->resultSet();
    }

    public function getProductOrderPrice($filter)
    {
        if ($filter == 'desc') {
            $this->db->query('SELECT * FROM products WHERE status = 1 ORDER BY price DESC');
        } else {
            $this->db->query('SELECT * FROM products WHERE status = 1 ORDER BY price ASC ');
        }
        return $this->db->resultSet();
    }

    public function getProductOrderPriceLimit($start_from, $limitPerPage, $filter)
    {
        if ($filter == 'desc') {
            $this->db->query('SELECT * FROM products WHERE status = 1 ORDER BY price DESC LIMIT :start, :limitPerPage');
        } else {
            $this->db->query('SELECT * FROM products WHERE status = 1 ORDER BY price ASC LIMIT :start, :limitPerPage');
        }
        $this->db->bind(':start', $start_from);
        $this->db->bind(':limitPerPage', $limitPerPage);
        return $this->db->resultSet();
    }

    public
    function getUserByEmail($email)
    {
        $this->db->query('SELECT * FROM users where email = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();
        if ($row) {
            return $row;
        } else {
            return false;
        }
    }

    public
    function getUserById($id)
    {
        $this->db->query('SELECT * FROM users where userid = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public
    function getProductById($id)
    {
        $this->db->query('SELECT * FROM products where productId = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public
    function addUser($data)
    {

        $sql = "INSERT INTO users (first_name, last_name, email, password, registration_date, user_level) values (:firstname, :lastname, :email, :password, NOW(), :user_level);";
        $this->db->query($sql);
        $this->db->bind(':firstname', $data['firstname']);
        $this->db->bind(':lastname', $data['lastname']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':user_level', $data['privilege']);
        // Execute

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }


    public
    function editUser($data)
    {
        $sql = "UPDATE users SET first_name = :firstname, last_name = :lastname ,user_level = :user_level WHERE userId = :userId";
        $this->db->query($sql);
        $this->db->bind(':firstname', $data['firstname']);
        $this->db->bind(':lastname', $data['lastname']);
        $this->db->bind(':userId', $data['userId']);
        $this->db->bind(':user_level', $data['privilege']);
        // Execute

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public
    function editProduct($data)
    {
//        echo '<pre>',
//        var_dump($data),
//        '</pre>';
//
//                die('1');
        $sql = "UPDATE products SET productName = :productName,productImage = :productImage, price = :price  WHERE productId = :productId";
        $this->db->query($sql);
        $this->db->bind(':productName', $data['productName']);
        $this->db->bind(':productImage', $data['productImage']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':productId', $data['productId']);
        // Execute

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public
    function deleteUser($idUser)
    {

        $sql = "UPDATE users SET status = :status WHERE userId = :userId";
        $this->db->query($sql);
        $this->db->bind(':status', '0');
        $this->db->bind(':userId', $idUser);
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public
    function deleteProduct($idProduct)
    {

        $sql = "UPDATE products SET status = :status WHERE productId = :productId";
        $this->db->query($sql);
        $this->db->bind(':status', '0');
        $this->db->bind(':productId', $idProduct);
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public
    function addProduct($data)
    {

        $sql = "INSERT INTO products (productName, productImage, price) values (:productName, :productImage, :price)";
        $this->db->query($sql);
        $this->db->bind(':productName', $data['productName']);
        $this->db->bind(':productImage', $data['image']);
        $this->db->bind(':price', $data['price']);

        // Execute

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

}