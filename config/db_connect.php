<?php
    class DatabaseConnection 
    {
        // Database Connection Params
        private $hostname = 'localhost';
        private $db_name = 'php_teams_db';
        private $username = 'root';
        private $password = '';

        private $dbc;

        // Database Connection Function
        public function connect(){
            $this->dbc = null;

            try
            {
                $this->dbc = new PDO('mysql:host=' . $this->hostname . ';' . 'dbname=' . $this->db_name, $this->username, $this->password);
                $this->dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch(PDOException $e)
            {
                echo 'Connection Error: ' . $e->getMessage();
            }

            return $this->dbc;
        }
    }
?>