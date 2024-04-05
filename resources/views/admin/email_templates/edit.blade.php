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
            <h2 class="text-center mb-3">Edit Email Template</h1>
        </div>
        <div class="col-2"><a href="{{route('admin.email-templates.index')}}" class="btn btn-primary">Go Back</a></div>
    </div>
    <form method="POST" action="{{ route('admin.email-templates.update',$email_template->id)}}" class="row" enctype="multipart/form-data">
    <div class="row p-3">
        
    <div class="imageContainer mb-3 col-md-6">
        
            @csrf
            @method('PUT')
            <div class="col-12 form-group">
                <div>
                    @php
                        $email_types = [1 => 'Register for Customer',
                                        2 => 'Forgot Password',
                                        3 => 'User Past Ad Order',
                                        4 => 'User Register for Admin',
                                        5 => 'Customer Request Success',
                                        6 => 'Register for User',
                                        7 => 'Register for User',
                                        8 => 'Register for User',
                                        9 => 'Register for User',
                                        10 => 'Register for User',
                                        11 => 'Register for User',
                                        12 => 'Register for User',
                                        13 => 'Register for User',
                                        14 => 'Register for User',
                                        15 => 'Register for User']
                    @endphp
                    
                <label class="col-form-label">Email Of</label>
                    <input type="hidden" class="form-control" name="email_of" value="{{$email_template? $email_template->email_of : '' }}">
                    <input type="text" class="form-control"  placeholder="Email Type" value="@if(isset( $email_types[$email_template->email_of])){{ $email_types[$email_template->email_of] }} @endif" readonly=""> @error('type') <div class="alerts alert-danger mt-1 mb-1" style="color:red">{{ $message }}</div> @enderror
                  </div>       
                    @error('email_of')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="mail_subject">Mail Subject</label>
                    <input type="text" name="mail_subject" class="form-control" id="mail_subject"
                        value="{{ old('mail_subject',$email_template->mail_subject) }}">
                    @error('mail_subject')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="mail_body" class="col-sm-4 col-form-label">Caption</label>
                    <textarea class="ckeditor form-control" name="mail_body" id="mail_body" placeholder="Description">{{ old('mail_body',$email_template->mail_body) }}</textarea>
                    @error('mail_body')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="mail_signature" class="col-sm-4 col-form-label">Signature</label>
                    <textarea class="ckeditor form-control" name="mail_signature" id="mail_signature" placeholder="Mail signature">{{ old('mail_signature', $email_template->signature) }}</textarea>
                    @error('mail_signature')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="status">Status</label>
                    <select class="form-control form-select" name="status" id="compose_status">
                        <option value="">Select</option>
                        <option value="1" {{ $email_template->status == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ $email_template->status == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('compose_status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                 <button class="btn btn-primary customSaveButton" type="submit">Save</button>
            </div>
            
       
    
    <div class="imageContainer mb-3 col-md-6">
    @if( $email_template)
    @if($email_template->email_of == 2)
  
    <div class="email-verify-main">
      <div class="email-slfeh-title">
        <img src="{{asset('storage/images/website/'. siteSettings()->logo)}}" alt="">
      </div>
      <div class="verify-email-inner-box">
        <h5>Hello!</h5>
                <p>Click the button below to verify your email address.</p>
        <div id="resultBody">{!!$email_template->mail_body!!}</div>
        <div class="text-center mt-5 mb-5">
          <a href="#" class="btn-style-verify">Verify Email Address</a>
        </div>
        <!-- <span>Regards, <br> Solfeh</span> -->
        <div id="resultSign">{!!$email_template->signature!!}</div>
        <hr>
        <p class="verify-descrip-data">
          If you're having trouble clicking the "Verify Email Address" button, copy and paste the URL below into your web browser: <a href="#">http://192.168.1.42:8001/verify-email/94/e829fe960232c26d02b5497a65a9feff61daf52a?expires=1665643755&signature=3f48f0024b332717e6fd7646a7154cdf3e1b1f15652aac1809cded7045f0802b</a>
        </p>
      </div>

      <div class="email-slfeh-title">
        <p>© 2024 <a href="#">Energise</a>. All rights reserved.</p>
      </div>
    </div>
  
  @else

  
    <div class="email-verify-main">
      <div class="email-slfeh-title">
        <img src="{{asset('storage/images/website/'. siteSettings()->logo)}}" alt="">
      </div>
      <div class="verify-email-inner-box">
        <!-- <h5>Hello!</h5>
                <p>Click the button below to verify your email address.</p> -->
        <div id="resultBody">{!!$email_template->mail_body!!}</div>
        <div class="text-center mt-5 mb-5">
          <!-- <a href="#" class="btn-style-verify">Verify Email Address</a> -->
        </div>
        <!-- <span>Regards, <br> Solfeh</span> -->
        <div id="resultSign">{!!$email_template->signature!!}</div>
        <hr>
        <!-- <p class="verify-descrip-data">
                  If you're having trouble clicking the "Verify Email Address" button, copy and paste the URL below into your web browser: <a href="#">http://192.168.1.42:8001/verify-email/94/e829fe960232c26d02b5497a65a9feff61daf52a?expires=1665643755&signature=3f48f0024b332717e6fd7646a7154cdf3e1b1f15652aac1809cded7045f0802b</a>
                </p> -->
      </div>

      <div class="email-slfeh-title">
        <p>© 2024 <a href="#">Energise</a>. All rights reserved.</p>
      </div>
    </div>
   @endif
  @endif
    </div>

</div>
</form>
<script src="{{ asset('assets/plugins/tinymce/tinymce.min.js')}}"></script>
<script>
 tinymce.init({
        selector: '#mail_body,#mail_signature',
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
