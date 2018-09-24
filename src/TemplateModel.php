<?php

namespace Bechwebb\TemplateModels;

use Closure;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

abstract class TemplateModel
{
    public function getTemplate($template)
    {
        $data = $this->items();

        extract($data);

        include(get_template_directory().$template);
    }

    protected function items()
    {
        $class = new ReflectionClass($this);

        $publicProperties = $this->mapWithKeys($class->getProperties(ReflectionProperty::IS_PUBLIC), function (ReflectionProperty $property) {
            return [$property->getName() => $this->{$property->getName()}];
        });

        return $publicProperties;
    }

    public function mapWithKeys($items, callable $callback)
    {
        $result = [];

        foreach ($items as $key => $value) {
            $assoc = $callback($value, $key);

            foreach ($assoc as $mapKey => $mapValue) {
                $result[$mapKey] = $mapValue;
            }
        }

        return $result;
    }
}
