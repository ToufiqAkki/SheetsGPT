<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Workspace;
use App\Models\Document;
use App\Services\DocumentParserService;

class DocumentController extends Controller
{
    public function store(Request $request, Workspace $workspace, DocumentParserService $parser)
    {
        if ($workspace->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'files.*' => 'required|file|mimes:csv,xlsx,xls|max:10240' // 10MB max per file
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('documents');
                $absolutePath = storage_path('app/private/' . $path);
                
                // Parse content
                $content = $parser->parse($absolutePath);

                $workspace->documents()->create([
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'content' => $content
                ]);
            }
        }

        return redirect()->back()->with('status', 'Files uploaded successfully.');
    }

    public function destroy(Workspace $workspace, Document $document)
    {
        if ($workspace->user_id !== auth()->id() || $document->workspace_id !== $workspace->id) {
            abort(403);
        }

        // Optional: delete from storage
        \Storage::delete($document->file_path);
        
        $document->delete();

        return redirect()->back()->with('status', 'Document deleted.');
    }
}
