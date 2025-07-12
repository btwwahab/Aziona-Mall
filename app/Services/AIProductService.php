<?php
// filepath: app/Services/AIProductService.php
namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\Order;

class AIProductService
{
    private $client;
    private $apiKey;
    private $baseUrl = 'https://api.groq.com/openai/v1';

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('GROQ_API_KEY');
        
    }

    public function generateChatResponse($message, $context = [])
    {
        $prompt = "You are a helpful e-commerce shopping assistant. Help customers with product questions, shopping advice, and general support.\n\n";
        
        if (!empty($context)) {
            $prompt .= "Conversation history:\n";
            foreach ($context as $msg) {
                $prompt .= $msg['sender'] . ': ' . $msg['content'] . "\n";
            }
        }
        
        $prompt .= "Customer: " . $message . "\n\n";
        $prompt .= "Respond as a friendly shopping assistant (keep response under 100 words):";
        
        return $this->callGrokAPI($prompt);
    }

    public function generateProductRecommendations($userId, $productHistory = [])
    {
        $prompt = "Based on this customer's purchase history, suggest 5 related products they might like: " . 
                 implode(', ', $productHistory) . ". Focus on complementary items and popular choices.";
        
        return $this->callGrokAPI($prompt);
    }

    // SHARED API CALL METHOD
    public function callGrokAPI($prompt)
    {
        try {
            if (empty($this->apiKey)) {
                Log::error('Groq API key not configured');
                return 'AI service configuration missing. Please contact support.';
            }

            $response = $this->client->post($this->baseUrl . '/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'llama3-8b-8192',
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'max_tokens' => 500,
                    'temperature' => 0.7
                ],
                'timeout' => 30
            ]);

            $data = json_decode($response->getBody(), true);
            
            if (isset($data['choices'][0]['message']['content'])) {
                return $data['choices'][0]['message']['content'];
            } else {
                Log::error('Unexpected API response format', ['response' => $data]);
                return 'AI service temporarily unavailable. Please try again later.';
            }
            
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return 'AI service temporarily unavailable. Please try again later.';
        } catch (\Exception $e) {
            return 'AI service temporarily unavailable. Please try again later.';
        }
    }

    
}