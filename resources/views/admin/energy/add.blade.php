@extends('admin.layouts.app')
@section('title', 'PriceWise- Energy Create')
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
                            href="{{ route('admin.energy.index') }}">Energy Products</a></li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <form id="productForm" method="post" action="{{ route('admin.energy.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class=" col-12">
                <div class="card">
                    <div class="card-header px-4 py-3">
                        <h5 class="mb-0">Add New Energy Product</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="provider" class="col-form-label"><b>Provider</b>
                                </label>

                                <select id="provider" name="provider" class="select2 form-select" required>
                                    <option disabled selected>Select</option>
                                    @if ($providers)
                                        @foreach ($providers as $provider)
                                            <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <span class="invalid-feedback">Please Select a valid value.</span>
                                <input type="hidden" value="{{ $objCategory->id }}" class="form-control" name="category">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="provider" class="col-form-label"><b>Target Group</b>
                                </label>
                                <select id="target_group" name="target_group" class="select2 form-select" required>
                                    <option disabled selected>Select</option>
                                    <option value="personal">Personal</option>
                                    <option value="commercial">Commercial</option>
                                    <option value="large_business">Large Business</option>
                                </select>
                                <span class="invalid-feedback">Please Select a valid value.</span>
                            </div>

                            <div class="col-md-3 col-12">
                                <div class=" mb-3">
                                    <label for="valid_till" class="col-form-label"> Power Origin</label>
                                    <div>
                                        <label><input type="checkbox" name="power_origin[]" value="wind"> Wind</label>
                                    </div>
                                    <div>
                                        <label><input type="checkbox" name="power_origin[]" value="water"> Water</label>
                                    </div>
                                    <div>
                                        <label><input type="checkbox" name="power_origin[]" value="sun"> Sun</label>
                                    </div>
                                    <div>
                                        <label><input type="checkbox" name="power_origin[]" value="thermal"> Thermal</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class=" mb-3">
                                    <label for="avg_delivery_time" class=" col-form-label">Type Of Gas</label>
                                    <div>
                                        <label><input type="checkbox" name="gas_type[]" value="co2"> Co2 Compensated
                                            Gas</label>
                                    </div>
                                    <div>
                                        <label><input type="checkbox" name="gas_type[]" value="partly_green_gas"> Partly
                                            Green Gas</label>
                                    </div>
                                    <div>
                                        <label><input type="checkbox" name="gas_type[]" value="100_green_gas"> 100% Green
                                            Gas</label>
                                    </div>
                                    <div>
                                        <label><input type="checkbox" name="gas_type[]" value="gas_free"> Gas Free</label>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div>
                                    <label for="valid_till" class="col-form-label"> Fixed Delivery Cost: € <span
                                            style="font-family: Robot Mono" id="delivery_cost">0</span></label>
                                    <input type="hidden" class="form-control" name="fixed_delivery" id="fixed_delivery"
                                        placeholder="Delivery Cost" value="" readonly>
                                </div>
                                <div>
                                    <label for="avg_delivery_time" class=" col-form-label">Grid Management Cost: € <span
                                            style="font-family: Robot Mono" id="grid_cost">0</span></label>
                                    <input type="hidden" readonly class="form-control" id="grid_management"
                                        name="grid_management" placeholder="Grid Management Cost" value=""
                                        min="0">
                                </div>
                                <div>
                                    <label for="avg_delivery_time" class=" col-form-label">Feed In Tariff: € <span
                                            style="font-family: Robot Mono" id="feed_in_tariffs">0</span></label>
                                    <input type="hidden" readonly class="form-control" id="feed_in_tariff"
                                        name="feed_in_tariff" placeholder="Solar Buy Back" value=""
                                        min="0">
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div>
                                    <label for="" class="col-form-label">Tax On Electric:
                                        € <span
                                            style="font-family: Robot Mono">{{ $globalEnergy->tax_on_electric }}</span></label>
                                    <input type="hidden" readonly value="{{ $globalEnergy->tax_on_electric }}"
                                        name="tax_on_electric" id="tax_on_electric" class="form-control">
                                </div>
                                <div>
                                    <label for="" class="col-form-label">Tax on Gas:
                                        € <span
                                            style="font-family: Robot Mono">{{ $globalEnergy->tax_on_gas }}</span></label>
                                    <input type="hidden" readonly value="{{ $globalEnergy->tax_on_gas }}"
                                        name="tax_on_gas" id="tax_on_gas" class="form-control">
                                </div>
                                <div>
                                    <label for="" class="col-form-label">ODE On Electric:
                                        € <span
                                            style="font-family: Robot Mono">{{ $globalEnergy->ode_on_electric }}</span></label>
                                    <input type="hidden" readonly value="{{ $globalEnergy->ode_on_electric }}"
                                        name="ode_on_electric" id="ode_on_electric" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div>
                                    <label for="" class="col-form-label">ODE On Gas:
                                        € <span
                                            style="font-family: Robot Mono">{{ $globalEnergy->ode_on_gas }}</span></label>
                                    <input type="hidden" readonly value="{{ $globalEnergy->ode_on_gas }}"
                                        name="ode_on_gas" id="ode_on_gas" class="form-control">
                                </div>
                                <div>
                                    <label for="" class="col-form-label">VAT:
                                        € <span style="font-family: Robot Mono">{{ $globalEnergy->vat }}</span></label>
                                    <input type="hidden" readonly value="{{ $globalEnergy->vat }}" name="vat"
                                        id="vat" class="form-control">
                                </div>
                                <div>
                                    <label for="" class="col-form-label">Energy Tax Reduction/Year:
                                        € <span
                                            style="font-family: Robot Mono">{{ $globalEnergy->energy_tax_reduction }}</span></label>
                                    <input type="hidden" readonly value="{{ $globalEnergy->energy_tax_reduction }}"
                                        name="energy_tax_reduction" id="energy_tax_reduction" class="form-control">
                                </div>

                            </div>
                        </div>

                        <div class="row mt-2">
                            <span id="lenthY" style="display: none;">
                                @include('admin.energy.contract_year')
                            </span>
                        </div>

                        <div class="">
                            <label class=" col-form-label"></label>
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" class="btn btn-primary px-4" id="submitBtn"
                                    name="submit2">Submit</button>
                                <a href="{{ route('admin.energy.create') }}" class="btn btn-light px-4">Reset</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </form>
@endsection
@push('scripts')
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('#lenthY').css('display', 'block');
            }, 2000); // 2000 milliseconds = 2 seconds delay

            $('#submitBtn').click(function(e) {
                // Initialize the validation flag
                let isValid = true;

                // Check if at least one "Power Origin" checkbox is checked
                let isPowerOriginChecked = $('input[name="power_origin[]"]:checked').length > 0;
                // Check if at least one "Type of Gas" checkbox is checked
                let isGasTypeChecked = $('input[name="gas_type[]"]:checked').length > 0;
                // Check if at least one "Contract Year" checkbox is checked
                let isYearChecked = $('input[name="contract_year[]"]:checked').length > 0;

                // If any checkbox group is not selected, show alert and prevent form submission
                if (!isPowerOriginChecked || !isGasTypeChecked || !isYearChecked) {
                    e.preventDefault(); // Prevent form submission
                    let message = 'Please select at least one option for:\n';
                    if (!isPowerOriginChecked) message += '- Power Origin\n';
                    if (!isGasTypeChecked) message += '- Type of Gas\n';
                    if (!isYearChecked) message += '- Contract Year\n';
                    alert(message);
                    isValid = false;
                }

                // Validate number inputs related to selected "Contract Year" checkboxes
                $('input[name="contract_year[]"]:checked').each(function() {
                    let year = $(this).val();

                    // Validate each corresponding input based on the selected year
                    let powerInput = $(`#power_year_${year}`);
                    let gasInput = $(`#gas_year_${year}`);
                    let discountInput = $(`#discount_year_${year}`);

                    // Array of inputs to check
                    let inputs = [powerInput, gasInput, discountInput];

                    // Loop through inputs and check for validity
                    inputs.forEach(function(input) {
                        var value = input.val();
                        if (value === "" || value < 0 || isNaN(value)) {
                            isValid = false;
                            input.addClass('is-invalid') // Add error class if invalid
                        } else {
                            input.removeClass('is-invalid'); // Add valid class if valid
                        }
                    });
                });


                // Validate provider select field
                let provider = $('#provider').val();
                if (!provider) {
                    isValid = false;
                    $('#provider').addClass('is-invalid');
                } else {
                    $('#provider').removeClass('is-invalid').addClass('is-valid');
                }

                // Validate target group select field
                let targetGroup = $('#target_group').val();
                if (!targetGroup) {
                    isValid = false;
                    $('#target_group').addClass('is-invalid');
                } else {
                    $('#target_group').removeClass('is-invalid').addClass('is-valid');
                }

                // Prevent form submission if any field is invalid
                if (!isValid || $('.is-invalid').length > 0) {
                    e.preventDefault(); // Prevent form submission
                    alert("Please correct the invalid fields before submitting.");
                }
            });


            // Optional: Apply the decimal restriction to all relevant input fields on keyup
            $('input[type="number"]').on('keyup', function() {
                restrictDecimal($(this));
            });

            // Function to handle decimal restriction (optional for later)
            function restrictDecimal(inputField) {
                var value = inputField.val();
                if (value === '') {
                    inputField.removeClass('is-valid').addClass('is-invalid');
                } else {
                    inputField.removeClass('is-invalid').addClass('is-valid');
                }
                if (value.indexOf('.') !== -1) {
                    var parts = value.split('.');
                    if (parts[1].length > 2) {
                        parts[1] = parts[1].substring(0, 2); // Restrict to two decimal places
                        inputField.val(parts[0] + '.' + parts[1]);
                    }
                }
            }


            $('input[type="checkbox"]').on('change', function() {
                // Get the parent row of the checkbox
                var parentRow = $(this).closest('tr');

                // Enable/disable all input fields in the same row based on the checkbox state
                if ($(this).is(':checked')) {
                    parentRow.find('input[type="number"]').prop('readonly', false);
                    parentRow.find('input[type="number"]').prop('disabled',
                        false); // Add valid class if valid
                        parentRow.find('input[type="date"]').prop('readonly', false).prop('disabled', false);
                    // parentRow.find('input[type="number"]').attr('required', true);
                    // parentRow.find('input[type="number"]').css('border', '1px solid gray');
                } else {
                    parentRow.find('input[type="number"]').prop('disabled', true);
                    parentRow.find('input[type="number"]').prop('readonly', true);
                    parentRow.find('input[type="date"]').prop('readonly', true).prop('disabled', true);
                    parentRow.find('input[type="number"]').val('');
                    parentRow.find('input[type="number"]').removeClass('is-invalid');
                    parentRow.find('input[type="number"]').removeClass('is-valid');
                    // parentRow.find('input[type="number"]').removeAttr('required');
                    // parentRow.find('input[type="number"]').css('border', '1px solid #d1d8ea');
                }
            });

        });

        $('#target_group').on('change', function() {
            validateSelectField($(this));
        });


        $('#provider').on('change', function() {
            validateSelectField($(this));
            var vals = $(this).val(); // Get the selected value
            var action = "{{ route('admin.get_provider') }}"; // Get the route without ID

            $.ajax({
                url: action, // Use the dynamically constructed URL
                type: 'Post',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: vals,
                },
                success: function(resp) {
                    console.log(resp);
                    // return false;
                    if (resp.status) {
                        if (resp.data != '') {
                            // For Input Data
                            $('#fixed_delivery').val(resp.data.fixed_deliver_cost);
                            $('#grid_management').val(resp.data.grid_management_cost);
                            $('#feed_in_tariff').val(resp.data.feed_in_tariff);
                            // For Html Data
                            $('#delivery_cost').html(resp.data.fixed_deliver_cost);
                            $('#grid_cost').html(resp.data.grid_management_cost);
                            $('#feed_in_tariffs').html(resp.data.feed_in_tariff);
                        }
                        // Check if lengthData is not null and is an array
                        if (resp.lengthData && Array.isArray(resp.lengthData) && resp.lengthData
                            .length > 0) {
                            // Loop through each item in the lengthData array
                            resp.lengthData.forEach(function(value) {
                                // Disable the checkbox that matches the value
                                $(`#doneData_${value}`).html(`Data Exists.`).css('color', 'red');
                                $(`input[name="contract_year[]"][value="${value}"]`).prop(
                                    'checked', false).css('display', 'none');
                                $(`input[name="year_power[${value}]"]`).prop('disabled', true)
                                    .removeClass('is-valid').removeClass('is-invalid').val('');
                                $(`input[name="year_gas[${value}]"]`).prop('disabled', true)
                                    .removeClass('is-valid').removeClass('is-invalid').val('');
                                $(`input[name="discount[${value}]"]`).prop('disabled', true)
                                    .removeClass('is-valid').removeClass('is-invalid').val('');
                                $(`input[name="valid_till[${value}]"]`).prop('disabled', true)
                                    .removeClass('is-valid').removeClass('is-invalid').val('');
                            });
                        } else {
                            // Revert checkboxes and fields to their normal state if lengthData is null or empty
                            $('input[name="contract_year[]"]').css('display', 'inline').prop('checked',
                                false);
                            $('input[name^="year_power"]').prop('disabled', true).prop('readonly', true).removeClass(
                                'is-valid').removeClass('is-invalid').val('');
                            $('input[name^="year_gas"]').prop('disabled', true).prop('readonly', true).removeClass('is-valid')
                                .removeClass('is-invalid').val('');
                            $('input[name^="discount"]').prop('disabled', true).prop('readonly', true).removeClass('is-valid')
                                .removeClass('is-invalid').val('');
                            $('input[name^="valid_till"]').prop('disabled', true).prop('readonly', true).removeClass('is-valid')
                                .removeClass('is-invalid').val('');
                            $('[id^="doneData_"]').html('');
                        }

                    } else {
                        toastr.error(resp.message, '');
                    }
                    // toastr.error(data, 'Already Exists!');
                },
                error: function(xhr) {
                    console.error('Error fetching provider:', xhr.responseText);
                }
            });
        });
        // Function to handle select field validation
        function validateSelectField(selectField) {
            if (selectField.val() == "") {
                // Add 'is-invalid' class if no valid option is selected
                selectField.addClass('is-invalid').removeClass('is-valid');
            } else {
                // Remove 'is-invalid' and add 'is-valid' class if valid option is selected
                selectField.removeClass('is-invalid').addClass('is-valid');
            }
        }
    </script>
    <script>
        // $(document).ready(function() {
        //     new Choices(document.querySelector("#pin_codes"), {
        //         removeItemButton: true
        //     });
        // });
        tinymce.init({
            selector: '#description',
            plugins: 'codesample code advlist autolink lists link image charmap print preview hr anchor pagebreak',
            toolbar_mode: 'floating',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',

            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | codesample code',
            codesample_languages: [{
                    text: 'HTML/XML',
                    value: 'markup'
                },
                {
                    text: 'JavaScript',
                    value: 'javascript'
                },
                {
                    text: 'CSS',
                    value: 'css'
                },
                {
                    text: 'PHP',
                    value: 'php'
                },
                {
                    text: 'Ruby',
                    value: 'ruby'
                },
                {
                    text: 'Python',
                    value: 'python'
                },
                {
                    text: 'Java',
                    value: 'java'
                },
                {
                    text: 'C',
                    value: 'c'
                },
                {
                    text: 'C#',
                    value: 'csharp'
                },
                {
                    text: 'C++',
                    value: 'cpp'
                }
            ],

            file_picker_callback(callback, value, meta) {
                let x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName(
                    'body')[0].clientWidth
                let y = window.innerHeight || document.documentElement.clientHeight || document
                    .getElementsByTagName('body')[0].clientHeight

                tinymce.activeEditor.windowManager.openUrl({
                    url: '/file-manager/tinymce5',
                    title: 'Laravel File manager',
                    width: x * 0.8,
                    height: y * 0.8,
                    onMessage: (api, message) => {
                        callback(message.content, {
                            text: message.text
                        })
                    }
                })
            }
        });
    </script>
    <script type="text/javascript">
        $("#productForm").validate({
            errorElement: 'span',
            errorClass: 'help-block',
            highlight: function(element, errorClass, validClass) {
                $(element).closest('.form-group').addClass("has-error");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).closest('.form-group').removeClass("has-error");
                $(element).closest('.form-group').addClass("has-success");
            },

            rules: {
                title: "required",
                description: "required",

            },
            messages: {
                title: "Title is missing",
                description: "Description is missing",
            },
            submitHandler: function(form) {
                var data = CKEDITOR.instances.description.getData();
                $("#description").val(data);
                var formData = new FormData(form);
                $.ajax({

                    url: form.action,
                    method: "post",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        //success

                        if (data.status) {
                            location.href = data.redirect_location;
                        } else {
                            toastr.error(data.message.message, 'Already Exists!');
                        }
                    },
                    error: function(e) {
                        toastr.error('Something went wrong . Please try again later!!', '');
                    }
                });
                return false;
            }
        });

        $("#title").keyup(function() {
            var title_val = $("#title").val();
            $("#link").val(title_val.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''));
        });
        $("#title").keydown(function() {
            var title_val = $("#title").val();
            $("#link").val(title_val.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''));
        });
    </script>
@endpush
