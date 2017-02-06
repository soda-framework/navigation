{{--renders tree in html --}}
<li class="tree-row {{ $treeItem->hasChildrenRelation() && count($treeItem->children) > 0 ? 'has-sub-items' : '' }}" data-id="{{ $treeItem->id }}" data-move="{{ route('soda.navigation.move', $treeItem->id) }}" style="display:{{ isset($display) ? $display : 'block' }}">
    <div class="tree-item clearfix">
        <span class="handle">
            <img src="/soda/cms/img/drag-dots.gif" />
        </span>
        <span class="item-status">
            <span class="{{ $treeItem->status == \Soda\Cms\Support\Constants::STATUS_DRAFT ? 'inactive' : 'active' }}-circle"></span>
        </span>
        <a class="item-title" href="{{ route('soda.navigation.edit', ['id' => $treeItem->id]) }}">
            <span>{{ $treeItem->name }}</span>
        </a>
        <span class="minify">
            <i class="fa fa-chevron-right"></i>
        </span>
        <div class="option-buttons pull-right">
            <div style="display:inline-block;position:relative;">
                <a href="#" class="btn btn-info option-more" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-ellipsis-v"></i>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route('soda.navigation.create', ['parentId' => $treeItem->id]) }}">Create submenu item</a>
                    </li>
                    <li>
                        <a href="{{ route('soda.navigation.edit', ['id' => $treeItem->id]) }}">Edit menu item</a>
                    </li>
                    <li class="divider"></li>
                    <li class="warning">
                        <a data-tree-delete href="{{ route('soda.navigation.delete', ['id' => $treeItem->id]) }}">Delete</a>
                    </li><!--v-if-->
                </ul>
            </div>
        </div>
    </div>

    <ul class="tree-sub-items">
        @if ($treeItem->hasChildrenRelation() && count($treeItem->children) > 0)
            @foreach($treeItem->children as $child)
                @include('soda-navigation::tree.item', ['treeItem' => $child, 'display' => 'none'])
            @endforeach
        @endif
    </ul>
</li>
