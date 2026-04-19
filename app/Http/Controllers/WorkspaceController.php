<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Workspace;

class WorkspaceController extends Controller
{
    public function index()
    {
        $workspaces = auth()->user()->workspaces()->latest()->get();
        return view('workspaces.index', compact('workspaces'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $workspace = auth()->user()->workspaces()->create($validated);

        return redirect()->route('workspaces.show', $workspace)->with('status', 'Workspace created successfully.');
    }

    public function show(Workspace $workspace)
    {
        if ($workspace->user_id !== auth()->id()) {
            abort(403);
        }

        $documents = $workspace->documents;
        $conversations = $workspace->conversations()->latest()->get();

        return view('workspaces.show', compact('workspace', 'documents', 'conversations'));
    }

    public function destroy(Workspace $workspace)
    {
        if ($workspace->user_id !== auth()->id()) {
            abort(403);
        }

        $workspace->delete();

        return redirect()->route('workspaces.index')->with('status', 'Workspace deleted.');
    }
}
