<table class="display table table-striped table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>Image</th>
        <th>Title</th>
        <th>Approved</th>
        <th>Status</th>
        <th>Created Time</th>
        <th>Created By</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($posts as $key => $item)
        <tr>
            <td>{{$startIndex + $key + 1 }}</td>
            {{--                                            <td>--}}
            {{--                                                @if (!empty($item->image))--}}
            {{--                                                    @php--}}
            {{--                                                        $images = json_decode($item->image, true); --}}
            {{--                                                    @endphp--}}
            {{--                                                    @if (!empty($images) && is_array($images))--}}
            {{--                                                        @foreach ($images as $image)--}}
            {{--                                                            <img src="{{ asset('backend/images/news-feed-post/' . $image) }}" class="img-fluid rounded" style="max-height: 50px; margin-right: 5px;">--}}
            {{--                                                        @endforeach--}}
            {{--                                                    @endif--}}
            {{--                                                @endif--}}
            {{--                                            </td>--}}

            <td>
                @if (!empty($item->image))
                    @php
                        $images = json_decode($item->image, true);
                    @endphp
                    @if (!empty($images) && is_array($images))
                        @foreach ($images as $index => $image)
                            @if ($index < 4)
                                <img src="{{ asset('backend/images/news-feed-post/' . $image) }}?t={{ time() }}"
                                     class="img-fluid rounded"
                                     style="max-height: 50px; margin-right: 5px;">
                            @endif
                        @endforeach
                    @endif
                @endif
            </td>




            <td>{{ $item->title }}</td>
            <td>
                @if($item->approved == 1)
                <span class="badge badge-success">
                    Approved
                </span>
                @endif
            </td>
            <td>
                <a href="{{ route('posts.change-status', $item->id) }}" class="btn btn-sm btn-{{ $item->status == 1 ? 'success':'danger' }}">
                    {{ $item->status == 1 ? 'Active' : 'Inactive' }}
                </a>
            </td>
            <td>{{ $item->created_at->diffForHumans() }}</td>
            <td>{{ $item->createdBy->name ?? 'N/A' }}</td>
            <td>
                <a href="{{ route('posts.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('posts.destroy', $item->id) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Are you sure you want to delete this post?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="pagination">
    {{ $posts->links() }}
</div>
