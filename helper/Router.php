<?php

class Router {
    private $configuration;
    private $defaultController;
    private $defaultMethod;
    private $validController;

    public function __construct($configuration, $defaultController, $defaultMethod) {
        $this->configuration = $configuration;
        $this->defaultController = $defaultController;
        $this->defaultMethod = $defaultMethod;
        $this->validController = ['login', 'signin','flight_plan', 'ticket', 'credit'];
    }

    public function executeMethodFromController($controllerName, $methodName) {
        if(!Session::isSessionActive() && !$this->isValidController($controllerName)){
            $controllerName = 'login';
        }
        $controller = $this->getControllerFrom($controllerName);
        $method = $this->getValidMethod($controller, $methodName, $this->defaultMethod);
        call_user_func([$controller,$method]);
    }

    private function getControllerFrom($page) {
        $controllerName = $this->createMethodName($page);
        $validController = $this->getValidMethod($this->configuration,$controllerName, $this->defaultController);
        return $this->createController($validController);
    }

    private function getValidMethod($class, $method, $defaultMethod)  {
        return method_exists($class, $method) ? $method : $defaultMethod;
    }

    private function createMethodName($page) {
        return 'get' . ucfirst($page) . 'Controller';
    }
    private function createController($validController) {
        return call_user_func([$this->configuration, $validController]);
    }

    private function isValidController($controllerName){
        return in_array($controllerName, $this->validController);
    }
}