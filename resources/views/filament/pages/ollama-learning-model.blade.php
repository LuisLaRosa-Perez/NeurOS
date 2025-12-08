<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            Estadísticas del Modelo de Aprendizaje (Ollama)
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-filament::card>
                <p class="text-lg font-medium">Total de Peticiones Exitosas:</p>
                <p class="text-3xl font-bold">{{ $this->totalRequests }}</p>
            </x-filament::card>

            <x-filament::card>
                <p class="text-lg font-medium">Últimas 3 Peticiones Exitosas:</p>
                @forelse ($this->latestRequests as $request)
                    <div class="mb-4 p-4 border rounded-lg bg-gray-50 dark:bg-gray-800">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Tipo: <span class="font-semibold">{{ $request->context['type'] ?? 'N/A' }}</span>,
                            Edad: <span class="font-semibold">{{ $request->context['age'] ?? 'N/A' }}</span>,
                            Tema: <span class="font-semibold">{{ $request->context['topic'] ?? 'N/A' }}</span>
                        </p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">
                            {{ $request->created_at->diffForHumans() }} por {{ $request->user->name ?? 'Invitado' }}
                        </p>
                        <p class="text-sm font-mono mt-2 truncate max-w-full">
                            Prompt: {{ Str::limit(json_decode($request->prompt, true)[1]['content'] ?? 'N/A', 150) }}
                        </p>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400">No hay peticiones exitosas registradas todavía.</p>
                @endforelse
            </x-filament::card>
        </div>
    </x-filament::section>
</x-filament-panels::page>
