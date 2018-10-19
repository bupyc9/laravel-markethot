<ul class="list-group">
    @foreach($categories as $category)
        <li class="list-group-item">
            <h5 class="mb-0">
                <a href="{{route('categoryIndex', ['code' => $category['code']])}}">{{$category['name']}}</a>
            </h5>
            @if($category['children'])
                <ul class="list-group ml-2 mt-2">
                    @foreach($category['children'] as $child)
                        <li class="list-group">
                            <a href="{{route('categoryIndex', ['code' => $category['code'], 'childCode' => $child['code']])}}">{{$child['name']}}</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
    @endforeach
</ul>