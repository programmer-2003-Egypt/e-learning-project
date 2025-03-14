<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stream Video and Save</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        /* Animated Gradient Background */
        @keyframes gradient-animation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(-45deg,rgb(71, 22, 11),rgb(195, 186, 168),rgb(2, 41, 9),rgb(89, 105, 175));
            background-size: 400% 400%;
            animation: gradient-animation 15s infinite alternate ease-in-out;
        }

        .container {
            text-align: center;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            width: 100vh;
        }

        h2 {
            font-size: 24px;
            font-weight: bold;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-bottom: 15px;
        }

        .input-field {
            margin-bottom: 15px;
            display: flex;
            justify-content: center;
        }

        input[type="text"] {
            padding: 10px;
            font-size: 16px;
            width: 100%;
            border-radius: 5px;
            border: 1px solid #ccc;
            outline: none;
            transition: all 0.3s ease-in-out;
        }

        input[type="text"]:focus {
            border-color: #ff5733;
            box-shadow: 0 0 8px rgba(255, 87, 51, 0.5);
        }

        /* Buttons */
        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 15px;
            margin: 5px;
            font-size: 16px;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: transform 0.2s, background 0.3s;
        }

        .btn i {
            font-size: 18px;
        }

        .btn:hover {
            transform: scale(1.05);
        }

        .btn-primary {
            background: #007bff;
        }

        .btn-primary:hover {
            background: #0056b3;
        }

        .btn-danger {
            background: #dc3545;
        }

        .btn-danger:hover {
            background: #a71d2a;
        }

        .btn-warning {
            background: #ffc107;
            color: black;
        }

        .btn-warning:hover {
            background: #d39e00;
        }

        .btn-muted {
            background: #6c757d;
        }

        .btn-muted:hover {
            background: #545b62;
        }

        .hidden {
            display: none;
        }

        /* Video Container */
        .video-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
            background: black;
            width: 100%;
            height: 200px;
            border-radius: 10px;
            border: 3px solid #ff5733;
            box-shadow: 0 0 10px rgba(255, 87, 51, 0.7);
            overflow: hidden;
        }

        video {
            width: 100%;
            height: 100%;
            border-radius: 10px;
        }

        /* Timer */
        .timer {
            font-size: 20px;
            font-weight: bold;
            margin-top: 15px;
            padding: 8px 12px;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            border-radius: 5px;
            display: inline-block;
        }

        /* Controls */
        .controls {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 15px;
        }
        .custom-swal-popup {
    border-radius: 12px;
    box-shadow: 0 0 15px rgba(255, 87, 51, 0.5);
}

.custom-swal-title {
    font-size: 22px;
    text-transform: uppercase;
}

.custom-swal-text {
    font-size: 16px;
}


    </style>
</head>
<body>

     <div class="container">
        <h2>
            <i class="fa-solid fa-broadcast-tower" style="color: #ff5733;"></i> Live Streaming
        </h2>

        <div class="input-field">
            <input type="text" id="videoNameInput" placeholder="Enter video name">
        </div>

        <button id="startStreamBtn" class="btn btn-primary">
            <i class="fa-solid fa-play-circle"></i> Start Stream
        </button>
        <button id="stopStreamBtn" class="btn btn-danger hidden">
            <i class="fa-solid fa-stop-circle"></i> Stop Stream
        </button>
        <button id="muteBtn" class="btn btn-muted hidden">
            <i class="fa-solid fa-volume-mute"></i> Mute
        </button>
        <button id="resumeBtn" class="btn btn-warning hidden">
            <i class="fa-solid fa-pause-circle"></i> Pause
        </button>

        <div class="video-container">
            <video id="videoPlayer" autoplay muted playsinline></video>
        </div>

        <div class="timer" id="timer">
            <i class="fa-solid fa-clock"></i> 00:00
        </div>

        <div class="controls">
            <button id="muteBtn" class="btn btn-muted">
                <i class="fa-solid fa-volume-mute"></i> Mute
            </button>
            <button id="resumeBtn" class="btn btn-warning hidden">
                <i class="fa-solid fa-pause-circle"></i> Pause
            </button>
        </div>
    </div>




    <script>
        let mediaRecorder;
        let recordedChunks = [];
        let videoStream;
        let streamStarted = false;
        let timerInterval;
        let elapsedTime = 0;

        const videoNameInput = document.getElementById("videoNameInput");
        const startStreamBtn = document.getElementById("startStreamBtn");
        const stopStreamBtn = document.getElementById("stopStreamBtn");
        const muteBtn = document.getElementById("muteBtn");
        const resumeBtn = document.getElementById("resumeBtn");
        const videoPlayer = document.getElementById("videoPlayer");
        const timerElement = document.getElementById("timer");

        const configuration = {
            iceServers: [{ urls: "stun:stun.l.google.com:19302" }]
        };
        let peerConnection;

        startStreamBtn.addEventListener("click", async () => {
            if (!videoNameInput.value) {
                Swal.fire({
                    title: 'Please fill input name',
                    text: 'Enter a name for the video before starting the stream.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }

            try {
                videoStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
                videoPlayer.srcObject = videoStream;

                peerConnection = new RTCPeerConnection(configuration);
                videoStream.getTracks().forEach(track => peerConnection.addTrack(track, videoStream));

                peerConnection.onicecandidate = event => {
                    if (event.candidate) {
                        console.log("New ICE candidate:", event.candidate);
                    }
                };

                peerConnection.ontrack = event => {
                    console.log("Receiving stream", event.streams[0]);
                };

                await fetch("notify_students.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                        lecture_name: videoNameInput.value,
                        timestamp: new Date().toISOString(),
                        status: "Live"
                    })
                });

                recordedChunks = []; // Clear previous recordings
                mediaRecorder = new MediaRecorder(videoStream);

                mediaRecorder.ondataavailable = event => recordedChunks.push(event.data);

                mediaRecorder.onstop = async () => {
                    const blob = new Blob(recordedChunks, { type: 'video/webm' });
                    const formData = new FormData();
                    formData.append('video', blob, `${videoNameInput.value}.webm`);
                    formData.append('filename', videoNameInput.value);

                    try {
                        const response = await fetch('upload_lectures.php', {
                            method: 'POST',
                            body: formData,
                        });

                        const result = await response.json();

                        if (result.success) {
                            Swal.fire({
                                title: '✅ Stream Saved!',
                                text: 'Your stream has been saved successfully.',
                                icon: 'success',
                                confirmButtonText: 'Download',
                                showCancelButton: true,
                                cancelButtonText: 'Close'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    const downloadLink = document.createElement('a');
                                    downloadLink.href = result.file;
                                    downloadLink.download = `${videoNameInput.value}.webm`;
                                    downloadLink.click();
                                }
                            });
                        } else {
                            Swal.fire({
                                title: '❌ Error!',
                                text: result.error,
                                icon: 'error'
                            });
                        }
                    } catch (error) {
                        Swal.fire({
                            title: '❌ Save Failed!',
                            text: 'Unable to save the stream. Please try again later.',
                            icon: 'error'
                        });
                    }
                };

                mediaRecorder.start();
                streamStarted = true;

                startStreamBtn.classList.add('hidden');
                stopStreamBtn.classList.remove('hidden');
                muteBtn.classList.remove('hidden');
                resumeBtn.classList.remove('hidden');

                timerInterval = setInterval(() => {
                    elapsedTime++;
                    let minutes = Math.floor(elapsedTime / 60);
                    let seconds = elapsedTime % 60;
                    timerElement.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                }, 1000);

            } catch (error) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to access camera or microphone.',
                    icon: 'error'
                });
            }
        });

        stopStreamBtn.addEventListener("click", () => {
            if (mediaRecorder && mediaRecorder.state !== 'inactive') {
                mediaRecorder.stop();
                videoStream.getTracks().forEach(track => track.stop());
            }
            clearInterval(timerInterval);
        });

        muteBtn.addEventListener("click", () => {
            const audioTrack = videoStream.getAudioTracks()[0];
            if (audioTrack) {
                audioTrack.enabled = !audioTrack.enabled;
                muteBtn.textContent = audioTrack.enabled ? 'Mute' : 'Unmute';
            }
        });

        resumeBtn.addEventListener("click", () => {
            if (mediaRecorder.state === 'paused') {
                mediaRecorder.resume();
                resumeBtn.textContent = 'Pause';
            } else {
                mediaRecorder.pause();
                resumeBtn.textContent = 'Resume';
            }
        });

        window.addEventListener('beforeunload', async (event) => {
            if (streamStarted) {
                event.preventDefault();
                event.returnValue = '';

                const { value } = await Swal.fire({
                    title: '⚠️ Unsaved Video!',
                    text: 'Your video recording is still in progress. Do you want to save it before leaving?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Save',
                    cancelButtonText: 'Discard'
                });

                if (value === 'save' && mediaRecorder.state !== 'inactive') {
                    mediaRecorder.stop();
                }
            }
        });

    </script>

</body>
</html>
