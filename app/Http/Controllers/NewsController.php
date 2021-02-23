<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        echo '<pre>';
        dd($request->file('image'));
        die;
        echo '</pre>';

        $request->validate([
            'name'=> 'required|min:1|max:100',
            'text'=> 'required|min:1|max:255',
            // 'image'=> 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'image'=> 'required|max:2048',
        ]);

    
        // News::create($request->all());
        $news= new News();
        $news->name = $request->input('name');
        $news->text= $request->input('text');
        $name = $request->file('image')->getClientOriginalName();
        $path = str_replace('public/images/','',$request->file('image')->store('public/images'));
        $news->image= 1;
        $news->user_id = Auth::user()->id;
        $news->message_id = 1;
        $news->save();
     
        return redirect()->route('news.index')
                        ->with('success','Новость успешно опубликована');
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
            'name' => 'required',
            'text' => 'required',
            'image' => 'required',
            'user_id' => 'required',
            'message_id' => 'required'
        ]);
    
        $news->update($request->all());
    
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
