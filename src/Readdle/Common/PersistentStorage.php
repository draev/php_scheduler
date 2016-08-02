<?php
namespace Readdle\Scheduler\Common;

class PersistentStorage
{
    const CACHE_PREFIX = 'php_scheduler';

    protected $functions;
    protected $appName;

    public function __construct(string $appName, callable $setFunc, callable $getFunc)
    {
        if (!is_callable($setFunc)) {
            throw new \Exception("Set function is not valid callback");
        }

        if (!is_callable($getFunc)) {
            throw new \Exception("Get function is not valid callback");
        }

        $this->functions['set'] = $setFunc;
        $this->functions['get'] = $getFunc;
        $this->appName = $appName;
    }

    protected function getNameSpacedKey(string $key)
    {
        return self::CACHE_PREFIX . ":{$this->appName}:{$key}";
    }

    public function save(string $key, string $value)
    {
        $this->functions['set']($this->getNameSpacedKey($key), $value);
    }

    public function get(string $key)
    {
        return $this->functions['get']($this->getNameSpacedKey($key));
    }
}