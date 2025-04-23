@extends('admin.layouts.app')
@section('title','Edit Charges | Energies')
@section('content')


    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><a
                            href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a
                            href="{{ route('admin.feed-in-charge', $id) }}">Feed In Charges</a></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-12">
            <h6 class="mb-0 text-uppercase">Edit Feed In Charges </h6>
            <hr />
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('admin.update-feed-in-charges') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="" style="overflow-x:auto;">
                            <table id="userTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Scale</th>
                                        <th colspan="2" class="text-center">Range </th>
                                        <th>Cost/Day €</th>
                                        <th>Cost/Year €</th>
                                    </tr>
                                </thead>
                                <tbody id="appData">
                                    <input type="hidden" name="provider_id" value="{{ $id }}">
                                    @if ($feedIn)
                                        @foreach ($feedIn as $feed)
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="ids[]" value="{{ $feed->id }}">
                                                    <input type="text" class="form-control" name="scale[]"
                                                        value="{{ $feed->scale ?? '' }}" placeholder="Scale" required>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="range_from[]"
                                                        value="{{ $feed->range_from ?? '' }}" placeholder="Range From"
                                                        required>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="range_to[]"
                                                        value="{{ $feed->range_to ?? '' }}" placeholder="Range To" required>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" name="cost_per_day[]"
                                                        step="0.00001" value="{{ $feed->cost_per_day ?? '' }}"
                                                        placeholder="Cost Per Day" required>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" name="cost_per_year[]"
                                                        step="0.00001" value="{{ $feed->cost_per_year ?? '' }}"
                                                        placeholder="Cost Per Year" required>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
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
@push('scripts')
    <script>
        $(document).ready(function() {
            // Optional: Apply the decimal restriction to all relevant input fields on keyup
            $(document).on('keyup', 'input[type="number"]', function() {
                restrictDecimal($(this));
            });

            // Function to handle decimal restriction (optional for later)
            function restrictDecimal(inputField) {
                var value = inputField.val();
                if (value.indexOf('.') !== -1) {
                    var parts = value.split('.');
                    if (parts[1].length > 5) {
                        parts[1] = parts[1].substring(0, 5); // Restrict to five decimal places
                        inputField.val(parts[0] + '.' + parts[1]);
                    }
                }
            }
        });
    </script>
@endpush
