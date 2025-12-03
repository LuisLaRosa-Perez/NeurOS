<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use WebSocket\Client;
use WebSocket\ConnectionException;

class VlmController extends Controller
{
    public function predict(Request $request)
    {
        $request->validate([
            'image' => 'required|string',
            'prompt' => 'required|string|min:3',
        ]);

        $imageData = $request->input('image');
        $prompt = $request->input('prompt');

        // The Python server will be a WebSocket server
        // I need a websocket client for PHP. I will search for one.
        // A popular one is "textalk/websocket". I will assume it's installed via composer.
        // composer require textalk/websocket
        
        $client = null;
        try {
            // The python server will be on port 8001
            $client = new Client("ws://127.0.0.1:8002");
            
            $payload = json_encode([
                'image' => $imageData,
                'prompt' => $prompt,
            ]);

            $client->send($payload);
            $response = $client->receive(); // Wait for the response from the server
            
            $client->close();

            $data = json_decode($response, true);

            if (isset($data['error'])) {
                return response()->json(['error' => $data['error']], 500);
            }

            return response()->json(['prediction' => $data['prediction']]);

        } catch (ConnectionException $e) {
            Log::error('VLM WebSocket connection failed: ' . $e->getMessage());
            return response()->json(['error' => 'Could not connect to the VLM model server.'], 500);
        } catch (\Exception $e) {
            Log::error('VLM prediction error: ' . $e->getMessage());
            if ($client) {
                $client->close();
            }
            return response()->json(['error' => 'An unexpected error occurred during prediction.'], 500);
        }
    }
}
