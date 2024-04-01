<x-app-layout>
    <div class="row">

        <div class="col-10">
            <h1 class="text-center mb-3">Admin User Detail</h1>
        </div>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <td>Full Name</td>
                <td>
                    @if($email->email_of == 1)
                    Register for User
                    @elseif($email->email_of == 2)
                    Forgot password
                    @elseif($email->email_of == 3)
                    User Past Ad Order
                    @elseif($email->email_of == 4)
                    Admin Past Ad Order
                    @elseif($email->email_of == 5)
                    User Register for Admin
                    @elseif($email->email_of == 6)
                    Seller Past Ad Order
                    @elseif($email->email_of == 7)
                    Parts Request Question(parent)
                    @elseif($email->email_of == 8)
                    Parts Request Question(sub question)
                    @elseif($email->email_of == 9)
                    Bid Offer
                    @elseif($email->email_of == 10)
                    Sales Question
                    @elseif($email->email_of == 11)
                    Parts Order to User
                    @elseif($email->email_of == 12)
                    Parts Order to Bidder
                    @elseif($email->email_of == 13)
                    Parts Order to Admin
                    @elseif($email->email_of == 14)
                    Subscribe Alert for ad
                    @elseif($email->email_of == 15)
                    Subscribe Alert for Request Parts
                    @endif</td>
            </tr>
            <tr>
                <td>Mail Id</td>
                <td>{{$email->mail_subject}}</td>
            </tr>
            <tr>
                <td>User Id</td>
                <td>{!! strip_tags($email->mail_body) !!}</td>
            </tr>
        </thead>
       
    </table>
    

</x-app-layout>
