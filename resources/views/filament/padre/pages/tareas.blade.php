<x-filament-panels::page>
    @if ($this->gameTask)
        {{-- Vista del Juego con Temporizador y Bloqueo --}}
        <div>
            <x-filament::button
                icon="heroicon-o-arrow-left"
                wire:click="stopPlaying"
                outlined
            >
                Volver a la lista de actividades
            </x-filament::button>

            <x-filament::section class="mt-4">
                <x-slot name="heading">
                    {{ str_replace('*', '', $this->gameTask->name) }}
                </x-slot>

                <div class="aspect-w-16 aspect-h-9 w-full" style="height: 70vh;">
                    <iframe src="{{ $this->gameTask->game_link }}" frameborder="0" allowfullscreen class="h-full w-full rounded-lg"></iframe>
                </div>
            </x-filament::section>
        </div>
    @else
        {{-- Vista de Lista de Tareas y Examen --}}
        {{-- Botón Volver --}}
        <div class="fi-ta-header-actions flex items-center gap-x-3 px-4 py-4 sm:px-6">
            @if ($this->selectedTask)
                <x-filament::button
                    icon="heroicon-o-arrow-left"
                    wire:click="deselectTask"
                    outlined
                >
                    Volver a la lista
                </x-filament::button>
            @elseif ($this->category)
                {{-- No mostrar nada si estamos en una vista de categoría --}}
            @else
                <x-filament::button
                    icon="heroicon-o-arrow-left"
                    tag="a"
                    href="{{ url()->previous() }}"
                    outlined
                >
                    Volver
                </x-filament::button>
            @endif
        </div>

        <div class="fi-page-content">
            <div class="fi-page-content-main">
                @if ($this->selectedTask)
                    {{-- Mostrar el examen de la tarea seleccionada --}}
                    @include('filament.padre.pages.partials.task-exam', ['task' => $this->selectedTask])
                @else
                    {{-- Mostrar la lista de categorías y tareas --}}
                    @if ($this->groupedTasks->isEmpty())
                        <x-filament::section>
                            <x-slot name="heading">No hay tareas disponibles</x-slot>
                            <p>No se encontraron tareas.</p>
                        </x-filament::section>
                    @else
                        @foreach ($this->groupedTasks as $category => $tasks)
                            <x-filament::section id="{{ Str::slug($category) }}" class="mb-6">
                                <x-slot name="heading">{{ ucfirst(str_replace('_', ' ', $category)) }}</x-slot>

                                @php
                                    $pendingTasks = $tasks->filter(fn($task) => !$task->completions_exists);
                                @endphp

                                @if ($pendingTasks->isEmpty())
                                    <p class="text-gray-500 dark:text-gray-400">No hay tareas pendientes en esta categoría.</p>
                                @else
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @foreach ($pendingTasks as $task)
                                            <x-filament::section class="transition flex flex-col h-full">
                                                <x-slot name="heading">{{ str_replace('*', '', $task->name) }}</x-slot>
                                                
                                                <div class="flex-grow">
                                                    @if ($category === 'juegos_de_recreacion')
                                                        @if ($task->preview_image_url)
                                                            <img src="{{ Storage::url($task->preview_image_url) }}" alt="Previsualización" class="mb-4 rounded-lg object-cover w-full h-32">
                                                        @endif
                                                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">{!! $task->description !!}</p>
                                                    @else
                                                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">{!! $task->description !!}</p>
                                                    @endif
                                                </div>

                                                <div class="mt-6 flex justify-center items-center gap-x-2">
                                                    @if ($category === 'juegos_de_recreacion')
                                                        <x-filament::button
                                                            wire:click="playGame({{ $task->id }})"
                                                            size="sm"
                                                            icon="heroicon-o-play"
                                                        >
                                                            Jugar
                                                        </x-filament::button>
                                                    @else
                                                        <x-filament::button
                                                            wire:click="selectTask({{ $task->id }})"
                                                            size="sm"
                                                        >
                                                            Realizar Examen
                                                        </x-filament::button>
                                                    @endif
                                                </div>
                                            </x-filament::section>
                                        @endforeach
                                    </div>
                                @endif
                            </x-filament::section>
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
    @endif
</x-filament-panels::page>