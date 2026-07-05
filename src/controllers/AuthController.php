<?php

class AuthController {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function login($username, $password) {
        // Logic for user login
        // Validate user credentials and start session
    }

    public function register($username, $password, $email) {
        // Logic for user registration
        // Insert new user into the database
    }

    public function logout() {
        // Logic for user logout
        // Destroy session and redirect to login
    }

    public function isAuthenticated() {
        // Check if user is authenticated
        // Return true or false
    }
}