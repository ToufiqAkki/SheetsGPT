<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiChatService
{
    /**
     * Send a sequence of messages to the Gemini API, prepended with document context.
     * 
     * @param array $history Previous messages [ ['role' => 'user'/'model', 'parts' => [['text' => '...']]] ]
     * @param string $documentContext The parsed text of the spreadsheets
     * @param string $newMessage The user's new message
     * @return string The assistant's text response
     */
    public function sendMessage(array $history, string $documentContext, string $newMessage): string
    {
        $apiKey = config('services.gemini.key');
        if (!$apiKey) {
            throw new \Exception("Gemini API key is not configured.");
        }

        // $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $apiKey;
        $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/gemma-4-26b-a4b-it:generateContent?key=" . $apiKey;

        // Truncate document context to prevent exceeding the model's 262,144 token limit.
        // Note: For tabular data (numbers, commas, spaces), the character-to-token ratio can be close to 1:1 or 2:1.
        $maxChars = 150000; 
        if (strlen($documentContext) > $maxChars) {
            $documentContext = mb_substr($documentContext, 0, $maxChars) . "\n\n... [Data Truncated to fit AI token limit]";
        }

        // System instructions / context is prepended to the first message or handled as a system instruction if it supports it.
        // For Gemini 1.5, we can use system_instruction.
        $payload = [
            'system_instruction' => [
                'parts' => [
                    ['text' => "You are an AI assistant for a spreadsheet analysis app. You are given data from uploaded XLSX/CSV documents. Your goal is to answer the user's questions based on this data. If they ask for a table, provide it in markdown format.\n\nCRITICAL RULE: When outputting tables, ONLY include the specific columns that the user explicitly asked for, or that are absolutely necessary to answer their query. Do not output all columns from the dataset to avoid clutter.\n\nKeep answers concise, accurate, and professional.\n\nDocument Data:\n" . $documentContext]
                ]
            ],
            'contents' => $history
        ];

        // Append the new message
        $payload['contents'][] = [
            'role' => 'user',
            'parts' => [['text' => $newMessage]]
        ];

        try {
            $response = Http::timeout(60)->post($endpoint, $payload);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    return $data['candidates'][0]['content']['parts'][0]['text'];
                }
                Log::error('Unexpected Gemini Response format', $data);
                return "Error: Unexpected response format from AI.";
            }

            Log::error('Gemini API Error: ' . $response->body());
            return "Error from Gemini API: " . $response->status();

        } catch (\Exception $e) {
            Log::error('Gemini Request Exception: ' . $e->getMessage());
            return "Exception while communicating with AI: " . $e->getMessage();
        }
    }
}
