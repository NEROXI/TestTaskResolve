<?php

namespace Root\Html;

require_once __DIR__ . '/../../vendor/autoload.php';

use Symfony\Component\VarDumper\VarDumper;

class SomeObject {
    protected string $name;
    public function __construct(string $name) {
        $this->name = $name;
    }
    public function getObjectName() {
        return $this->name;
    }
}

interface ObjectHandlerInterface {
    public function handle(SomeObject $object): void;
    public function isSupport(SomeObject $object): bool;
}

class Object1Handler implements ObjectHandlerInterface {
    public function handle(SomeObject $object): void
    {
        VarDumper::dump("Handling object 1");
    }

    public function isSupport(SomeObject $object): bool
    {
        return $object->getObjectName() === 'object_1';
    }
}

class Object2Handler implements ObjectHandlerInterface {
    public function handle(SomeObject $object): void {
        VarDumper::dump("Handling object 2");
    }

    public function isSupport(SomeObject $object): bool
    {
        return $object->getObjectName() === 'object_2';
    }
}

class SomeObjectsHandler {
    protected $handlers = [];

    public function __construct(ObjectHandlerInterface ...$handlers) {
        $this->handlers = $handlers;
    }

    public function handleObjects(array $objects): void {
        foreach ($objects as $object) {
            foreach ($this->handlers as $handler) {
                if ($handler->isSupport($object)) {
                    $handler->handle($object);
                }
            }
        }
    }
}

$objects = [
    new SomeObject('object_1'),
    new SomeObject('object_2')
];

$soh = new SomeObjectsHandler(new Object1Handler(), new Object2Handler());
$soh->handleObjects($objects);

