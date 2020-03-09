<ul>
@foreach($pwzItems as $item)
    <li><a href="#" data-code="{{ $item->Code }}" title="{{ $item->Address }}">{{ $item->Name }}</a></li>
@endforeach
</ul>