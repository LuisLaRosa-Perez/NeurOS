<div>
    <div class="space-y-4">
        <div>
            <video id="webcam" autoplay playsinline class="w-full h-auto border border-gray-300 rounded-lg"></video>
            <canvas id="canvas" class="hidden"></canvas>
        </div>

        <div>
            <label for="prompt" class="block text-sm font-medium text-gray-700">Prompt</label>
            <textarea wire:model.defer="prompt" id="prompt" rows="2" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Describe what the AI should look for..."></textarea>
            @error('prompt') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="flex space-x-4">
            <x-filament::button tag="button" id="start-camera" color="warning">Start Camera</x-filament::button>
            <x-filament::button tag="button" id="stop-camera" color="warning" disabled>Stop Camera</x-filament::button>
            <x-filament::button tag="button" id="start-sending" color="warning" disabled>Start Analysis</x-filament::button>
            <x-filament::button tag="button" id="stop-sending" color="warning" disabled>Stop Analysis</x-filament::button>
        </div>
    </div>

    <div wire:ignore class="mt-6 p-4 bg-gray-50 rounded-md">
        <h3 class="text-lg font-medium text-gray-900">Live Description:</h3>
        <p id="result-text" class="mt-2 text-sm text-gray-600">
            The description will appear here...
        </p>
    </div>
    
    <div id="error-container" class="hidden mt-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md">
        <h3 class="text-lg font-medium">Error</h3>
        <p id="error-text" class="mt-2 text-sm"></p>
    </div>
</div>

@script
document.addEventListener('livewire:init', () => {
    const video = document.getElementById('webcam');
    const canvas = document.getElementById('canvas');
    const startCameraBtn = document.getElementById('start-camera');
    const stopCameraBtn = document.getElementById('stop-camera');
    const startSendingBtn = document.getElementById('start-sending');
    const stopSendingBtn = document.getElementById('stop-sending');
    const resultText = document.getElementById('result-text');
    const promptInput = document.getElementById('prompt');
    const errorContainer = document.getElementById('error-container');
    const errorText = document.getElementById('error-text');

    let stream;
    let sendingInterval;

    // 1. Camera Control
    startCameraBtn.addEventListener('click', async () => {
        if (navigator.mediaDevices.getUserMedia) {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: true });
                video.srcObject = stream;
                startCameraBtn.disabled = true;
                stopCameraBtn.disabled = false;
                startSendingBtn.disabled = false;
                hideError();
            } catch (error) {
                console.error("Error starting camera:", error);
                showError('Could not start camera. Please check permissions and ensure you are using a secure connection (https).');
            }
        } else {
            showError('getUserMedia is not supported by your browser.');
        }
    });

    stopCameraBtn.addEventListener('click', () => {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            video.srcObject = null;
            stopCameraBtn.disabled = true;
            startCameraBtn.disabled = false;
            startSendingBtn.disabled = true;
            if (sendingInterval) {
                stopSendingBtn.click();
            }
        }
    });
    
    // 2. Frame Sending Logic
    startSendingBtn.addEventListener('click', () => {
        if (!stream) return;

        startSendingBtn.disabled = true;
        stopSendingBtn.disabled = false;
        resultText.textContent = "Starting analysis...";
        hideError();

        sendingInterval = setInterval(async () => {
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            const frame = canvas.toDataURL('image/jpeg', 0.7);
            const prompt = promptInput.value;

            if (!prompt) {
                showError("Please enter a prompt.");
                stopSendingBtn.click();
                return;
            }

            try {
                const response = await fetch('/api/vlm-predict', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        image: frame,
                        prompt: prompt
                    })
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.error || 'Prediction request failed');
                }

                const data = await response.json();
                resultText.textContent = data.prediction;
                hideError();

            } catch (error) {
                console.error('Error sending frame:', error);
                showError(error.message);
                stopSendingBtn.click();
            }

        }, 3000); // Send a frame every 3 seconds
    });

    stopSendingBtn.addEventListener('click', () => {
        clearInterval(sendingInterval);
        stopSendingBtn.disabled = true;
        startSendingBtn.disabled = stream ? false : true;
        resultText.textContent = "Analysis stopped.";
    });

    // Helper functions for UI
    function showError(message) {
        errorText.textContent = message;
        errorContainer.classList.remove('hidden');
    }

    function hideError() {
        errorContainer.classList.add('hidden');
    }

    // Clean up on component destruction
    window.addEventListener('beforeunload', () => {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
        clearInterval(sendingInterval);
    });
});
@endscript