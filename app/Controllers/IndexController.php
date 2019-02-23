<?php
namespace App\Controllers;

use Swoft\App;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\View\Bean\Annotation\View;
use Swoft\Http\Message\Server\Response;
use Swoft\Http\Message\Bean\Annotation\Middleware;
use Swoft\Http\Message\Bean\Annotation\Middlewares;
use App\Middlewares\BaseMiddleware;
use App\Middlewares\InterfaceMiddleware;

/**
 * Class IndexController
 * @Controller()
 */
class IndexController
{
    /**
     * @RequestMapping("/")
     * @return Response
     */
    public function index(): Response
    {
        return view('index/index');
    }
}
