<?php 

class Core {
    public function run ($routes) {
        $url = '/';

        if (isset($_GET['url'])) {
            $url .= $_GET['url'];
        }

        if ($url != '/') {
            $url = rtrim($url, '/');
        }

        $routerFound = false;
        foreach ($routes as $path => $actions) {
            $exp = '#^' . preg_replace('/{id}/', '(\w+)', $path).'$#';

            if (preg_match($exp, $url, $matches)) {
                array_shift($matches);

                $routerFound = true;

                $actionVerbose = array();
                foreach ($actions as $action) {
                    if ($_SERVER['REQUEST_METHOD'] == $action[0]) {
                        $actionVerbose = $action;
                    }
                }

                if (empty($actionVerbose)) {
                    $routerFound = false;
                    break;
                }

                [$currentController, $method] = explode('@', $actionVerbose[1]);

                require_once __DIR__."/../controllers/$currentController.php";

                $newController = new $currentController();

                if (!empty($matches)) {
                    $newController->$method($matches[0]);
                } else {
                    $newController->$method();
                }
            }
        }

        if (!$routerFound) {
            require_once __DIR__."/../controllers/DefaultController.php";
            $defaultController = new DefaultController();
            $defaultController->notFound();
        }
    } 
}