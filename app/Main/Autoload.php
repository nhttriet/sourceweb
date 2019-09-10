<?php

use App\Http\Exceptions\Exception;

class Autoload
{
    private $root;
    private $autoload;
    private $server;

    public function __construct($config)
    {
        $this->root = $config['appurl'];
        $this->autoload = $config['autoload'];
        $this->autoloadFile();
        $this->server = $config['server'];
        spl_autoload_register([$this, 'load']);
    }

    public function load($class)
    {
        switch ($this->server) {
            case 'linux':
                $file = $this->root . '/' . str_replace('\\', '/', lcfirst($class) . '.php');
                break;
            case 'windows':
                $file = $this->root . '\\' . $class . '.php';
                break;
        }
        if (file_exists($file)) {
            require_once $file;
        } else {
            throw new Exception("Class $class doesn't exists");
        }
    }

    private function autoloadFile()
    {
        try {
            foreach ($this->defaultFile() as $file) {
                require_once $this->root . '/' . $file;
            }
        } catch (\Throwable $th) {
            toPre($th->getMessage());
        }

    }

    private function defaultFile()
    {
        return $this->autoload;
    }
}