<?php

require 'bootstrap.php';

use Routing\Router,
    Routing\MatchedRoute;

try {
    $router = new Router(GET_HTTP_HOST());
    
    $router->add('mainPage', '/', 'Views\MainView:get');
    
    $router->add('editSection', '/section/edit(id:num)', 'Views\SectionView:edit');
    $router->add('delSection', '/section/delete(id:num)', 'Views\SectionView:delete');
    $router->add('getSections', '/sections', 'Views\SectionView:get');
    $router->add('updateSection', '/section/update', 'Views\SectionView:update');
    $router->add('insertSection', '/section/insert', 'Views\SectionView:insert');
    
    $router->add('editElement', '/element/edit(id:num)', 'Views\ElementView:edit');
    $router->add('delElement', '/element/delete(id:num)', 'Views\ElementView:delete');
    $router->add('updateElement', '/element/update', 'Views\ElementView:update');
    $router->add('insertElement', '/element/insert', 'Views\ElementView:insert');

    $route = $router->match(GET_METHOD(), GET_PATH_INFO());

    if (null == $route) {
        $route = new MatchedRoute('Views\MainView:error404');
    }

    list($class, $action) = explode(':', $route->getController(), 2);
    call_user_func_array(array(new $class($router), $action), $route->getParameters());
} catch (Exception $ex) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);

    echo $ex->getMessage();
    echo $ex->getTraceAsString();
    exit;
}
