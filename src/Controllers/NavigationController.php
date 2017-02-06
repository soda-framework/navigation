<?php

namespace Soda\Navigation\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Soda\Navigation\Models\NavigationItem;

class NavigationController extends Controller
{
    public function index(Request $request)
    {
        $treeItems = NavigationItem::get()->toTree();

        return view('soda-navigation::index', compact('treeItems'));
    }

    public function create($parentId = null)
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

    public function move(Request $request, $id)
    {
        $position = $request->input('position');
        $parentId = $request->input('parent_id');

        NavigationItem::find($id)->moveTo($position, $parentId != null ? $parentId : null);

        return response()->json(['success' => true]);
    }
}
