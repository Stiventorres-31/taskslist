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


    // Crear tarea
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required|max:500',
            'user' => 'required|max:500',
        ]);
        $user = User::where('email', $validated['user'])->first();

        // Si el usuario no se encuentra, devolver un error
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        // Crear y guardar la nueva tarea
        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'user_id' => $user->id,
        ]);

        return response()->json(['message'=>'Task created successfully.','result'=>$task->load('user')], 201);
    }

    public function completedTask($id){


        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found.'], 404);
        }

        // Actualizar el campo 'completed' a 1
        $task->completed = 1;
        $task->save();

        // Retornar la tarea actualizada
        return response()->json(['message'=>'Task completed successfully.','result'=>$task->load('user')], 200);
    }

    // Actualizar tarea
    public function update(Request $request, $id)
    {

        $validated = $request->validate([
            'title' => 'required|max:255|min:4',
            'description' => 'required|max:500|min:4',
        ]);

        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found.'], 404);

        }

        // Corrección: Se actualiza la tarea con datos validados.
        $task->update($validated);

        return response()->json(['message'=>'Task updated successfully.','result'=>$task->load('user')], 200);
        // return redirect()->back()->with('success', 'Task updated successfully.');
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
