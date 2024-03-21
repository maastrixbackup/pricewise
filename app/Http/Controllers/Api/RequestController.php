<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Admin\AdminController;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use App\Mail\MarkdownEmail;
use Illuminate\Support\Facades\Mail;

class RequestController extends AdminController
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$posts = EmailTemplate::whereAdminId(\Auth::guard('admin')->user()->id)->get();

        return view('customers.request.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */ 
    public function store(Request $request)
    {
        $name = 'John Doe';
        $orderNo = '12345';
        $emailTemplate = EmailTemplate::where('name', 'Request Placed Successfully')->first();
        $body = $emailTemplate->content;
        $body = str_replace(['{{ $name }}', '{{ $orderNo }}'], [$name, $orderNo], $emailTemplate->content);

        $action_link = 'https://example.com';

        Mail::to('bijay.behera85@gmail.com')->send(new MarkdownEmail($name, $body, $action_link));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        // if(\Auth::guard('admin')->user()->id == $post->admin_id){
        //     return view('admin.posts.edit',['post'=>$post]);
        // }

        // if(\Auth::guard('admin')->user()->can('view',$post)){
        //     return view('admin.posts.edit',['post'=>$post]);            
        // }
        
        $this->authorize('view',$post);
        return view('admin.posts.edit',['post'=>$post]);            
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $this->authorize('update',$post);
        $post->update([
            'title'=>$request->title,
            'description'=>$request->description
        ]);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }
}
