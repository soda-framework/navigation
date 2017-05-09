<?php

namespace Soda\Navigation\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Soda\Navigation\Models\NavigationItem;

class NavigationController extends Controller
{
    public function __construct()
    {
        app('soda.interface')->setHeading('Navigation')->setHeadingIcon('fa fa-compass');
        app('soda.interface')->breadcrumbs()->addLink(route('soda.home'), 'Home');
        app('soda.interface')->breadcrumbs()->addLink(route('soda.navigation.index'), 'Navigation');
    }

    public function index(Request $request)
    {
        $treeItems = NavigationItem::get()->toTree();

        return view('soda-navigation::index', compact('treeItems'));
    }

    public function create($parentId = null)
    {
        app('soda.interface')->setHeading('New Navigation Item');

        $navigationItem = new NavigationItem([
            'slug_type' => 'url',
            'parent_id' => $parentId,
        ]);

        return view('soda-navigation::view', compact('navigationItem'));
    }

    public function edit($id)
    {
        $navigationItem = NavigationItem::findOrFail($id);
        app('soda.interface')->setHeading('Editing Navigation Item');

        return view('soda-navigation::view', compact('navigationItem'));
    }

    public function save(Request $request, $id = null)
    {
        $navigationItem = $id ? NavigationItem::find($id) : new NavigationItem;

        $navigationItem->fill($request->all());

        if ($navigationItem->parent_id === '') {
            $navigationItem->parent_id = null;
        }

        $navigationItem->save();

        return redirect()->route('soda.navigation.edit', $navigationItem->id)->with('success', 'Navigation item saved successfully');
    }

    public function delete($id)
    {
        NavigationItem::destroy($id);

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
