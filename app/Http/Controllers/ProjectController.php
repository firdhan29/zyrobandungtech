<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Payment;
use App\Models\Team;
use App\Models\Client;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with(['payments', 'teams'])->latest()->paginate(10);
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        return view('projects.create', compact('clients'));
    }

    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'client_id' => 'nullable|exists:clients,id',
            'client_name' => 'required_without:client_id|string|max:255',
            'client_phone' => 'required_without:client_id|string|max:20',
            'total_amount' => 'required|numeric|min:0',
            'dp_amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $clientId = $request->client_id;
        $clientName = $request->client_name;
        $clientPhone = $request->client_phone;
        $clientEmail = $request->client_email;

        if ($clientId) {
            $client = Client::find($clientId);
            $clientName = $client->name;
            $clientPhone = $client->phone;
            $clientEmail = $client->email;
        } else {
            $client = Client::create([
                'name' => $clientName,
                'phone' => $clientPhone,
                'email' => $clientEmail,
            ]);
            $clientId = $client->id;
        }

        $project = Project::create([
            'client_id' => $clientId,
            'name' => $request->name,
            'description' => $request->description,
            'client_name' => $clientName,
            'client_phone' => $clientPhone,
            'client_email' => $clientEmail,
            'total_amount' => $request->total_amount,
            'dp_amount' => $request->dp_amount,
            'remaining_amount' => $request->total_amount - $request->dp_amount,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'whatsapp_link' => 'https://wa.me/' . preg_replace('/[^0-9]/', '', $clientPhone),
        ]);

        return redirect()->route('projects.show', $project)->with('success', 'Project berhasil dibuat!');
    }

    public function show(Project $project)
    {
        $project->load(['payments', 'teams', 'client']);
        $allTeams = Team::where('is_active', true)->get();
        return view('projects.show', compact('project', 'allTeams'));
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'client_phone' => 'required|string|max:20',
            'total_amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|string',
        ]);

        $data = $request->all();
        
        // Recalculate remaining amount if total amount changed
        if ($project->total_amount != $request->total_amount) {
            $totalPaid = $project->dp_amount + $project->payments()->where('status', 'confirmed')->sum('amount');
            $data['remaining_amount'] = max(0, $request->total_amount - $totalPaid);
        }

        $project->update($data);
        return redirect()->route('projects.show', $project)->with('success', 'Project diperbarui!');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project dihapus!');
    }

    public function assignTeam(Request $request, Project $project)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
        ]);

        $project->teams()->syncWithoutDetaching([$request->team_id]);
        return redirect()->back()->with('success', 'Team member ditambahkan ke project!');
    }

    public function removeTeam(Project $project, Team $team)
    {
        $project->teams()->detach($team->id);
        return redirect()->back()->with('success', 'Team member dihapus dari project!');
    }

    public function updateProgress(Request $request, Project $project)
    {
        $request->validate([
            'progress_percentage' => 'required|integer|min:0|max:100',
            'progress_notes' => 'nullable|string',
        ]);

        $project->update([
            'progress_percentage' => $request->progress_percentage,
            'progress_notes' => $request->progress_notes,
        ]);

        return response()->json(['success' => true]);
    }
}