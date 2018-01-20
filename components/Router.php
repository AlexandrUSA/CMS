<?php
class Router
{
	private $routes;
	public function __construct()
    {
        $routesPath = ROOT.'/config/routes.php';
        $this->routes = include($routesPath);
    }
    public function getURI() {
        if(!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }
    public function run() {
	    // Получили ури пользователя
	    $uri = $this->getURI();
        foreach ($this->routes as $uriPattern => $path) {
            // В цикле прошлись про нашим роутам в поисках совпадения
           if(preg_match("~$uriPattern~", $uri)) {
                // Заменяем рег. выражение в роутах на данные с uri
               $internalRoute = preg_replace("~$uriPattern~", $path, $uri);
                //Получаем массив всех арг-ов uri
               $arr = explode('/',  $internalRoute);
               //Определяем контроллер
               $controller = ucfirst(array_shift($arr) . 'Controller');
               // Метод контроллера
               $action = 'action' . ucfirst(array_shift($arr));
               // И список параметров для поиска по БД
               $parameters = $arr;
               // Ищем нужный контроллер
               $controllerFile = ROOT . '/controllers/' . $controller . '.php';
               if(file_exists($controllerFile)) {
                   include_once($controllerFile);
               }
               //Создаем эк-ляр класса нужного контроллера
                $controllerObject = new $controller;
               //И вызываем Нужный нам метод с массивом параметров
                $result = call_user_func_array([$controllerObject, $action], $parameters);
                if($result) {break;}
           }
       }
    }
}