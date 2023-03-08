<?php

class ApiDocGenerator
{

    private $routes;

    public function __construct()
    {
        $this->routes = require __DIR__ . '/../config/routes.php';
    }

    public function generate()
    {
        $contents = '<h1>Basic API Docucmentation</h1><br/>';
        foreach($this->routes as $pattern => $target){
            $parts = explode(' ', $pattern);
            $method = strtoupper($parts[0]);
            $path = '/'. str_replace('(:num)', '{id}', $parts[1]);
            $contents .= "<div><strong>$method</strong> $path</div>";
            if(array_key_exists('apidoc', $target)){
                if(array_key_exists('description', $target['apidoc'])){
                    $description = $target['apidoc']['description'];
                    $contents .= "<p>$description</p>";
                }

                if(array_key_exists('param', $target['apidoc'])){
                    $param = $target['apidoc']['param'];
                    $contents .= "<p><strong>param:</strong> $param</p>";
                }
            }
            $contents .= '<hr/>';
        }

        if (!is_dir('docs')) mkdir('docs');
        $doc = fopen('docs/index.html', 'w');
        fwrite($doc, $contents);
        fclose($doc);

        echo "[OK] The API documentation generated successfully. See http://127.0.0.1/docs/index.html";

    }
}
