<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * GeminiService - AI API wrapper for VPC generation
 * 
 * This service acts as a facade that can use either:
 * - Direct API calls (legacy mode)
 * - ReasoningService for ReAct-based generation (enhanced mode)
 */
class GeminiService
{
    protected string $provider;
    protected string $apiKey;
    protected string $model;
    protected int $maxRetries = 3;
    protected bool $useReActReasoning = true;

    public function __construct()
    {
        $this->provider = config('services.ai_provider', 'groq');
        
        if ($this->provider === 'groq') {
            $this->apiKey = config('services.groq.api_key');
            $this->model = config('services.groq.model', 'llama-3.3-70b-versatile');
        } else {
            $this->apiKey = config('services.gemini.api_key');
            $this->model = config('services.gemini.model', 'gemini-2.0-flash');
        }
    }

    /**
     * Analyze empathy map and generate customer profile
     * Uses ReAct reasoning when enabled
     */
    public function generateCustomerProfile(array $empathyData): array
    {
        // Use ReAct-based reasoning for enhanced generation
        if ($this->useReActReasoning) {
            $reasoningService = app(ReasoningService::class);
            $result = $reasoningService->generateCustomerProfileWithReasoning($empathyData);
            
            return [
                'customer_jobs' => $result['customer_jobs'],
                'customer_pains' => $result['customer_pains'],
                'customer_gains' => $result['customer_gains'],
                'reasoning' => $result['summary'] ?? '',
                'reasoning_trace' => $result['reasoning_trace'] ?? [],
            ];
        }
        
        // Legacy mode (fallback)
        $prompt = $this->buildPrompt($empathyData);
        
        if ($this->provider === 'groq') {
            return $this->callGroqApi($prompt);
        }
        
        return $this->callGeminiApi($prompt);
    }

    /**
     * Generate Value Map from approved Customer Profile
     * Uses ReAct reasoning for systematic analysis
     */
    public function generateValueMap(array $customerProfile): array
    {
        $reasoningService = app(ReasoningService::class);
        return $reasoningService->generateValueMapWithReasoning($customerProfile);
    }

    /**
     * Call Groq API
     */
    protected function callGroqApi(string $prompt): array
    {
        $lastException = null;
        
        for ($attempt = 1; $attempt <= $this->maxRetries; $attempt++) {
            try {
                $response = Http::timeout(60)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $this->apiKey,
                        'Content-Type' => 'application/json',
                    ])
                    ->post('https://api.groq.com/openai/v1/chat/completions', [
                        'model' => $this->model,
                        'messages' => [
                            [
                                'role' => 'user',
                                'content' => $prompt
                            ]
                        ],
                        'temperature' => 0.4,
                        'max_tokens' => 3072,
                    ]);

                if ($response->successful()) {
                    $result = $response->json();
                    $text = $result['choices'][0]['message']['content'] ?? '';
                    return $this->parseJsonResponse($text);
                }
                
                $errorBody = $response->json();
                $statusCode = $response->status();
                $errorMessage = $errorBody['error']['message'] ?? 'Unknown error';
                
                Log::error("Groq API Error (Attempt {$attempt})", [
                    'status' => $statusCode,
                    'error' => $errorMessage,
                ]);
                
                if ($statusCode === 429) {
                    if ($attempt < $this->maxRetries) {
                        sleep(pow(2, $attempt));
                        continue;
                    }
                    throw new Exception('Rate limit exceeded. Please wait and try again.');
                }
                
                throw new Exception($errorMessage);
                
            } catch (Exception $e) {
                $lastException = $e;
                if ($attempt >= $this->maxRetries) {
                    throw $e;
                }
                sleep(pow(2, $attempt));
            }
        }
        
        throw $lastException ?? new Exception('AI generation failed.');
    }

    /**
     * Call Gemini API (fallback)
     */
    protected function callGeminiApi(string $prompt): array
    {
        $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent";
        
        $response = Http::timeout(60)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($apiUrl . '?key=' . $this->apiKey, [
                'contents' => [['parts' => [['text' => $prompt]]]],
                'generationConfig' => [
                    'temperature' => 0.4,
                    'maxOutputTokens' => 3072,
                ]
            ]);

        if (!$response->successful()) {
            $error = $response->json()['error']['message'] ?? 'API error';
            throw new Exception($error);
        }

        $text = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? '';
        return $this->parseJsonResponse($text);
    }

    /**
     * Parse JSON response from AI
     */
    protected function parseJsonResponse(string $text): array
    {
        // Remove markdown code blocks if present
        $text = preg_replace('/```json\s*|\s*```/', '', $text);
        $text = trim($text);
        
        $data = json_decode($text, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Invalid JSON from AI', ['response' => $text]);
            throw new Exception('Invalid JSON response from AI.');
        }
        
        if (!isset($data['vpc_points']) || !is_array($data['vpc_points'])) {
            throw new Exception('Missing vpc_points in AI response.');
        }
        
        // Categorize points
        $customerJobs = [];
        $customerPains = [];
        $customerGains = [];
        
        foreach ($data['vpc_points'] as $point) {
            if (!isset($point['type'], $point['description'])) continue;
            
            switch ($point['type']) {
                case 'customer_job':
                    $customerJobs[] = $point['description'];
                    break;
                case 'pain':
                    $customerPains[] = $point['description'];
                    break;
                case 'gain':
                    $customerGains[] = $point['description'];
                    break;
            }
        }
        
        if (empty($customerJobs) && empty($customerPains) && empty($customerGains)) {
            throw new Exception('No valid points found in AI response.');
        }
        
        return [
            'customer_jobs' => $customerJobs,
            'customer_pains' => $customerPains,
            'customer_gains' => $customerGains,
            'vpc_points' => $data['vpc_points'],
        ];
    }

    /**
     * Build the prompt for AI
     */
    protected function buildPrompt(array $empathyData): string
    {
        $says = implode("\n", $empathyData['says'] ?? []);
        $thinks = implode("\n", $empathyData['thinks'] ?? []);
        $does = implode("\n", $empathyData['does'] ?? []);
        $feels = implode("\n", $empathyData['feels'] ?? []);

        return <<<PROMPT
ROLE
You are an expert Business Analyst specializing in Value Proposition Design.

TASK
Analyze this Empathy Map data and create a Customer Profile.

INPUT
1. Says: "{$says}"
2. Thinks: "{$thinks}" 
3. Does: "{$does}" 
4. Feels: "{$feels}"

INSTRUCTIONS
Extract insights into three categories:
1. Customer Jobs: What is the user trying to accomplish?
2. Pains: What frustrates or blocks them?
3. Gains: What outcomes do they want?

OUTPUT
Return ONLY valid JSON (no markdown, no extra text):
{
  "vpc_points": [
    { "type": "customer_job", "description": "Description in Indonesian" },
    { "type": "pain", "description": "Description in Indonesian" },
    { "type": "gain", "description": "Description in Indonesian" }
  ]
}

Generate 3-5 points for each type. Be specific and actionable.
PROMPT;
    }
}
