@section('title','Price Compare- Edit Create')
@section('content')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <!-- <div class="breadcrumb-title pe-3">Add New User</div> -->
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.pages.index')}}">Pages</a></li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->
<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header px-4 py-3">
                <h5 class="mb-0">Edit Page</h5>
            </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
            
                    <form method="POST" action="{{ route('admin.pages.update', $page->id)}}">
                        @csrf
                        @method('PUT')
                        <div class="flex-col flex py-3">
                            <label class="pb-2 text-gray-700 font-semibold">Title</label>
                            <input type="text" class="p-2 shadow rounded-lg bg-gray-100 outline-none focus:bg-gray-200" placeholder="title" name="title" value="{{$page->title}}">
                        </div>
                        <div class="flex-col flex py-3">
                            <label class="pb-2 text-gray-700 font-semibold">Description</label>
                            <textarea type="text" class="p-2 shadow rounded-lg bg-gray-100 outline-none focus:bg-gray-200" rows="9" placeholder="title" name="description" id="description">{{$page->description}}</textarea>
                        </div>

                        <button class="px-4 py-2 rounded text-white inline-block shadow-lg bg-blue-500 hover:bg-blue-600 focus:bg-blue-700" type="submit">Update</button>
                        
                    </form>


                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>


