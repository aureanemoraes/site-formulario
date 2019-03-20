<p>
    <h1>Question</h1>
    @foreach($questions as $q)
        {{$q->name}}
    @endforeach

    <h1>Options</h1>
    @foreach($options as $o)
        {{ $o->name }}
    @endforeach

</p>


