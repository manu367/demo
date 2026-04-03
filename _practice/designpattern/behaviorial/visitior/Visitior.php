<?php

interface Vistitor{
    function visitCircle(Circle $shape);
    function visitRectangle(Rectangle $rectangle);
    function visitCircleDetails();
}

interface Shape{
    function accept(Vistitor $vistitor);
}
class Rectangle implements Shape{
    private $width, $height;
    public function __construct($width, $height){
        $this->width = $width;
        $this->height = $height;
    }

    public function accept(Vistitor $vistitor)
    {
        $vistitor->visitior($this);
    }
    public function getArea(){
        return $this->width * $this->height;
    }
}
class Circle implements Shape{
    private $radius;
    public function __construct($radius){
        $this->radius = $radius;
    }
    public function accept(Vistitor $vistitor){
        $vistitor->visitior($this);
    }
    public function getRadius(){
        return $this->radius;
    }
}

class VisitiorConext implements Vistitor{
    private $circleRadius;
    function visitCircle(Circle $shape){
        echo $this->circleRadius=$shape->getRadius().PHP_EOL;
    }

    function visitRectangle(Rectangle $rectangle){
        echo $rectangle->getArea().PHP_EOL;
    }

    function visitCircleDetails()
    {
        echo "this is manu pathak";
    }
}

$shape=[new Circle(12),new Rectangle(20,25)];
$visitiorConext = new VisitiorConext();
$visitiorConext->visitCircle($shape[0]);
$visitiorConext->visitRectangle($shape[1]);
$visitiorConext->visitCircleDetails();