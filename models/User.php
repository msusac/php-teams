<?php
    class User 
    {
        // Properties
        public $id;
        public $username;
        public $password;
        public $fullname;
        public $email;
        public $created_at;
        public $authority;

        // Database Connection
        private $dbc;

        // Constructor with Database Connection
        public function __construct($dbc)
        {
            $this->dbc = $dbc;
        }

        // Select user by email
        public function select_by_email()
        {
            $query = "SELECT * FROM user_table 
                      WHERE email = '$this->email'";

            $stmt = $this->dbc->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        // Select user by ID
        public function select_by_id()
        {
            $query = "SELECT u.id AS id, u.username AS username, u.fullname AS fullname, 
                      u.email AS email, u.date_created AS created_at, a.name AS user_authority
                      FROM user_table u 
                      LEFT OUTER JOIN user_authority_table ua ON ua.user_id = u.id 
                      LEFT OUTER JOIN authority_table a ON a.id = ua.authority_id 
                      WHERE u.id = '$this->id'";

            $stmt = $this->dbc->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        // Select user by id that doesn't have any authority
        public function select_by_id_authority_null()
        {
            $query = "SELECT u.id AS id, a.name AS user_authority
                      FROM user_table u 
                      LEFT OUTER JOIN user_authority_table ua ON ua.user_id = u.id
                      LEFT OUTER JOIN authority_table a ON a.id = ua.authority_id 
                      WHERE u.id = '$this->id'";

            $stmt = $this->dbc->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        // Select user by params
        public function select_by_param($query_where_authority)
        {
            $query = "SELECT u.id as id, u.username AS username,
                      u.email AS email, a.name AS user_authority
                      FROM user_table u 
                      LEFT OUTER JOIN user_authority_table ua ON ua.user_id = u.id 
                      LEFT OUTER JOIN authority_table a ON a.id = ua.authority_id
                      WHERE username LIKE '%$this->username%' AND email LIKE '%$this->email%'
                      ".$query_where_authority."
                      ORDER BY username ASC";

            $stmt = $this->dbc->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        // Select user by username
        public function select_by_username()
        {
            $query = "SELECT u.id AS id, u.username AS username, u.fullname AS fullname, 
                      u.email AS email, u.date_created AS created_at, a.name AS user_authority
                      FROM user_table u 
                      LEFT OUTER JOIN user_authority_table ua ON ua.user_id = u.id 
                      LEFT OUTER JOIN authority_table a ON a.id = ua.authority_id 
                      WHERE u.username = '$this->username'";

            $stmt = $this->dbc->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        // Select user by username and password
        public function select_by_username_password()
        {
            $query = "SELECT u.id AS id, u.username AS username, a.name AS user_authority 
                      FROM user_table u
                      LEFT OUTER JOIN user_authority_table ua ON ua.user_id = u.id 
                      LEFT OUTER JOIN authority_table a ON a.id = ua.authority_id  
                      WHERE username = '$this->username' 
                      AND password = '$this->password'";

            $stmt = $this->dbc->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        // Select user fullname by ID
        public function select_fullname_by_id()
        {
            $query = "SELECT fullname FROM user_table
                      WHERE id = '$this->id'";
            
            $stmt = $this->dbc->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        // Insert user
        public function insert()
        {
            $query = "INSERT INTO user_table (username, email, password) 
                      VALUES ('$this->username', '$this->email', '$this->password')";

            $stmt = $this->dbc->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        // Insert user authority
        public function insert_authority_user()
        {
            $query = "INSERT INTO user_authority_table (user_id, authority_id) 
                      VALUES ($this->id, 2)";

            $stmt = $this->dbc->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        // Update user by ID
        public function update_by_id()
        {
            $query = "UPDATE user_table 
                      SET fullname = '$this->fullname',
                      password = '$this->password' 
                      WHERE id = '$this->id'";

            $stmt = $this->dbc->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        // Update user fullname only by ID
        public function update_fullname_by_id()
        {
            $query = "UPDATE user_table 
                      SET fullname = '$this->fullname' 
                      WHERE id = '$this->id'";

            $stmt = $this->dbc->prepare($query);
            $stmt->execute();
            return $stmt;
        }
    }
?>