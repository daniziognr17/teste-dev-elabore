<?php 

$routes = [
    '/' => [
        ['GET', 'HomeController@index']
    ],
    '/clientes' => [
        ['GET', 'ClientesController@list'], 
        ['POST', 'ClientesController@create']
    ],
    '/clientes/{id}' => [
        ['GET', 'ClientesController@show'], 
        ['PUT', 'ClientesController@update'], 
        ['DELETE', 'ClientesController@delete']
    ],
    '/produtos' => [
        ['GET', 'ProdutosController@list'], 
        ['POST', 'ProdutosController@create']
    ],
    '/produtos/{id}' => [
        ['GET', 'ProdutosController@show'], 
        ['PUT', 'ProdutosController@update'], 
        ['DELETE', 'ProdutosController@delete']
    ],
    '/pedidos' => [
        ['GET', 'PedidosController@list'], 
        ['POST', 'PedidosController@create']
    ],
    '/pedidos/{id}' => [
        ['GET', 'PedidosController@show'], 
        ['PUT', 'PedidosController@update'], 
        ['DELETE', 'PedidosController@delete']
    ]
];