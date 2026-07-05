<?php

class Booking {
    private $db;
    private $table = 'bookings';

    public function __construct($database) {
        $this->db = $database;
    }

    public function createBooking($userId, $carId, $startDate, $endDate) {
        $query = "INSERT INTO " . $this->table . " (user_id, car_id, start_date, end_date) VALUES (:user_id, :car_id, :start_date, :end_date)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':car_id', $carId);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        return $stmt->execute();
    }

    public function getBookingsByUser($userId) {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBookingById($bookingId) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :booking_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':booking_id', $bookingId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function cancelBooking($bookingId) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :booking_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':booking_id', $bookingId);
        return $stmt->execute();
    }
}