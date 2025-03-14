<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'project');

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch videos from the database
$sql = "SELECT id, filename, filepath, uploaded_at,doctor_name FROM lectures ORDER BY uploaded_at DESC";
$result = $conn->query($sql);

// Count total number of videos
$count_sql = "SELECT COUNT(*) as total FROM lectures";
$count_result = $conn->query($count_sql);
$count_row = $count_result->fetch_assoc();
$total_videos = $count_row['total'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Recordings</title>
    
    <!-- Scripts and Styles -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2"></script>
    <script src="https://cdn.jsdelivr.net/npm/tesseract.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- Plyr.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/plyr@3.6.6/dist/plyr.css">
    <script src="https://cdn.jsdelivr.net/npm/plyr@3.6.6/dist/plyr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>


    

    <style>
    body {
    font-family: 'Arial', sans-serif;
    background: linear-gradient(135deg, green, grey);
    padding: 40px 0;
    color: #333;
    display: flex;
    flex-direction: column;
    align-items: center;
    min-height: 100vh;

    h1 {
        font-size: 3rem;
        margin-bottom: 40px;
        color: #4A90E2;
        text-align: center;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
    }

    .search-container {
        margin-bottom: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;

        .search-input {
            padding: 12px 20px;
            font-size: 1.5rem;
            width: 80%;
            max-width: 600px;
            border-radius: 25px;
            border: 2px solid #4A90E2;
            transition: border-color 0.3s, box-shadow 0.3s;

            &:focus {
                border-color: #357ABD;
                outline: none;
                box-shadow: 0 0 5px rgba(53, 123, 189, 0.5);
            }
        }
    }

    .video-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 40px;
        justify-content: center;
        width: 90%;
        margin-bottom: 40px;

        .video-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: all 0.3s ease;
            height: fit-content;
            display: flex;
            flex-direction: column;
            align-items: center;

            &:hover {
                transform: translateY(-8px);
                box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
                border: 2px solid white;
            }

            h3 {
                font-size: 1.5rem;
                margin-top: 15px;
                color: #333;
                font-weight: 600;
                text-align: center;
            }

            .upload-date {
                font-size: 1rem;
                color: #999;
                text-align: center;
                margin-top: 10px;
            }

            a {
                display: inline-block;
                margin-top: 10px;
                padding: 12px 25px;
                background-color: #4A90E2;
                color: white;
                text-decoration: none;
                transition: background-color 0.3s, transform 0.3s;

                &:hover {
                    background-color: #357ABD;
                    transform: scale(1.05);
                }
            }
        }
    }

    .controls-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;

        .control-btn {
            padding: 10px;
            background: #4A90E2;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            align-items: center;

            i {
                margin-right: 8px;
            }

            &:hover {
                background: #357ABD;
            }

            &:focus {
                outline: none;
            }

            &[title]:hover::after {
                content: attr(title);
                position: absolute;
                top: -25px;
                left: 50%;
                transform: translateX(-50%);
                background-color: #333;
                color: white;
                padding: 5px 10px;
                border-radius: 5px;
                font-size: 12px;
                white-space: nowrap;
            }
        }

        span {
            font-size: 1.2rem;
            color: #333;
        }
    }

    .plyr__progress-bar-container {
        width: 100%;
        height: 10px;
        background: #e0e0e0;
        border-radius: 5px;
        margin-top: 10px;

        .plyr__progress-bar {
            height: 100%;
            background: linear-gradient(to right, #4A90E2, #357ABD);
            border-radius: 5px;
            width: 0%;
            transition: width 0.1s ease;
        }
    }

    // RESPONSIVENESS
    @media (max-width: 1024px) {
        .video-container {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        h1 {
            font-size: 2.5rem;
        }

        .search-container {
            .search-input {
                font-size: 1.2rem;
                width: 90%;
            }
        }

        .video-container {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .controls-container {
            flex-direction: column;
            align-items: center;

            .control-btn {
                margin-top: 10px;
            }
        }
    }
}


    </style>
</head>
<body>
<h1 class="text-3xl font-bold text-center text-gray-800 dark:text-gray-200 mb-6 flex items-center justify-center gap-2">
    🎥 <span>Saved Recordings</span> <i class="fa fa-folder-open text-blue-500"></i>
</h1>
<?php
echo "<p class='text-center text-2xl font-bold text-white bg-green-500 p-4 rounded-lg shadow-lg'>
        🎥 Total Videos: <span class='text-yellow-300'>$total_videos</span>
      </p>";
?>


<!-- Search Bar with Tailwind Styling -->
<div class="flex justify-center mb-6 relative w-full md:w-2/3 lg:w-1/2 mx-auto">
    <i class="fa fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
    <input 
        class="w-full p-3 pl-10 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 dark:bg-gray-800 dark:text-gray-200" 
        type="text" 
        id="search" 
        placeholder="Search by video title..." 
        oninput="searchVideos()"
    >
</div>

<!-- Video Cards Container -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-4" id="video-container">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="bg-white dark:bg-gray-900 shadow-lg rounded-lg overflow-hidden transform hover:scale-105 transition-all duration-300 border border-gray-200 dark:border-gray-700">
                <div class="relative">
                    <video id="video-<?= $row['id'] ?>" class="w-full h-56 object-cover rounded-t-lg" controls data-plyr="true">
                        <source src="<?= htmlspecialchars($row['filepath']) ?>" type="video/webm">
                        Your browser does not support the video tag.
                    </video>
                </div>
 <!-- Uploaded By (Doctor Name) -->
                 <p class="text-sm text-gray-700 dark:text-gray-300 flex items-center gap-1">
                            <i class="fa fa-user-md text-green-500"></i> Uploaded by: 
                            <span class="font-medium"><?= !empty($row['doctor_name']) ? htmlspecialchars($row['doctor_name']) : 'Unknown' ?></span>

                        </p>
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 truncate flex items-center gap-2">
                        <i class="fa fa-file-video text-blue-500"></i> <?= htmlspecialchars($row['filename']) ?>
                    </h3>
                    
                    <!-- Upload Date -->
                    <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1">
                        <i class="fa fa-calendar-alt"></i> Uploaded on: <?= date('F j, Y, g:i A', strtotime($row['uploaded_at'])) ?>
                    </p>

                    <!-- Video Progress Bar -->
                    <div class="relative w-full bg-gray-300 dark:bg-gray-700 rounded-full h-2 my-2 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-2 rounded-full transition-all duration-300" id="progress-<?= $row['id'] ?>" style="width: 0%;"></div>
                    </div>
<!-- Video Time Info -->
<div class="flex justify-between items-center text-sm font-medium text-gray-700 dark:text-gray-300 mt-2 px-2 py-1 bg-gray-100 dark:bg-gray-800 rounded-lg shadow-sm border border-gray-300 dark:border-gray-600">
    <span id="current-time-<?= $row['id'] ?>" class="flex items-center gap-1">
        ⏱ <span class="font-bold">00:00</span>
    </span>
    <span id="duration-<?= $row['id'] ?>" class="flex items-center gap-1">
        ⏳ <span class="font-bold">--:--</span>
    </span>
</div>

                    <!-- Action Buttons -->
                    <div class="flex justify-between items-center mt-4">
                      

                        
                        <button class="flex items-center bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition-all duration-300 shadow-md" title="Share this video" onclick="shareVideo('<?= htmlspecialchars($row['filepath']) ?>')">
                            <i class="fa fa-share-alt mr-2"></i> Share
                        </button>

                        <!-- Download Button -->
                        <a href="<?= htmlspecialchars($row['filepath']) ?>" download class="flex items-center bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm transition-all duration-300 shadow-md" title="Download this video">
                            <i class="fa fa-download mr-2"></i> Download
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-gray-600 dark:text-gray-400 text-center col-span-3 flex items-center justify-center gap-2">
            ⚠️ <span>No recordings found.</span>
        </p>
    <?php endif; ?>
</div>


    <script>
        function searchVideos() {
    let input = document.getElementById('search').value.toLowerCase();
    let videoCards = document.querySelectorAll('#video-container > div');

    videoCards.forEach(card => {
        let title = card.querySelector('h3').innerText.toLowerCase();
        if (title.includes(input)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}
        const players = {};

        // Initialize Plyr for all video elements
        document.querySelectorAll('.plyr').forEach((el) => {
            players[el.id] = new Plyr(el);
        });



        // Share Video (Browser's Share API)
        function shareVideo(videoUrl) {
    if (navigator.share) {
        navigator.share({
            title: '🎥 Video Sharing',
            text: 'Check out this video!',
            url: videoUrl
        }).then(() => {
            console.log('✅ Shared successfully');
        }).catch((error) => {
            console.error('❌ Error sharing:', error);
        });
    } else {
        // Fallback: Generate QR Code and Copy Link Modal
        showShareOptions(videoUrl);
    }
}

function showShareOptions(videoUrl) {
    // Create a modal for sharing options
    let modalHtml = `
        <div id="shareModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-md w-full text-center">
                <h2 class="text-lg font-bold text-gray-900 dark:text-gray-200">🔗 Share Video</h2>
                
                <!-- QR Code Container -->
                <div class="flex justify-center my-4">
                    <div id="qrcode"></div>
                </div>

                <!-- Copy Link -->
                <div class="relative flex items-center border rounded-lg p-2 bg-gray-200 dark:bg-gray-700">
                    <input id="videoLink" type="text" class="w-full bg-transparent outline-none text-gray-900 dark:text-gray-200" value="${videoUrl}" readonly>
                    <button onclick="copyLink()" class="ml-2 bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">📋 Copy</button>
                </div>

                <!-- Close Button -->
                <button onclick="closeModal()" class="mt-4 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">❌ Close</button>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML("beforeend", modalHtml);

    // Generate QR Code
    let qr = new QRCode(document.getElementById("qrcode"), {
        text: videoUrl,
        width: 128,
        height: 128
    });
}

function copyLink() {
    let videoInput = document.getElementById("videoLink");
    videoInput.select();
    document.execCommand("copy");
    alert("✅ Video link copied!");
}

function closeModal() {
    document.getElementById("shareModal").remove();
}
        // Video Duration & Time Update
        function updateVideoTime(videoId) {
            const video = document.getElementById(`video-${videoId}`);
            const progress = document.getElementById(`progress-${videoId}`);
            const currentTimeElem = document.getElementById(`current-time-${videoId}`);
            const durationElem = document.getElementById(`duration-${videoId}`);

            // Update progress bar width and time
            const currentTime = video.currentTime;
            const duration = video.duration;
            const progressWidth = (currentTime / duration) * 100;

            progress.style.width = `${progressWidth}%`;
            currentTimeElem.textContent = formatTime(currentTime);
            durationElem.textContent = formatTime(duration);
        }

        // Format time to mm:ss
        function formatTime(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return `${mins}:${secs < 10 ? '0' : ''}${secs}`;
        }

 
    </script>
</body>
</html>

<?php
$conn->close();
?>
