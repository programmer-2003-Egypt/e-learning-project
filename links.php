<?php
$pdo = new PDO("mysql:host=localhost;dbname=project;charset=utf8mb4", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

// Get the year dynamically from user input (default to 3 if not provided)
$year = isset($_GET['year']) ? (int)$_GET['year'] : 3;

// Fetch subjects from the database dynamically based on the selected year
$query = "SELECT subject FROM teachers WHERE year = :year";
$stmt = $pdo->prepare($query);
$stmt->execute(['year' => $year]);
$subjects = $stmt->fetchAll();

// Define icons for specific subjects
$icons = [
    "Quantum Physics" => "fas fa-atom",
    "Atomic Physics" => "fas fa-bomb",
    "Nuclear Physics" => "fas fa-radiation",
    "Optics" => "fas fa-lightbulb",
    "Cyber Security" => "fas fa-shield-alt",
    "Data Science" => "fas fa-database",
    "Machine Learning" => "fas fa-robot",
    "Web Development" => "fas fa-code",
    "Artificial Intelligence" => "fas fa-brain",
    "Cloud Computing" => "fas fa-cloud",
    "Blockchain" => "fas fa-link",
    "Cryptography" => "fas fa-key",
    "Networking" => "fas fa-network-wired",
    "Mobile Development" => "fas fa-mobile-alt",
    "Game Development" => "fas fa-gamepad",
    "Augmented Reality" => "fas fa-glasses",
    "Virtual Reality" => "fas fa-vr-cardboard",
    "Internet of Things" => "fas fa-microchip",
    "Software Engineering" => "fas fa-laptop-code",
    "DevOps" => "fas fa-tools",
    "Big Data" => "fas fa-server",
    "Embedded Systems" => "fas fa-microchip",
    "Electronics" => "fas fa-bolt",
    "Mathematics" => "fas fa-square-root-alt",
    "Astronomy" => "fas fa-star",
    "Robotics" => "fas fa-cogs",
    "Ethical Hacking" => "fas fa-user-secret",
    "Penetration Testing" => "fas fa-bug",
    "Forensics" => "fas fa-fingerprint"

   
];

// Default icon if subject not found in the list
$defaultIcon = "fas fa-book";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced Video Platform</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/plyr@3.6.2/dist/plyr.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        /* General Page Styles */
/* General Page Styles */
/* Global Reset */
/* Global Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body Styling */
body {
    background: linear-gradient(-45deg, rgb(20, 20, 20), rgb(50, 50, 50), rgb(80, 80, 80), rgb(30, 30, 30));
    background-size: 400% 400%;
    animation: gradientBG 12s ease-in-out infinite;
    color: #ffffff;
    font-family: 'Poppins', sans-serif;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    min-height: 100vh;
    margin: 0;
    overflow-y: auto;
    position: relative;

    &::before {
        content: "";
        position: absolute;
        width: 100%;
        height: 50%;
        background: radial-gradient(circle at center, rgba(255, 255, 255, 0.05) 0%, rgba(0, 0, 0, 0.95) 80%);
        z-index: -1;
    }
}

/* Animated Background */
@keyframes gradientBG {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}
.iframe-container {
    position: relative;
    width: 100%;
    padding-top: 40.25%; /* 16:9 aspect ratio */

    iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 50%;
        border: none;
    }

    .plyr__video-embed {
        width: 100%;
        height: 50vh; /* Full screen height */
    }
}

/* Checkbox Container */
.checkbox-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    gap: 20px;
    padding: 20px;
    background: #252525;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    width: fit-content;
    animation: fadeIn 0.8s ease-in-out;
}

button[type="submit"] {
    display: block;
    margin: 20px auto;
    padding: 10px 20px;
    background: #ff004c;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.3s ease;

    &:hover { background: #ff8a00; }
}

/* Custom Checkbox */
.custom-checkbox {
    display: none;
    
    & + label {
        position: relative;
        padding-left: 35px;
        cursor: pointer;
        font-size: 16px;
        transition: color 0.3s ease;

        &:before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 22px;
            height: 22px;
            border: 2px solid #ff004c;
            border-radius: 6px;
            background-color: #2d2d2d;
            transition: all 0.3s ease;
        }
    }

    &:checked + label {
        &:before {
            background-color: #ff004c;
            border-color: #ff8a00;
            box-shadow: 0 0 8px #ff004c;
        }

        &:after {
            content: '✔';
            position: absolute;
            left: 5px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
            color: #fff;
            font-weight: bold;
            transition: opacity 0.3s ease;
        }
    }
}

/* Loading Spinner */
.loading-container {
    display: none;
    margin-top: 20px;
}

.loading-spinner {
    border: 4px solid transparent;
    border-top: 4px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    width: 40px;
    height: 40px;
    margin: auto;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Video Card */
.video-card {
    width: 100%;
    max-width: 500px;
    margin: 20px auto;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    overflow: auto;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    background: #fff;
    text-align: center;
    position: relative;

    &:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }
}

@media (prefers-color-scheme: dark) {
    .video-card {
        background: #252525;
        color: #fff;
        box-shadow: 0 10px 30px rgba(255, 255, 255, 0.1);
    }
}
.year-input {
    width: 100%;
    max-width: 300px;
    padding: 12px;
    font-size: 18px;
    border: 2px solid #ccc;
    border-radius: 8px;
    margin-bottom: 16px;
    box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease-in-out;

    /* Nested styles */
    &:focus {
      border-color: #007bff;
      box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
      outline: none;
    }

    &:hover {
      border-color: #666;
    }

    &::placeholder {
      color: #999;
      opacity: 0.5;
    }

    &:disabled {
      background-color: #f5f5f5;
      cursor: not-allowed;
    }
  }
    </style>
</head>
<body>
<input 
  type="number" 
  id="yearInput" 
  placeholder="Enter Year between 2 and 4" 
  class="year-input"
  oninput="updateYear()"
/>

<div class="container mx-auto p-6 flex flex-col items-center">
    <!-- Custom checkbox form -->
    <form id="fieldsForm" class="space-y-4 mb-6">
        <div class="checkbox-container grid grid-cols-1 gap-4 justify-center text-center">
            <?php foreach ($subjects as $subject): 
                $subjectName = htmlspecialchars($subject['subject']);
                $iconClass = $icons[$subjectName] ?? $defaultIcon;
            ?>
                <div class="checkbox-item flex items-center justify-center">
                    <input type="checkbox" class="custom-checkbox hidden" id="<?= $subjectName; ?>" value="<?= $subjectName; ?>">
                    <label for="<?= $subjectName; ?>" class="flex items-center gap-2 cursor-pointer text-lg">
                        <i class="<?= $iconClass; ?>"></i> <?= $subjectName; ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded-lg shadow-md hover:bg-blue-700 transition">
            Submit
        </button>
    </form>

    <div id="loading" class="loading-container flex justify-center items-center">
        <div class="loading-spinner"></div>
    </div>
</div>


        <div id="videoSlider" class="video-slider"></div>
    </div>

    <!-- Script -->
    <script>
          let timeout;

function updateYear() {
    clearTimeout(timeout);
     // Clear previous timeout to prevent multiple redirects

    timeout = setTimeout(() => {
        const year = document.getElementById("yearInput").value;
        if (year) {
            window.location.href = "?year=" + year;
        }
    }, 1500);
}

      
      class VideoFetcher {
    constructor(apiKey, fieldsFormId, videoSliderId, loadingContainerId) {
        this.apiKey = apiKey;
        this.fieldsForm = document.getElementById(fieldsFormId);
        this.videoSlider = document.getElementById(videoSliderId);
        this.loadingContainer = document.getElementById(loadingContainerId);
        this.attachFormListener();
    }

    attachFormListener() {
        this.fieldsForm.addEventListener('submit', (e) => {
            e.preventDefault();
            let selectedFields = Array.from(e.target.querySelectorAll('input[type="checkbox"]:checked'))
                .map((checkbox) => checkbox.id);
            this.fetchVideoData(selectedFields);
        });
    }

    async fetchVideoData(fields) {
        this.loadingContainer.style.display = 'block';

        try {
            const response = await axios.get('https://www.googleapis.com/youtube/v3/search', {
                params: {
                    part: 'snippet',
                    q: fields.join(','),
                    key: this.apiKey,
                    type: 'video',
                    maxResults: 20
                }
            });

            let videos = response.data.items;
            this.shuffleArray(videos);
            let selectedVideos = videos.slice(0, 8);

            this.renderVideos(selectedVideos);
        } catch (error) {
            console.error("Error fetching videos:", error);
        } finally {
            this.loadingContainer.style.display = 'none';
        }
    }

    shuffleArray(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
    }

    renderVideos(videos) {
        this.videoSlider.innerHTML = '';

        videos.forEach((video, index) => {
            if (index % 4 === 0 && index !== 0) {
                this.videoSlider.innerHTML += '<hr>';
            }

            const videoCard = `
                <div class="video-card group relative bg-white shadow-lg rounded-lg overflow-hidden 
                    transition-transform transform hover:scale-105 duration-300 cursor-pointer" 
                    onclick="videoHandler.openVideo('${video.id.videoId}', '${video.snippet.title.replace(/'/g, "\\'")}')">
                    
                    <div class="relative">
                        <img src="${video.snippet.thumbnails.high.url}" alt="${video.snippet.title}" 
                             class="w-full h-56 object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-20 opacity-0 
                             group-hover:opacity-100 flex items-center justify-center transition-opacity duration-300">
                            <svg class="w-14 h-14 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M7 6v12l10-6z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-500 transition-colors duration-200">
                            ${video.snippet.title}
                        </h3>
                        <p class="text-sm text-gray-600 mt-2 line-clamp-2">${video.snippet.description}</p>
                    </div>

                    <div class="absolute top-2 right-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded-full">
                        Live
                    </div>
                </div>
            `;

            this.videoSlider.innerHTML += videoCard;
        });
    }
}

class VideoHandler {
    openVideo(videoId, videoTitle) {
        Swal.fire({
            title: 'Watch Video',
            html: `
                <div class="iframe-container">
                    <iframe id="videoEmbed" src="https://www.youtube.com/embed/${videoId}?autoplay=1" 
                            frameborder="0" allow="autoplay; encrypted-media" allowfullscreen 
                            class="plyr__video-embed">
                    </iframe>
                </div>
                <button id="saveVideoBtn" class="mt-4 bg-green-500 text-white px-4 py-2 rounded">
                    Save Video
                </button>
            `,
            showCloseButton: true,
            width: '80%',
            padding: '20px',
            showConfirmButton: false
        });

        // Attach event listener correctly
        setTimeout(() => {
            const saveButton = document.getElementById('saveVideoBtn');
            if (saveButton) {
                saveButton.addEventListener('click', () => {
                    this.saveVideoToDatabase(videoId, videoTitle);
                });
            }
        }, 100);
    }

    async saveVideoToDatabase(videoId, videoTitle) {
        try {
            await axios.post('save-video.php', {
                videoId: videoId,
                videoTitle: videoTitle
            });

            Swal.fire({
                title: 'Success!',
                text: 'The video has been saved to your collection.',
                icon: 'success',
                confirmButtonText: 'Okay'
            });
        } catch (error) {
            Swal.fire({
                title: 'Error!',
                text: 'There was an issue saving the video.',
                icon: 'error',
                confirmButtonText: 'Okay'
            });
        }
    }
}

// Instantiate the classes


// Initialize classes
const API_KEY = 'AIzaSyAvfxFp-_RgL8GgHLhSVy15rwlQBzu7zF4';
const videoFetcher = new VideoFetcher(API_KEY, 'fieldsForm', 'videoSlider', 'loading');
const videoHandler = new VideoHandler();

    </script>
</body>
</html>
