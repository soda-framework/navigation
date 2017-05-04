@extends(soda_cms_view_path('layouts.inner'))

@section('content-heading-button')
    @include(soda_cms_view_path('partials.buttons.create'), ['url' => route('soda.navigation.create')])
@stop

@section('content')
    @include('soda-navigation::tree.base', compact('treeItems'))
@endsection
