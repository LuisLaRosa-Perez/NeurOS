import dotenv from 'dotenv';
import OpenAI from 'openai';
dotenv.config();

const openai = new OpenAI({
    apiKey: process.env.DEEPSEEK_API_KEY,
    baseURL: process.env.DEEPSEEK_API_BASE_URL || 'https://openrouter.ai/api/v1',

});

async function obtenerRespuesta(prompt) {
    try{
        const response = await openai.chat.completions.create({
            model: 'deepseek/deepseek-r1:free',
            messages: [
                { role: 'user', content: prompt }
            ],
        });
        console.log(chat.choices[0].message.content);
    
  }catch (error) {
        console.error('Error al obtener la respuesta de DeepSeek:', error);
    }
}
obtenerRespuesta("");