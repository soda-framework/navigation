@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li><a href="{{ route('soda.navigation.index') }}">Navigation</a></li>
        <li class="active">Editing Menu Item</li>
    </ol>
@stop

@section('head.title')
    <title>Editing Menu Item</title>
@endsection

@section('content-heading-button')
    <button class="btn btn-info btn-lg" data-submits="#navigation-item-form">
        <i class="fa fa-pencil"></i>
        <span>Save</span>
    </button>
@stop

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-navigation',
    'title'       => 'Editing Menu Item',
])

@section('content')
    <div class="content-block">
        <form id="navigation-item-form" method="POST" action='{{ route('soda.navigation.save', $navigationItem->id) }}' enctype="multipart/form-data">
            {!! csrf_field() !!}
            @if($navigationItem->id === null)
            <input type="hidden" name="parent_id" value="{{ $navigationItem->parent_id }}" />
            @endif

            {!! app('soda.form')->text([
                "name"        => "Name",
                "description" => "The name of this menu item",
                "field_name"  => 'name',
            ])->setModel($navigationItem) !!}

            <input type="hidden" name="slug_type" value="{{ $navigationItem->slug_type ?: \Soda\Navigation\Models\NavigationItem::SLUG_TYPE_URL }}" />

            {!! app('soda.form')->text([
                'name'        => 'URL',
                'description' => 'The url this menu item should link to',
                'field_name'  => 'slug_value',
            ])->setModel($navigationItem) !!}

            {!! app('soda.form')->toggle([
                'name'         => 'Published',
                'field_name'   => 'status',
                'value'        => Soda\Cms\Foundation\Constants::STATUS_LIVE,
                'field_params' => ['checked-value' => Soda\Cms\Foundation\Constants::STATUS_LIVE, 'unchecked-value' => Soda\Cms\Foundation\Constants::STATUS_DRAFT],
            ])->setModel($navigationItem) !!}
        </form>
    </div>

    <div class="content-bottom">
        <button class="btn btn-info btn-lg" data-submits="#navigation-item-form">
            <i class="fa fa-pencil"></i>
            <span>Save</span>
        </button>
    </div>
@endsection
