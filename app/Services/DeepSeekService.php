<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DeepSeekService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.openai.key');
        $this->baseUrl = config('services.openai.base_url');

        if (empty($this->apiKey)) {
            throw new \Exception('OpenAI API key (for OpenRouter/DeepSeek) not configured.');
        }
        if (empty($this->baseUrl)) {
            throw new \Exception('OpenAI Base URL (for OpenRouter/DeepSeek) not configured.');
        }
    }

    /**
     * Make a request to the DeepSeek API.
     *
     * @param array $messages
     * @param string $model
     * @param float $temperature
     * @return array|null
     */
    protected function makeRequest(array $messages, string $model = 'DeepSeek: DeepSeek V3.1 (free)', float $temperature = 0.7): ?array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/chat/completions", [
            'model' => $model,
            'messages' => $messages,
            'temperature' => $temperature,
            'stream' => false, // For now, we don't need streaming
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        // Log error or handle it appropriately
        \Log::error('DeepSeek API Error: ' . $response->body());
        return null;
    }

    /**
     * Generate 3 reading comprehension topics based on age.
     *
     * @param int $age
     * @return array|null
     */
    public function generateTopics(int $age): ?array
    {
        $prompt = "Suggest 3 diverse and engaging reading comprehension topics suitable for a child of {$age} years old. Provide only the topics, one per line, without numbering or any other text. Ensure they are distinct and age-appropriate.";

        $messages = [
            ['role' => 'system', 'content' => 'You are a helpful assistant that suggests reading comprehension topics.'],
            ['role' => 'user', 'content' => $prompt],
        ];

        $result = $this->makeRequest($messages, 'deepseek-chat', 0.8);

        if ($result && isset($result['choices'][0]['message']['content'])) {
            $topicsString = trim($result['choices'][0]['message']['content']);
            return array_filter(array_map('trim', explode("\n", $topicsString)));
        }

        return null;
    }

    /**
     * Generate a reading text and 3-5 comprehension questions based on age and topic.
     *
     * @param int $age
     * @param string $topic
     * @return array|null ['text' => '...', 'questions' => ['...', '...', ...]]
     */
    public function generateTask(int $age, string $topic): ?array
    {
        $prompt = "Generate a reading comprehension text and 3-5 questions for a child of {$age} years old on the topic: '{$topic}'.\n\n"
                . "Format the output as follows:\n"
                . "TEXT:\n[The reading text here]\n\n"
                . "QUESTIONS:\n[Question 1]\n[Question 2]\n[Question 3]\n[Question 4 (optional)]\n[Question 5 (optional)]\n\n"
                . "Ensure the text is engaging and the questions directly relate to the text and are age-appropriate.";

        $messages = [
            ['role' => 'system', 'content' => 'You are a helpful assistant that generates reading comprehension tasks.'],
            ['role' => 'user', 'content' => $prompt],
        ];

        $result = $this->makeRequest($messages, 'deepseek-chat', 0.7);

        if ($result && isset($result['choices'][0]['message']['content'])) {
            $content = trim($result['choices'][0]['message']['content']);

            if (preg_match('/TEXT:\s*(.*?)\s*QUESTIONS:\s*(.*)/s', $content, $matches)) {
                $text = trim($matches[1]);
                $questionsString = trim($matches[2]);
                $questions = array_filter(array_map('trim', explode("\n", $questionsString)));

                return [
                    'text' => $text,
                    'questions' => $questions,
                ];
            }
        }

        return null;
    }
}
