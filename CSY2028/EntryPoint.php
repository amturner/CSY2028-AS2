<?php
namespace CSY2028;
class EntryPoint {
    private $routes;

    public function __construct(Routes $routes) {
        $this->routes = $routes;
    }

    public function run() {
        $route = ltrim(explode('?', $_SERVER['REQUEST_URI'])[0], '/');

        if ($route != '' && $route[strlen($route)-1] == '/')
            header('Location: /' . rtrim($route, '/'));

		$method = $_SERVER['REQUEST_METHOD'];

		$routes = $this->routes->getRoutes();

		if (isset($routes[$route]['login']))
			$this->routes->checkLogin();

        $this->routes->updateRole();

        if (isset($routes[$route]['restricted']))
            if (!$this->routes->checkAccess())
                header('Location: /admin/access-restricted');

		$controller = $routes[$route][$method]['controller'];
        $functionName = $routes[$route][$method]['function'];

        if (isset($routes[$route][$method]['parameters']))
            $parameters = $routes[$route][$method]['parameters'];
        else
            $parameters = '';
        
        $page = $controller->$functionName($parameters);

        $nav = $this->loadTemplate('../templates/nav.html.php', $page['variables']);
        $output = $this->loadTemplate('../templates/' . $page['template'], $page['variables']);

        $title = $page['title'];

        require '../templates/' . $page['layout'];
    }

    public function loadTemplate($fileName, $templateVars) {
		extract($templateVars);
		ob_start();
		require $fileName;
		$contents = ob_get_clean();
		return $contents;
	}
}
?>