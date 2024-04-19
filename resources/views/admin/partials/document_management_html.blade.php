<input type="hidden" name="category_id" value="{{$objPost->category}}">
<input type="hidden" name="post_id" value="{{$objPost->id}}">
<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">File</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($documents as $key => $document)
        <tr>
            <th scope="row">{{ $key + 1 }}</th>
            <td>{{ $document->filename }}</td>
            <td><input type="hidden" value="{{$document->id}}">                                                
                <input type="button" class="btn btn-danger delete-button" value="Delete"/>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<input type="button" id="add-more" class="btn btn-primary" value="Add More"/>