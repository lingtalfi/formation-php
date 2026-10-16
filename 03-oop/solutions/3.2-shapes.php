<?php
declare(strict_types=1);

abstract class Shape
{
    abstract public function area(): float;

    public function describe(): string
    {
        return static::class . ' with area ' . round($this->area(), 2);
    }
}

class Circle extends Shape
{
    public function __construct(private float $radius) {}

    public function area(): float
    {
        return M_PI * $this->radius ** 2;
    }
}

class Rectangle extends Shape
{
    public function __construct(protected float $width, protected float $height) {}

    public function area(): float
    {
        return $this->width * $this->height;
    }
}

class Square extends Rectangle
{
    public function __construct(float $side)
    {
        parent::__construct($side, $side);   // reuse the parent's constructor
    }
}

$shapes = [new Circle(5), new Rectangle(4, 6), new Square(3)];

foreach ($shapes as $shape) {
    echo $shape->describe() . PHP_EOL;   // polymorphism: each area() is different
}

echo '--- sorted by area' . PHP_EOL;
usort($shapes, fn(Shape $a, Shape $b) => $a->area() <=> $b->area());   // <=> returns -1, 0 or 1
foreach ($shapes as $shape) {
    echo $shape->describe() . PHP_EOL;
}
