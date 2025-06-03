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

    private $defaultPrompt = "You are a cooking assistant. Help users with cooking related questions. Make sure to provide clear and concise answers. If you don't know the answer, say 'I don't know'.
    If user asks for a recipe, provide a simple recipe with ingredients and steps.
    If user asks for cooking tips, provide useful tips.
    If user asks for a cooking technique, explain it clearly.
    If user asks for a food substitution, suggest a suitable alternative.
    If user asks for a cooking time, provide an estimated time.
    If user asks for a cooking temperature, provide a suitable temperature.
    If user asks for a cooking method, explain it clearly.
    If user asks for a cooking tool, suggest a suitable tool.
    If user asks for a cooking term, explain it clearly.
    If user asks for a cooking measurement, provide a suitable measurement.
    If user asks for a cooking ingredient, suggest a suitable ingredient.
    If user asks for a cooking technique, explain it clearly.   
    If user ask about anything else, politely inform them that you can only assist with cooking related questions.
    Please respond in a friendly and helpful manner.
    Jawablah dengan bahasa Indonesia.
    ";

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

            // Handle various API response scenarios
            if (isset($responseData['error'])) {
                $errorMessage = $responseData['error']['message'] ?? 'Unknown error occurred';
                $errorCode = $responseData['error']['code'] ?? 500;
                
                // Handle specific error cases
                if ($errorCode == 503) {
                    return response()->json([
                        'response' => "Maaf, saya sedang mengalami kesibukan. Mohon coba beberapa saat lagi."
                    ]);
                }
            }

            // Extract text from response - handle both v1 and v1beta response structures
            if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
                $generatedText = $responseData['candidates'][0]['content']['parts'][0]['text'];
            } elseif (isset($responseData['candidates'][0]['text'])) {
                $generatedText = $responseData['candidates'][0]['text'];
            } else {
                \Log::error('API Response Structure:', ['response' => $responseData]);
                return response()->json([
                    'response' => "Maaf, hal yang Anda tanyakan di luar kemampuan saya. Saya hanya bisa membantu dengan pertanyaan seputar memasak."
                ]);
            }

            // Send the response with the original text (keeping markdown formatting)
            return response()->json(['response' => $generatedText]);
            
        } catch (\Exception $e) {
            \Log::error('Chatbot Error:', ['error' => $e->getMessage()]);
            return response()->json([
                'response' => "Maaf, saya sedang mengalami kendala teknis. Mohon coba beberapa saat lagi."
            ]);
        }
    }
    //
}
