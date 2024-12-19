@extends('admin.layouts.app')
@section('title', 'Pricewise- Switching Plan Edit')
@section('content')

    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <!-- <div class="breadcrumb-title pe-3">Add New User</div> -->
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><a
                            href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a
                            href="{{ route('admin.switching-plan-faqs', $pFaq->provider_id) }}">Switching Plan FAQs</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="col-12 col-lg-12">
            <div class="card">
                <div class="card-header px-4 py-3">
                    <h5 class="mb-0">Edit FAQ</h5>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="{{ route('admin.update-switching-plan-faqs') }}">
                        @csrf
                        {{-- <input type="hidden" name="id" value="{{ $pFaq->id }}"> --}}
                        <input type="hidden" name="p_id" value="{{ $pFaq->provider_id }}">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Sample Title</label>
                                <input type="text" class="form-control" name="title" id="title" placeholder="Title"
                                    value="{{ $pFaq->title }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Description</label>
                                <textarea name="desc" id="desc" cols="30" rows="3" class="form-control" placeholder="Description"
                                    required>{{ $pFaq->description }}</textarea>
                            </div>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Question</th>
                                    <th>Answer</th>
                                </tr>
                            </thead>
                            <tbody id="appData">
                                @if (!empty($pFaqs))
                                    @foreach ($pFaqs as $k => $v)
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control" name="question[]"
                                                    value="{{ $v->question }}" placeholder="Question" required>
                                                <input type="hidden" name="ids[]" value="{{ $v->id }}">
                                                <input type="hidden" name="p_ids[]" value="{{ $v->provider_id }}">
                                            </td>
                                            <td>
                                                <textarea name="answer[]" id="answer" class="form-control" cols="30" rows="3" placeholder="Answer"
                                                    required>{{ $v->answer }}</textarea>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <div class="row">
                            <label class=" col-form-label"></label>
                            <div class="">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <button type="submit" class="btn btn-primary px-4" name="submit2">Update</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
