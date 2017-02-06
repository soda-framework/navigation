@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li class="active">Navigation</li>
    </ol>
@stop

@section('head.title')
    <title>Reports</title>
@endsection

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-compass',
    'title'       => 'Navigation',
])

@section('content')
    @include('soda-navigation::tree.base', compact('treeItems'))
@endsection
