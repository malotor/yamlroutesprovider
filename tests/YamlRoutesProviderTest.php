<?php

namespace malotor\ConfigProvider\tests;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

use malotor\YamlRoutesProvider\YamlRoutesProvider;

use Silex\Application;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\HttpKernel\HttpKernelInterface;


class YamlRoutesProviderTest extends TestCase
{

    protected $app;
    protected $client;

    /**
     * @test
     */
    public function it_could_load_routes_from_a_yaml_file()
    {

        $routes_yml =
            <<<YAML
# config/routes.yml
test:
  path: /test
  defaults: { _controller: 'test_controller:index' }
  methods: [GET]
YAML;



        $filesystem = vfsStream::setup("root",null, [
            'routes.yml' => $routes_yml
        ]);


        $configFilePath = $filesystem->url("root") . '/routes.yml';

        $app = new Application();
        $this->app = $app;

        $app->register(new YamlRoutesProvider($configFilePath), array());

        $app['test_controller'] =  new TestController();

        $this->client = $this->createClient();

        $this->doRequest("/test");

        $this->assertTrue(($app['test_controller'])->hasBeenCalled());

    }


    /**
     * Creates a Client.
     *
     * @param array $server Server parameters
     *
     * @return Client A Client instance
     */
    public function createClient(array $server = array())
    {
        if (!class_exists('Symfony\Component\BrowserKit\Client')) {
            throw new \LogicException('Component "symfony/browser-kit" is required by WebTestCase.'.PHP_EOL.'Run composer require symfony/browser-kit');
        }

        return new Client($this->app, $server);
    }


    public function doRequest($url,$method = 'GET',$data = [] )
    {
        $this->client = $this->createClient($this->parameters);
        $crawler = $this->client->request($method, $url, $data);
    }
}


class TestController
{
    private $called = false;

    public function index()
    {
        $this->called = true;
    }

    public function hasBeenCalled()
    {
        return $this->called;
    }

}