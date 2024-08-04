<?php

namespace App\Traits;

use Illuminate\Support\Arr;
use ReflectionClass;

trait DtoTrait {
  public function fillDto(array $data) : void
  {
    $reflectionClass = new ReflectionClass($this);
    $properties = $reflectionClass->getProperties();
    $attribute = [];
    foreach($properties as $property) {
      $property->setAccessible(true);
      $this->{$property->getName()} = Arr::get($data, $property->getName());
    }
  }

  public function toArray(): array
  {
    $reflectionClass = new ReflectionClass($this);
    $properties = $reflectionClass->getProperties();
    $returnData = [];
    foreach($properties as $property) {
      $property->setAccessible(true);
      $returnData[$property->getName()] = $property->getValue($this);
    }
    return $returnData;
  }

  public function get($key): mixed
  {
    $reflectionClass = new ReflectionClass($this);
    if ($reflectionClass->hasProperty($key)) {
      $property = $reflectionClass->getProperty($key);
      $property->setAccessible(true);
      return $property->getValue($this);
    }
    return null;
  }
}