<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class OpenaiController extends Controller
{
    public function foobar()
    {
        $result = OpenAI::completions()->create([
            'model' => 'text-davinci-003',
            'prompt' => 'PHP is',
            'max_tokens' => 500,
        ]);

        echo $result['choices'][0]['text'];
    }
}
