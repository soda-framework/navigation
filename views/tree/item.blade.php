{{--renders tree in html --}}
<li class="tree-row {{ $treeItem->hasChildrenRelation() && count($treeItem->children) > 0 ? 'has-sub-items' : '' }}" data-id="{{ $treeItem->id }}" data-move="{{ route('soda.navigation.move', $treeItem->id) }}" style="display:{{ isset($display) ? $display : 'block' }}">
    <div class="tree-item clearfix">
        <span class="{{ $treeItem->parent_id === null ? 'locked-handle' : 'handle' }}">
            <img src="/soda/cms/img/drag-dots.gif" />
        </span>
        <span class="item-status">
            <span class="{{ $treeItem->status == Soda\Cms\Foundation\Constants::STATUS_DRAFT ? 'inactive' : 'active' }}-circle"></span>
        </span>
        <a class="item-title" href="{{ route('soda.navigation.edit', ['id' => $treeItem->id]) }}">
            <span>{{ $treeItem->name }}</span>
        </a>

        <span class="{{ $treeItem->hasChildrenRelation() && count($treeItem->children) > 0 ? 'minify' : 'locked-minify'}}">
            <i class="fa fa-chevron-right"></i>
        </span>

        <div class="option-buttons pull-right">
            <div style="display:inline-block;position:relative;">
                <a href="#" class="btn btn-info option-more" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-ellipsis-v"></i>
                </a>
                <div class="dropdown-menu">
                    <div>
                        <a href="{{ route('soda.navigation.create', ['parentId' => $treeItem->id]) }}">Create submenu item</a>
                    </div>
                    <div>
                        <a href="{{ route('soda.navigation.edit', ['id' => $treeItem->id]) }}">Edit menu item</a>
                    </div>
                    <div class="divider"></div>
                    <div class="warning">
                        <a data-tree-delete href="{{ route('soda.navigation.delete', ['id' => $treeItem->id]) }}">Delete</a>
                    </div>
                </div>
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
