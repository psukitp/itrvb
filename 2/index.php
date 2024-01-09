<?php

class Product
{
    public $id;
    public $name;
    public $price;
    public $description;

    public function __construct($id, $name, $price, $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
    }
}

class DigitalProduct extends Product
{
    public function calculateCost()
    {
        return $this->price / 2;
    }
}

class PieceProduct extends Product
{
    public function calculateCost($pieces)
    {
        return $this->price * $pieces;
    }
}

class WeightProduct extends Product
{
    public $priceFor100g;

    public function __construct($id, $name, $price, $description, $priceFor100g)
    {
        parent::__construct($id, $name, $price, $description);
        $this->priceFor100g = $priceFor100g;
    }
    public function calculateCost($weightG)
    {
        return $this->price * ($weightG / 1000);
    }
}