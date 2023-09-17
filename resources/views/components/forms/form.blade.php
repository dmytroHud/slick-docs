<form method="{{$baseMethod}}" {!! $attributes->merge(['class' => 'space-y-6']) !!}>
    @csrf
    @method($method)
    {{$slot}}
</form>
