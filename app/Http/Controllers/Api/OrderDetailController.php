<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderDetailController extends Controller
{
    public function index(TodoList $todo_list)
    {
        
    }

    public function store(TaskRequest $request, TodoList $todo_list)
    {
        $task =  $todo_list->tasks()->create($request->validated());
        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }

    public function update(Task $task, Request $request)
    {
        $task->update($request->all());
        return new TaskResource($task);
    }
}
