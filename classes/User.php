<?php
class User {
    private $id;
    private $name;
    private $email;
    private $phone;
    private $address;
    private $role;

    public function __construct($id, $name, $email, $phone, $address, $role) {
        $this->id = $id;
        $this->name = htmlspecialchars($name);
        $this->email = htmlspecialchars($email);
        $this->phone = htmlspecialchars($phone);
        $this->address = htmlspecialchars($address);
        $this->role = $role;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getRole() {
        return $this->role;
    }

    public function isAdmin() {
        return $this->role === 'admin';
    }
}
?>
