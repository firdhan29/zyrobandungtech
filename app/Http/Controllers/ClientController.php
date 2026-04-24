<?php
namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::withCount([
            'projects',
            'projects as completed_projects_count' => function ($query) {
                $query->where('status', 'completed');
            }
        ])->orderBy('completed_projects_count', 'desc')->get();

        return view('clients.index', compact('clients'));
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $client->update($request->all());
        return redirect()->route('clients.index')->with('success', 'Data klien diperbarui!');
    }

    public function destroy(Client $client)
    {
        // Check if client has projects
        if ($client->projects()->count() > 0) {
            return redirect()->route('clients.index')->with('error', 'Klien tidak bisa dihapus karena memiliki data project!');
        }

        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Data klien dihapus!');
    }
}