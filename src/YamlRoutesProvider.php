<?php

namespace malotor\YamlRoutesProvider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use malotor\ConfigProvider\YamlFileParser;

class YamlRoutesProvider implements ServiceProviderInterface
{

    private $configFile;


    public function __construct($configFile)
    {
        $this->configFile = $configFile;
    }


    public function register(Container $pimple)
    {
        /*
        $parser = new YamlFileParser();

        $pimple['config'] = $parser->parse($this->configFile, $pimple);
        */

    }
}
