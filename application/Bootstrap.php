<?php
class Bootstrap extends Yaf_Bootstrap_Abstract{
    private $_config;
    private $_memcache;

    public function _initConfig() {
        $this->_config = Yaf_Application::app()->getConfig();
        Yaf_Registry::set('config', $this->_config);
        $php_config_path = Yaf_Application::app()->getConfig()->application->arrayconfig;
        $php_config = Config::getConfig('php_config',$php_config_path);
        Yaf_Registry::set('phpconfig', $php_config);
    }
    public function _initRequest() {
        Yaf_Registry::set('request', new RequestValidator());
    }
    public function _initMemcaches(){
        $cacheconfig = explode("|",$this->_config->application->memcacheconfig);
        $memcache = new Memcache;
        foreach($cacheconfig as $cache){
            list($host,$port) = explode(":",$cache);
            $memcache->addServer($host, $port);
        }
        $this->_memcache = $memcache;
        Yaf_Registry::set('memcache', $memcache);
    }

    public function _initMemcacheSession(){
        $expire_time = $this->_config->session_expire;
        new SessionHandler($this->_memcache,$expire_time);
        session_start();
    }

    public function _initErrors(){
        if($this->_config->application->showErrors){
            ini_set('display_errors',"On");
            ini_set('error_reporting',E_ALL);
        }else{
            error_reporting (0);
            ini_set('display_errors','Off');
        }
    }


    public function _initPlugin(Yaf_Dispatcher $dispatcher) {
        //注册一个插件
        $AutoloadPlugin = new AutoloadPlugin();
        $dispatcher->registerPlugin($AutoloadPlugin);
    }
    public function _initRoute(Yaf_Dispatcher $dispatcher) {
        $router = $dispatcher->getRouter();
        $route = new Yaf_Route_Simple("m", "c", "a");
        $router->addRoute("myroute", $route); 


        $router = Yaf_Dispatcher::getInstance()->getRouter();
        $router->addConfig(Yaf_Registry::get("config")->routes);
        $route  = new Yaf_Route_Rewrite(
            "/product/list/:id/:name",
            array(
                "controller" => "product",
                "action"     => "info",
            )
        );

        $router->addRoute('dummy', $route);
    }
    public function _initView(Yaf_Dispatcher $dispatcher){
        $request = $dispatcher->getRequest();
        $uri = $request->getRequestUri();
        if (($request->getParam('c') == 'home' && $uri == '/index.php') || $uri == '/') {
            return '';
        }
        $config = Yaf_Registry::get('config');
        $view = new Smarty_Adapter(null, $config->application->smarty);

        Yaf_Dispatcher::getInstance()->setView($view);
    }
}
