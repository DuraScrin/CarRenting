<?php

class CarController {
    private $carModel;

    public function __construct($carModel) {
        $this->carModel = $carModel;
    }

    public function listCars() {
        $cars = $this->carModel->getAllCars();
        include '../views/cars.php';
    }

    public function viewCar($id) {
        $car = $this->carModel->getCarById($id);
        include '../views/car_detail.php';
    }
}