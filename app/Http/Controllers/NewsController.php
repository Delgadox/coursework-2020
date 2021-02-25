<?php

namespace App\Http\Controllers;

use App\Models\News;
use app\Models\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Telegram;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Objects\InputMedia\InputMedia;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:news-list|news-create|news-edit|news-delete', ['only' => ['index','show']]);
         $this->middleware('permission:news-create', ['only' => ['create','store']]);
         $this->middleware('permission:news-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:news-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = News::latest()->paginate(5);
        $users = user::all()->keyby('id');
        return view('news.index',compact('data','users'))
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
        $response = Telegram::sendPhoto([
            'chat_id' => '@'.env('TELEGRAM_BOT_GROUP'),
            'photo' => InputFile::create($request->file('file_image')->getrealpath()),
            'parse_mode' => 'HTML',
            'caption' => '<b>'.$request->input('name').'</b>'.PHP_EOL.$request->input('text')
        ]);
        $messageId = $response->getMessageId();
        $news->user_id = Auth::user()->id;
        $news->message_id = $messageId;
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
        $users = user::where('id', $news->user_id)->first();
        return view('news.show',compact('news', 'users'));
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

        $response = Telegram::deleteMessage([
            'chat_id' => '@'.env('TELEGRAM_BOT_GROUP'),
            'message_id' => $news->message_id
        ]);
        $response = Telegram::sendPhoto([
            'chat_id' => '@'.env('TELEGRAM_BOT_GROUP'),
            'photo' => InputFile::create($request->file('file_image')->getrealpath()),
            'parse_mode' => 'HTML',
            'caption' => '<b>'.$request->input('name').'</b>'.PHP_EOL.$request->input('text')
        ]);
        $messageId = $response->getMessageId();

        $path = str_replace('public/images/','',$request->file('file_image')->store('public/images'));
        $news->name=$request->name;
        $news->text=$request->text;
        Storage::delete('public/images/'.$news->file_image);
        $news->file_image=$path;
        $news->message_id = $messageId;
        $news->save();

        return redirect()->route('news.index')->with('success','Новость успешно изменина');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy(News $news)
    {
        Storage::delete('public/images/'.$news->file_image);
        $response = Telegram::deleteMessage([
            'chat_id' => '@'.env('TELEGRAM_BOT_GROUP'),
            'message_id' => $news->message_id
        ]);
        $news->delete();

        return redirect()->route('news.index')->with('success','Новость удалена');
    }
}
