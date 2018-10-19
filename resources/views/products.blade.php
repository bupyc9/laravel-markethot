@extends('layouts.app')

@section('content')
    <h1>{{$title}}</h1>

    <div class="card-columns">
        @foreach($products as $product)
            <div class="card">
                <img class="card-img-top" src="{{$product->picture}}" alt="{{$product->name}}">
                <div class="card-body">
                    <h5 class="card-title">{{$product->name}}</h5>
                    <p class="card-text">{{\Illuminate\Support\Str::limit($product->description, 50)}}</p>
                </div>
                <div class="card-footer">
                    <b class="text-muted">{{$product->price}} &#8381;</b>
                </div>
            </div>
        @endforeach
    </div>
    {{$products->links()}}
@endsection