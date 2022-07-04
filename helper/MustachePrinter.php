<?php

require_once('third-party/mustache/src/Mustache/Autoloader.php');

class MustachePrinter {

    private $mustache;
    private $viewPath;

    public function __construct($viewPath){
        $this->viewPath = $viewPath;
        Mustache_Autoloader::register();
        $this->mustache = new Mustache_Engine(
            [
                'partials_loader' => new Mustache_Loader_FilesystemLoader( $viewPath )
            ]);
    }

    public function generateView($template , $data = []){
        $contentAsString =  file_get_contents($this->viewPath . "/" .$template);

        $data['logged'] = Session::isSessionActive();
        $data['firstname'] = Session::getNickname();

        echo  $this->mustache->render($contentAsString, $data);
    }

    public function generateTemplatedStringForPDF($template , $data = []){
        $contentAsString =  file_get_contents($this->viewPath . "/" .$template);

        return $this->mustache->render($contentAsString, $data);
    }
}