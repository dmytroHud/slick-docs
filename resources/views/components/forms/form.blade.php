<form method="{{$method}}" {!! $attributes->merge(['class' => 'space-y-6']) !!}>
    @csrf
    {{$slot}}
</form>
