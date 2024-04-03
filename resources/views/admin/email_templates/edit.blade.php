<x-app-layout>

    <div class="row">


        <div class="col-10">
            <h2 class="text-center mb-3">Add Email Template</h1>
        </div>
        <div class="col-2"><a href="{{route('admin.email-templates.index')}}" class="btn btn-primary">Go Back</a></div>
    </div>
    <div class="row p-3">
    <div class="imageContainer mb-3 col-md-6">
        <form method="POST" action="{{ route('templates.update',['template' => $data->compose_id])}}" class="row" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="col-8 form-group">
                <div>
                    <label for="email_of">Select For</label>
                    <select name="email_of" class="form-control" value="{{ old('email_of') }}">
                        <option value="1" {{ $data->email_of == '1' ? 'selected' : '' }}>Register for User</option>
                        <option value="2" {{ $data->email_of == '2' ? 'selected' : '' }}>Forgot password</option>
                        <option value="3"  {{ $data->email_of == '3' ? 'selected' : '' }}>User Past Ad Order</option>
                        <option value="4"  {{ $data->email_of == '4' ? 'selected' : '' }}>User Register for Admin</option>
                        <option value="5"  {{ $data->email_of == '5' ? 'selected' : '' }}> Admin Past Ad Order</option>
                        <option value="6"  {{ $data->email_of == '6' ? 'selected' : '' }}>Seller Past Ad Order</option>
                        <option value="7"  {{ $data->email_of == '7' ? 'selected' : '' }}>Parts Request Question(parent)</option>
                        <option value="8"  {{ $data->email_of == '8' ? 'selected' : '' }}>Parts Request Question(sub question)</option>
                        <option value="9"  {{ $data->email_of == '9' ? 'selected' : '' }}>Bid Offer</option>
                        <option value="10"  {{ $data->email_of == '10' ? 'selected' : '' }}>Sales Question</option>
                        <option value="11"  {{ $data->email_of == '11' ? 'selected' : '' }}>Parts Order to User</option>
                        <option value="12"  {{ $data->email_of == '12' ? 'selected' : '' }}>Parts Order to Bidder</option>
                        <option value="13"  {{ $data->email_of == '13' ? 'selected' : '' }}>Parts Order to Admin</option>
                        <option value="14"  {{ $data->email_of == '14' ? 'selected' : '' }}>Subscribe Alert for ad</option>
                        <option value="15"  {{ $data->email_of == '15' ? 'selected' : '' }}>Subscribe Alert for Request Parts</option>
                    </select> 
                       
                    @error('email_of')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="mail_subject">Mail Subject</label>
                    <input type="text" name="mail_subject" class="form-control" id="mail_subject"
                        value="{{ old('mail_subject',$data->mail_subject) }}">
                    @error('mail_subject')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="mail_body" class="col-sm-4 col-form-label">Caption</label>
                    <textarea class="ckeditor form-control" name="mail_body" id="mail_body" placeholder="Description">{{ old('mail_body',$data->mail_body) }}</textarea>
                    @error('mail_body')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="mail_signature" class="col-sm-4 col-form-label">Signature</label>
                    <textarea class="ckeditor form-control" name="mail_signature" id="mail_signature" placeholder="Mail signature">{{ old('mail_signature', $data->signature) }}</textarea>
                    @error('mail_signature')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="status">Status</label>
                    <select class="form-control form-select" name="compose_status" id="compose_status">
                        <option value="1" {{ $data->compose_status == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ $data->compose_status == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('compose_status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <button class="btn btn-primary customSaveButton" type="submit">Save</button>
        </form>
    </div>
    <div class="imageContainer mb-3 col-md-6">
        <div id="emailTemplateForm">
    <div>
        # Hello!
        
    </div>
    <div>
        <button id="resetPasswordButton" class="btn btn-primary">Reset Password</button>
    </div>
    <div>
        Thanks,<br>
        {{ config('app.name') }}
    </div>
</div>
    </div>

</div>

<script src="{{ asset('assets/plugins/tinymce/tinymce.min.js')}}"></script>
<script>
    tinymce.init({
        selector: '#mail_body',
        plugins: 'codesample code advlist autolink lists link image charmap print preview hr anchor pagebreak',
        toolbar_mode: 'floating',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        
            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | codesample code',
            codesample_languages: [
                { text: 'HTML/XML', value: 'markup' },
                { text: 'JavaScript', value: 'javascript' },
                { text: 'CSS', value: 'css' },
                { text: 'PHP', value: 'php' },
                { text: 'Ruby', value: 'ruby' },
                { text: 'Python', value: 'python' },
                { text: 'Java', value: 'java' },
                { text: 'C', value: 'c' },
                { text: 'C#', value: 'csharp' },
                { text: 'C++', value: 'cpp' }
            ],
       
         file_picker_callback (callback, value, meta) {
        let x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth
        let y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight

        tinymce.activeEditor.windowManager.openUrl({
          url : '/file-manager/tinymce5',
          title : 'Laravel File manager',
          width : x * 0.8,
          height : y * 0.8,
          onMessage: (api, message) => {
            callback(message.content, { text: message.text })
          }
        })
      }
    });
    document.addEventListener("DOMContentLoaded", function() {

    document.getElementById('button-image').addEventListener('click', (event) => {
      event.preventDefault();

      window.open('http://192.168.1.44:8000/file-manager/fm-button', 'fm', 'width=1400,height=800');
    });
  });

  // set file link
  function fmSetLink($url) {
    document.getElementById('image_label').value = $url;
    document.getElementById('profile-pic').src = $url;
  }
      document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('saveButton').addEventListener('click', function () {
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
