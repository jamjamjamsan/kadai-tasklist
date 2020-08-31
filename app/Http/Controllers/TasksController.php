<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        if(\Auth::check()){
         //$tasks = Task::all();
         $user = \Auth::user();
         $tasks = $user->tasks()->get();

        // メッセージ一覧ビューでそれを表示
        return view('tasks.index', [
            'tasks' => $tasks,
        
        ]);
            
        }
        else
       
        return view("welcome");
         


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task;

        // メッセージ作成ビューを表示
        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $request->validate([
            "status" => "required|max:10",
            "content" => "required|max:255",
        ]);
        $task = new Task;
        $task->status = $request->status;
        $task->content = $request->content;
        $task->user_id = Auth()->id();
        $task->save();

        // トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::findOrFail($id);
        //$user = User::findOrFail($id);
        //$task = $user->tasks()->get();
        if (\Auth::id() === $task->user_id) {
             return view('tasks.show', [
            'task' => $task,
        ]);
        }
        else return redirect("/");
        // メッセージ詳細ビューでそれを表示
       // return view('tasks.show', [
            //'task' => $task,
       // ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::findOrFail($id);
        if (\Auth::id() === $task->user_id) {
            return view('tasks.edit', [
            'task' => $task,
        ]);
        }

        // メッセージ編集ビューでそれを表示
        else return redirect("/");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $request->validate([
            "status" => "required|max:10",
            "content" => "required|max:255",
        ]);
        
        $task = Task::findOrFail($id);
        // メッセージを更新
        $task->content = $request->content;
        $task->status = $request->status;
        $task->user_id = Auth()->id();
        $task->save();

        // トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        if (\Auth::id() === $task->user_id) {
             // メッセージを削除
             $task->delete();
        }
        
       

        // トップページへリダイレクトさせる
        return redirect('/');
    }
}
