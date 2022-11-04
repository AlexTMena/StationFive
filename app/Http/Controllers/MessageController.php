<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function post(Request $request)
    {

        $acceptedGreetings = ['Hi', 'Hello'];
        $acceptedFarewells = ['Goodbye', 'bye'];

        $validator = Validator::make($request->all(), [
            'message' => 'required|string',
            'conversation_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $validated = $validator->validated();

        $words = explode(' ', $validated['message']);
        
        if ($this->hasValidKeyword($acceptedGreetings,$words) === true) {
            return response()->json([
                'response_id' => $validated['conversation_id'],
                'response' => 'Welcome to StationFive.',
            ], 200);
        }
        
        if ($this->hasValidKeyword($acceptedFarewells,$words) === true) {
            return response()->json([
                'response_id' => $validated['conversation_id'],
                'response' => 'Thank you, see you around.',
            ], 200);
        }
        
        return response()->json([
            'response_id' => $validated['conversation_id'],
            'response' => 'Sorry, I don’t understand.',
        ], 200);;
    }

    private function hasValidKeyword($acceptedKeywords, $inputedWords) {
        $valid = false;
        foreach ($acceptedKeywords as $acceptedWord) {
            if (in_array($acceptedWord, $inputedWords)) {
                $valid = true;
                break;
            };
        }
        return $valid;
    }
}
