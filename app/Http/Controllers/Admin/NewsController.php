<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Channel;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    // 稿件列表
    public function index()
    {
        $news = News::paginate(10);
        return view('news.index', compact('news'));
    }

    // 创建稿件
    public function create()
    {
        $channels = Channel::all();
        return view('news.create', compact('channels'));
    }
    // 保存稿件
    public function store()
    {
        $this->validate(request(),[
            'title'  => 'required|string|min:3|max:100',
            'keywords' => 'max:50',
            'channel_id' => 'required|integer',
        ]);

        $news = [
            'create_user' => Auth::id(),
            'update_user' => Auth::id(),
            'title' => request('title'),
            'keywords' => request('keywords'),
            'channel_id' => request('channel_id'),
            'content' => request('content'),
        ];
        News::create($news);
        return redirect('/news');
    }

    // 编辑稿件
    public function edit(News $news)
    {
        $channels = Channel::all();
        return view('news.edit', compact('news', 'channels'));
    }

    // 更新稿件
    public function update(News $news)
    {
        $this->validate(request(), [
            'title'  => 'required|string|min:3|max:100',
            'keywords' => 'max:50',
            'channel_id' => 'required|integer',
        ]);
        $news->title = request('title');
        $news->keywords = request('keywords');
        $news->update_user = Auth::id();
        $news->channel_id = request('channel_id');
        $news->content = request('content');
        $news->save();
        return redirect('/news');
    }

    // 删除新闻
    public function destroy(News $news)
    {
        $news->delete();
        return [
            'error' => 0,
            'msg'   => 'success'
        ];
    }

}
