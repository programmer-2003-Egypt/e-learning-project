<?php
$pdo = new PDO("mysql:host=localhost;dbname=project;charset=utf8mb4", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

// Fetch latest video streams
$query = "SELECT lecture_name, timestamp, video_stream FROM students ORDER BY timestamp";
$stmt = $pdo->prepare($query);
$stmt->execute();
$streams = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Streams</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
       body {
    font-family: 'Poppins', sans-serif;
    background-color: #121212;
    color: white;
    text-align: center;
    padding: 20px;
    transition: background-color 0.5s ease-in-out;
}

.container {
    max-width: 900px;
    margin: auto;
    padding: 20px;
}

.video-box {
    background: linear-gradient(135deg, #1e1e1e, #2b2b2b);
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: 0px 5px 15px rgba(255, 87, 51, 0.5);
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    overflow: auto;
    position: relative;
}

.video-box:hover {
    transform: scale(1.05);
    box-shadow: 0px 10px 20px rgba(255, 87, 51, 0.7);
}

.video-box h3 {
    margin-bottom: 10px;
    font-size: 20px;
    font-weight: 600;
    letter-spacing: 1px;
}

.video-box p {
    font-size: 14px;
    opacity: 0.8;
}

.video-box video {
    width: 100%;
    border-radius: 10px;
    border: 2px solid #ff5733;
    transition: border-color 0.3s ease-in-out;
}

.video-box video:hover {
    border-color: #ffab73;
}

/* Floating Play Button */
.video-box #play-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 60px;
    color: rgba(255, 255, 255, 0.8);
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}

.video-box:hover #play-icon {
    opacity: 1;
    cursor: pointer;
}

/* Smooth Scroll */
html {
    scroll-behavior: smooth;
}

/* Responsive */
@media (max-width: 768px) {
    .video-box {
        padding: 15px;
    }
}

    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fa-solid fa-broadcast-tower" style="color: #ff5733;"></i> Available Live Streams</h2>
        <?php if (empty($streams)): ?>
            <p>No live streams available</p>
        <?php else: ?>
            <?php foreach ($streams as $stream): ?>
                <div class="video-box">
                    <h3>name:<?php echo htmlspecialchars($stream["lecture_name"]); ?></h3>
                    <p><i id="play-icon" class="fa-solid fa-clock"></i>date and time: <?php echo htmlspecialchars($stream["timestamp"]); ?></p>
                    <video controls>
                        <source src="<?php echo htmlspecialchars($stream["video_stream"]); ?>" type="video/webm">
                        Your browser does not support the video tag.
                    </video>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
