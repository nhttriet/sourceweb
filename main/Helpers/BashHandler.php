<?php

require_once './main/Helpers/common.php';
require_once './main/Helpers/execHelpers.php';
require_once './main/Colors.php';
require_once './main/Http/Exceptions/AppException.php';

class BashHandler
{
    private $argv;
    private $colors;

    public function __construct()
    {
        global $argv;
        $this->argv = $argv;
        $this->colors = new \Main\Colors;
    }

    public function exec(): void
    {
        $this->_checkRequireAutoload();

        switch (strtolower($this->argv[1])) {
            case 'config:cache':
            case 'c:c':
            case 'c:f':
                execClearCache();
                execWriteCache();
                execWriteConfigCache();
                execWriteDataViews();
                break;
            case 'db:seed':
                execRunSeed();
                break;
            case 'migrate':
                execMigrate();
                break;
            case 'ser':
            case 'serve':
            case 'serv':
                execCreateServerCli($this->argv);
                break;
            case 'key:':
            case 'key:generate':
            case 'key:gen':
                execGenerateKey();
                break;
            case 'make:controller':
                if (!isset($this->argv[2])) {
                    throw new Main\Http\Exceptions\AppException($this->colors->printError("Missing param controller's name.\nPlease using format >midun make:controller {ControllerName}<"));
                }
                $name = $this->argv[2];
                execMakeController($name);
                break;
            case 'make:model':
                if (!isset($this->argv[2])) {
                    throw new Main\Http\Exceptions\AppException($this->colors->printError("Missing param model's name.\nPlease using format >midun make:model {ModelName}<"));
                }
                $name = $this->argv[2];
                execMakeModel($name);
                break;
            default:
                throw new Main\Http\Exceptions\AppException($this->colors->printError("Bash {$this->argv[1]} is not supported."));
        }
    }

    private function _checkRequireAutoload(): void
    {
        if (isset($this->argv[1])) {
            if (
                strtolower($this->argv[1]) != 'config:cache'
                && strtolower($this->argv[1]) != 'c:f'
                && strtolower($this->argv[1]) != 'c:c'
                && strtolower($this->argv[1]) != 'key:'
            ) {
                require_once './main/Autoload.php';
                /**
                 * Miduner - A PHP Framework For Amateur
                 *
                 * @package  Miduner
                 * @author   Dang Anh <danganh.dev@gmail.com>
                 */
                $config = require_once './cache/app.php';
                new Autoload($config);

            }
        }
    }
}