<?php

namespace App\ExceptionResolver;


class ExceptionResolver
{
    /**
     * @var ExceptionMapping[]
     */
    private array $mappings = [];

    /**
     * @param array<class-string, array{code:int, hidden?:bool, loggable?:bool}> $mappings
     */
    public function __construct(array $mappings)
    {
        foreach ($mappings as $className => $mapping) {
            if (empty($mapping['code'])) {
                throw new \InvalidArgumentException('Code is mandatory for class ' . $className);
            }
            $this->addMapping($className, $mapping['code'], $mapping['hidden'] ?? true, $mapping['loggable'] ?? false);

        }
    }

    public function resolve(string $throwableClass): ?ExceptionMapping
    {
        $foundMapping = null;
        foreach ($this->mappings as $class => $mapping) {
            if ($throwableClass === $class || is_subclass_of($throwableClass, $class)) {
                $foundMapping = $mapping;
                break;
            }
        }

        return $foundMapping;
    }

    private function addMapping(string $class, int $code, bool $hidden, bool $loggable): void
    {
        $this->mappings[$class] = new ExceptionMapping($code, $hidden, $loggable);
    }
}