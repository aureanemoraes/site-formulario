<p>
    @foreach($questions as $question)
        @if($request->input('type_' . $question->id))
        <p>{{ $request->input('type_' . $question->id) }}</p>
        @endif
        <p>lala</p>
    @endforeach

</p>


