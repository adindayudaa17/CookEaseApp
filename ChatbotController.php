<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function index()
    {
        return view('chatbot');
    }

    private $defaultPrompt = "You are a cooking assistant. Help users with cooking related questions. Make sure to provide clear and concise answers. If you don't know the answer, say 'I don't know'.";

    public function sendMessage(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'message' => 'required|string',
            'prompt' => 'nullable|string'
        ]);

        try {
            $apiKey = trim(env('GEMINI_API_KEY'));
            
            if (empty($apiKey)) {
                throw new \Exception('Gemini API key is not set');
            }
            
            // Debug log - Check if API key is loaded
            Log::info('API Key loaded:', ['key_exists' => true, 'key_length' => strlen($apiKey)]);
            $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . $apiKey;
            
            // Prepare the request data
            $data = [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => ($request->input('prompt', $this->defaultPrompt) . " User asks: " . $request->message)
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 2048,
                ]
            ];

            // Initialize cURL
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json'
            ]);

            // Execute the request
            $response = curl_exec($ch);
            
            // Log raw response
            \Log::info('Raw API Response:', ['response' => $response]);
            
            if (curl_errno($ch)) {
                throw new \Exception(curl_error($ch));
            }
            
            // Get HTTP status code
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            \Log::info('HTTP Status Code:', ['code' => $httpCode]);
            
            curl_close($ch);

            // Decode the response
            $responseData = json_decode($response, true);
            
            // Debug log with full response structure
            \Log::info('Decoded API Response:', ['response' => $responseData]);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Failed to parse JSON response: ' . json_last_error_msg());
            }

            // Extract text from response - handle both v1 and v1beta response structures
            if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
                $generatedText = $responseData['candidates'][0]['content']['parts'][0]['text'];
            } elseif (isset($responseData['candidates'][0]['text'])) {
                $generatedText = $responseData['candidates'][0]['text'];
            } else {
                \Log::error('API Response Structure:', ['response' => $responseData]);
                throw new \Exception('Could not find text in API response. Response structure: ' . json_encode($responseData));
            }

            // Send the response with the original text (keeping markdown formatting)
            return response()->json(['response' => $generatedText]);
            
        } catch (\Exception $e) {
            return response()->json([
                'response' => 'Sorry, I encountered an error: ' . $e->getMessage()
            ], 500);
        }
    }
    //
}
