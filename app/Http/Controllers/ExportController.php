<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Message;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class ExportController extends Controller
{
    public function exportMessage(Message $message)
    {
        if ($message->conversation->workspace->user_id !== auth()->id()) {
            abort(403);
        }

        $pdf = Pdf::loadHTML('
            <html>
            <head>
                <style>
                    body { font-family: sans-serif; color: #333; line-height: 1.6; }
                    table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f4f4f4; }
                </style>
            </head>
            <body>
                <h2>SheetsGPT Export</h2>
                <div>' . str()->markdown($message->content) . '</div>
            </body>
            </html>
        ');

        return $pdf->download('sheetsgpt-response-' . $message->id . '.pdf');
    }
}
