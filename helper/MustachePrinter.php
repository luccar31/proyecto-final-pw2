<?php

class MustachePrinter {

    private $mustache;
    private $viewPath;
    private $session;

    public function __construct($viewPath, $session){
        $this->viewPath = $viewPath;
        Mustache_Autoloader::register();
        $this->mustache = new Mustache_Engine(
            [
                'partials_loader' => new Mustache_Loader_FilesystemLoader( $viewPath )
            ]);
        $this->session = $session;
    }

    public function generateView($template , $data = []){
        $contentAsString =  file_get_contents($this->viewPath . "/" .$template);

        $data['logged'] = $this->session->isSessionActive();

        echo  $this->mustache->render($contentAsString, $data);
    }
}