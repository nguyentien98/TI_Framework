<?php

namespace Controllers;

use Core\BaseController;
use Helpers\HttpRequest;
use Models\User;

class HomeController extends BaseController
{
    public function index(HttpRequest $request)
    {
        var_dump(User::where('name', '=', '1')->where('name', '!=', '1')->get());
    }
}
