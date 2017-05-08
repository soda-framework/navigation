<ul class="page-tree" id="page-tree">
    @foreach($treeItems as $treeItem)
        @include('soda-navigation::tree.item', compact('treeItem'))
    @endforeach
</ul>
@section('footer.js')
    @parent
    <script src="/soda/cms/js/forms/sortable.js"></script>
@stop