<?php

class BookingController {
    private $bookingModel;

    public function __construct() {
        $this->bookingModel = new Booking();
    }

    public function createBooking($data) {
        // Logic to create a new booking
        return $this->bookingModel->create($data);
    }

    public function getBooking($id) {
        // Logic to retrieve a booking by ID
        return $this->bookingModel->find($id);
    }

    public function updateBooking($id, $data) {
        // Logic to update an existing booking
        return $this->bookingModel->update($id, $data);
    }

    public function deleteBooking($id) {
        // Logic to delete a booking
        return $this->bookingModel->delete($id);
    }

    public function listBookings() {
        // Logic to list all bookings
        return $this->bookingModel->all();
    }
}