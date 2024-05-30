<x-app-layout>
    <div class="row">

        <div class="col-10">
            <h1 class="text-center mb-3">Manage Template</h1>
        </div>
        <div class="col-2"><a href="{{route('admin.email-templates.create')}}" class="btn btn-primary">Create new</a></div>
    </div>
    <table class="brandsTable table table-hover" id="cmspageslist">
        <thead>
            <tr>
                <th scope="col">#SL</th>
                <th scope="col">Assign To</th>
                <th scope="col">Subject</th>
                <th scope="col">Status</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody id="brands_sortable">
            @foreach ($data as $index => $menu)
            <tr id="{{ $menu->compose_id  }}">
                <td scope="row">{{ $index + 1 }}</td>
                <td class="text-capitalize">
                    @if($menu->email_of == 1)
                    Register for User
                    @elseif($menu->email_of == 2)
                    Forgot password
                    @elseif($menu->email_of == 3)
                    User Past Ad Order
                    @elseif($menu->email_of == 4)
                    Admin Past Ad Order
                    @elseif($menu->email_of == 5)
                    User Register for Admin
                    @elseif($menu->email_of == 6)
                    Seller Past Ad Order
                    @elseif($menu->email_of == 7)
                    Parts Request Question(parent)
                    @elseif($menu->email_of == 8)
                    Parts Request Question(sub question)
                    @elseif($menu->email_of == 9)
                    Bid Offer
                    @elseif($menu->email_of == 10)
                    Sales Question
                    @elseif($menu->email_of == 11)
                    Parts Order to User
                    @elseif($menu->email_of == 12)
                    Parts Order to Bidder
                    @elseif($menu->email_of == 13)
                    Parts Order to Admin
                    @elseif($menu->email_of == 14)
                    Subscribe Alert for ad
                    @elseif($menu->email_of == 15)
                    Subscribe Alert for Request Parts
                    @endif
                </td>
                <td scope="row">{{ $menu->mail_subject }}</td>

                <td scope="row">@if($menu->compose_status == 1)
                    Active
                    @else
                    Inactive
                    @endif</td>
                <td>
                    <div class="d-flex">
                        <div class="customButtonContainer"><a class="mx-2" href="{{ url('admin/templates/' . $menu->compose_id  . '/edit') }}"><i class="fas fa-edit"></i></a>

                            <a class="mx-2" href="{{ url('admin/templates/' . $menu->compose_id) }}"><i class="fas fa-eye"></i></a>
                        </div>

                        <div class="customButtonContainer">
                            <!-- <form method="POST" action="{{ url('admin/templates/' . $menu->compose_id) }}">@csrf
                                @method('DELETE')<button type="submit"><i class="fas fa-trash"></i></button></form> -->
                            <button title="Delete" class="trash remove-templates" data-id="{{ $menu->compose_id }}" data-action="{{ url('admin/templates/' . $menu->compose_id) }}"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        $('#cmspageslist').dataTable({
            "bPaginate": false
        });
    </script>

    <script>
        $(document).ready(function() {
            $("body").on("click", ".remove-templates", function() {
                var current_object = $(this);
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this data!",
                    type: "error",
                    showCancelButton: true,
                    dangerMode: true,
                    cancelButtonClass: '#DD6B55',
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Delete!',
                }, function(result) {
                    if (result) {
                        var action = current_object.attr('data-action');
                        var token = jQuery('meta[name="csrf-token"]').attr('content');
                        var id = current_object.attr('data-id');

                        $('body').html("<form class='form-inline remove-form' method='post' action='" + action + "'></form>");
                        $('body').find('.remove-form').append('<input name="_method" type="hidden" value="DELETE">');
                        $('body').find('.remove-form').append('<input name="_token" type="hidden" value="' + token + '">');
                        $('body').find('.remove-form').append('<input name="id" type="hidden" value="' + id + '">');
                        $('body').find('.remove-form').submit();
                    }
                });
            });
        });
    </script>
</x-app-layout>