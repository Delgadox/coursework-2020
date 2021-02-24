<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = News::latest()->paginate(5);

        return view('news.index',compact('data'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('news.create');
    }

    /**
     * Сохранить изображение в базу данных.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=> 'required|min:1|max:100',
            'text'=> 'required|min:1|max:255',
            'file_image'=> 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $news= new News();
        $news->name = $request->input('name');
        $news->text= $request->input('text');
        $name = $request->file('file_image')->getClientOriginalName();
        $path = str_replace('public/images/','',$request->file('file_image')->store('public/images'));
        $news->file_image = $path;
        $news->user_id = Auth::user()->id;
        $news->message_id = 1;
        $news->save();

        return redirect()->route('news.index')->with('success','Новость успешно опубликована');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function show(News $news)
    {
        return view('news.show',compact('news'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function edit(News $news)
    {
        return view('news.edit',compact('news'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, News $news)
    {
        $request->validate([
            'name'=> 'required|min:1|max:100',
            'text'=> 'required|min:1|max:255',
            'file_image'=> 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        dd($request->file('file_image')->['pathname']);
        $update = ['name'=>'','text'=>'','file_image'=>''];
        $update->name = $request->input('name');
        $update->text= $request->input('text');
//        $name = $request->file('file_image')->getClientOriginalName();
        $path = str_replace('public/images/','',$request->file('file_image')->store('public/images'));
        $update->file_image = $path;
        dd($update);
        $news->update($update);

        return redirect()->route('news.index')
                        ->with('success','Новость успешно изменина');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy(News $news)
    {
        $news->delete();

        return redirect()->route('news.index')
                        ->with('success','Новость удалена');
    }
}
