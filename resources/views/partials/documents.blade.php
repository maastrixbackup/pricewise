@if(isset($documents))
@foreach($documents as $document)
    <div class="document">
        <span>{{ $document->filename }}</span>
        <button class="delete" data-id="{{ $document->id }}">Delete</button>
    </div>
@endforeach
@endif