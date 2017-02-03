<?php

namespace Soda\Navigation\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class NavigationController extends Controller
{
    public function index(Request $request)
    {
        return view('soda-navigation::index');
    }

    public function create()
    {
        return view('soda-navigation::view');
    }

    public function edit($id)
    {
        return view('soda-navigation::view');
    }

    public function save(Request $request, $id = null)
    {
        return redirect()->route('soda.navigation.edit', $id)->with('success', 'Navigation item saved successfully');
    }

    public function delete($id)
    {
        return redirect()->route('soda.navigation.index')->with('warning', 'Navigation item deleted');
    }

    public function move()
    {
        return response()->json(['success' => true]);
    }
}
