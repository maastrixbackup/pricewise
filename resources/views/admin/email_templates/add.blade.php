<x-app-layout>
    <style type="text/css">
        .email-verify-main {
            background-color: #edf2f7;
            padding: 0px 25px;
        }

        .email-slfeh-title {
            text-align: center;
            padding: 25px 0;
        }

        .email-slfeh-title span {
            font-weight: 600;
            font-size: 18px;
            color: #585858;
        }

        .btn-style-verify {
            background: #2d3748;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
        }

        .verify-email-inner-box {
            padding: 20px 25px;
            background: #fff;
            box-shadow: 0 0 20px rgb(192 192 192 / 22%);
        }

        .verify-email-inner-box h5 {
            margin: 0 0 5px 0;
            font-weight: 600;
            color: #2d3748;
        }

        .verify-email-inner-box p,
        .verify-email-inner-box span {
            color: #909090;
            font-size: 16px;
        }

        .verify-descrip-data {
            word-break: break-all;
            font-size: 13px !important;
        }

        .verify-descrip-data a {
            color: #2f65bd;
        }

        .from-money-main .input-group .form-control {
            line-height: 20px;
            min-height: 32px;
        }

        .d-flex-btn-loans {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }
    </style>
    <div class="row">

        <div class="col-10">
            <h2 class="text-center mb-3">Add Email Template</h1>
        </div>
        <div class="col-2"><a href="{{ route('admin.email-templates.index') }}" class="btn btn-primary">Go Back</a></div>
    </div>
    <div class="row p-3">
        <div class="imageContainer mb-3 col-md-6">
            <form method="POST" action="{{ route('admin.email-templates.store') }}" class="row"
                id="emailTemplateFor">
                @csrf
                <div class="col-12 form-group">
                    <div>
                        <label for="email_of">Select For</label>
                        <select name="email_of" class="form-control" value="{{ old('email_of') }}">
                            <option value="">Select</option>
                            <option value="1">Register for User</option>
                            <option value="2">Forgot password</option>
                            <option value="3">User Past Ad Order</option>
                            <option value="4">Admin Past Ad Order</option>
                            <option value="5">User Register for Admin</option>
                            <option value="6">Seller Past Ad Order</option>
                            <option value="7">Parts Request Question(parent)</option>
                            <option value="8">Parts Request Question(sub question)</option>
                            <option value="9">Bid Offer</option>
                            <option value="10">Sales Question</option>
                            <option value="11">Parts Order to User</option>
                            <option value="12">Parts Order to Bidder</option>
                            <option value="13">Parts Order to Admin</option>
                            <option value="14">Subscribe Alert for ad</option>
                            <option value="15">Subscribe Alert for Request Parts</option>
                        </select>

                        @error('email_of')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="mail_subject">Mail Subject</label>
                        <input type="text" name="mail_subject" class="form-control" id="mail_subject"
                            value="{{ old('mail_subject') }}">
                        @error('mail_subject')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="mail_body" class="col-sm-4 col-form-label">Caption</label>
                        <textarea class="ckeditor form-control" name="mail_body" id="mail_body" placeholder="Description">{{ old('mail_body') }}</textarea>
                        @error('mail_body')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="mail_signature" class="col-sm-4 col-form-label">Signature</label>
                        <textarea class="ckeditor form-control" name="mail_signature" id="mail_signature" placeholder="Mail signature">{{ old('mail_signature') }}</textarea>
                        @error('mail_signature')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="status">Status</label>
                        <select name="status" class="form-control" id="status">
                            <option>Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        @error('status')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <button class="btn btn-sm btn-primary col-2 mt-2" type="submit" name="submit" id="saveButto">Save</button>
                </div>


            </form>
        </div>
        <div class="imageContainer mb-3 col-md-6">



        </div>

    </div>

    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>
    <script>
        tinymce.init({
            selector: '#mail_body,#mail_signature',
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
        document.addEventListener("DOMContentLoaded", function() {

            document.getElementById('button-image').addEventListener('click', (event) => {
                event.preventDefault();

                window.open('http://192.168.1.44:8000/file-manager/fm-button', 'fm',
                    'width=1400,height=800');
            });
        });

        // set file link
        function fmSetLink($url) {
            document.getElementById('image_label').value = $url;
            document.getElementById('profile-pic').src = $url;
        }
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('saveButton').addEventListener('click', function() {
                // Prevent default form submission
                event.preventDefault();

                // Serialize form data
                var formData = new FormData(document.getElementById('emailTemplateForm'));

                // Send AJAX request
                fetch("{{ route('admin.email-templates.store') }}", {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Display response data synchronously
                        console.log(data); // You can replace this with your desired action
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
        });
    </script>

</x-app-layout>
