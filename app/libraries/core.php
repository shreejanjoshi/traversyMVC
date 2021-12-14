<?php

// app core class
// create url and load core controller
// url format -/controller/method/params

class Core{
    protected $currentController = "Pages";
    protected $currentMethod = "index";
    protected $params = [];

    public function __construct()
    {
        //print_r($this->getUrl());

        $url = $this->getUrl();

        //look in controller for first value
        //even we wrote code in Core.php we are still inside index.php
        //ucwords capitalized first letter
        if(isset($url[0]) && file_exists('../app/controllers/' . ucwords($url[0]). '.php')){
            //in url post. If post file is there then set as controller
            //overwrite pages to post 0
            $this->currentController = ucwords($url[0]);

            //unset 0 index
            unset($url[0]);
        }

        //require the controller
        require_once '../app/controllers/' .$this->currentController. '.php';

        //instantiate controller class
        $this->currentController = new $this->currentController;

        //check for second part ofurl
        if(isset($url[1])){
            //check if method exits in url

            if(method_exists($this->currentController, $url[1])){
                $this->currentMethod = $url[1];

                //uset 1 index
                unset($url[1]);
            }
        }

        //get params
        // if there is parameters then it will add if not it is empty
        $this->params = $url ? array_values($url) : [];

        // call a callback with arrray of params
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function getUrl(){
        if(isset($_GET['url'])){
            // to split url and push it in array ..posts/edit/1 [post,edit,1]
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
    }
}