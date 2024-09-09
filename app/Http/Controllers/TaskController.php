<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    public function index()
    {
        // Obtener todas las tareas, incluyendo la información del usuario asignado
        $tasks = Task::orderBy('id','DESC')->with('user')->get();

        // Retornar las tareas en formato JSON
        return response()->json($tasks, 200);
    }

    public function findTask($id)
    {
        // Obtener todas las tareas, incluyendo la información del usuario asignado
        $task = Task::with('user')->find($id);

        // Retornar las tareas en formato JSON
        return response()->json($task, 200);
    }

    // Crear tarea
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required|max:500',
            'user' => 'required|max:500',
        ]);

        // $task = new Task($validated);
        // $user = User::where('email', $validated['user'])->first();
        // $task->user_id = $user->id;
        // $task->save();

        // return redirect()->back()->with('success', 'Task created successfully.');
        // Buscar el usuario por su email
        $user = User::where('email', $validated['user'])->first();

        // Si el usuario no se encuentra, devolver un error
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        // Crear y guardar la nueva tarea
        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'user_id' => $user->id,
        ]);

        return response()->json($task->load('user'), 201);
    }

    public function completedTask($id){
      

        $task = Task::find($id);

        if (!$task) {
            return response()->json(['error' => 'Task not found.'], 404);
        }

        // Actualizar el campo 'completed' a 1
        $task->completed = 1;
        $task->save();

        // Retornar la tarea actualizada
        return response()->json(['message'=>'Task completed successfully.','task'=>$task], 200);
    }

    // Actualizar tarea
    public function update(Request $request, $id)
    {

        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required|max:500',
        ]);

        $task = Task::find($id);

        if (!$task) {
            return redirect()->back()->with('error', 'Task not found.');
        }

        // Corrección: Se actualiza la tarea con datos validados.
        $task->update($validated);
        return redirect()->back()->with('success', 'Task updated successfully.');
    }

    // Eliminar tarea
    public function destroy($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return redirect()->back()->with('error', 'Task not found.');
        }

        $task->delete();

        return response()->json(['message'=>'Task deleted successfully.']);
        // return redirect()->back()->with('success', 'Task deleted successfully.');
    }
}
