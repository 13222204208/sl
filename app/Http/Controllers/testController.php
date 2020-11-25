<?php

namespace App\Http\Controllers;

use App\Menu;
use Illuminate\Http\Request;

class testController extends Controller
{
    public function getTree()
    {
        /* $menu = Menu::find(1);
        $menu->makeRoot();
        $d= $menu->createChild(['name'=>'我是子节点']);
        return $d;
        dd($menu->getDescendantsAndSelf()->toArray()); */
    }
}
