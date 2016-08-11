**General scheduler project**

- Run tests inside docker container `docker-compose up`

**Example usage**

```php
<?php

include "./vendor/autoload.php";

// Example of scheduled script
class SomeScript
{
    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function __invoke()
    {
        echo time() . " - {$this->name}" . PHP_EOL;
    }
}

// Mock of persistent storage. In production we reccomend to use redis storage
class Storage
{
    protected $keyValue = [];

    public function set($key, $value)
    {
        $this->keyValue[$key] = $value;
    }

    public function get($key)
    {
        return array_key_exists($key, $this->keyValue) ? $this->keyValue[$key] : 0;
    }
}

$storage = new Storage();

// Create scheduler object
$scheduler = new \Readdle\Scheduler\Scheduler(
    new \Readdle\Scheduler\PersistentStorage(
        'test',
        [$storage, 'set'],
        [$storage, 'get']
    )
);

// Register your scripts
$scheduler->register(10, new SomeScript('10 second'));
$scheduler->register(20, new SomeScript('20 second'));
$scheduler->register(7, new SomeScript('7 second'));
$scheduler->register(3, new SomeScript('3 second'));
$scheduler->register(27, new SomeScript('27 second'));

// Start scheduler loop
$scheduler->loop();
```
