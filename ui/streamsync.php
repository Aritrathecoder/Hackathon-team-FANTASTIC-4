<?php
include('connect.php');
$username = $_SESSION['username'];
$password = $_SESSION['password'];
$id = $_SESSION['id'];

// Get the first letter of the username

// Get the first letter of the username
$firstLetter = strtoupper(substr($username, 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Streaming Platform</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #000;
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sidebar {
            background-color: #000;
            width: 60px;
            position: fixed;
            height: 100%;
            border-right: 1px solid #333;
            z-index: 100;
        }
        
        .main-content {
            margin-left: 60px;
        }
        
        .logo {
            width: 40px;
            height: 40px;
            background-color: #4361ee;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        .nav-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            margin-bottom: 20px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .nav-icon:hover {
            background-color: #333;
        }
        
        .nav-icon.active {
            background-color: #7b2cbf;
        }
        
        .profile-icon {
            width: 40px;
            height: 40px;
            background-color: #f72585;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        .banner {
            background: linear-gradient(90deg, #7b2cbf 0%, #14b8a6 100%);
            border-radius: 12px;
            padding: 24px;
        }
        
        .sync-btn {
            background-color: #14b8a6;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
        }
        
        .indicator {
            width: 8px;
            height: 8px;
            background-color: #f72585;
            border-radius: 50%;
            display: inline-block;
        }
        
        .tab-btn {
            background-color: transparent;
            border: none;
            color: #fff;
            padding: 8px 24px;
            border-radius: 8px;
            font-size: 14px;
        }
        
        .tab-btn.active {
            background-color: #7b2cbf;
        }
        
        .content-card {
            border-radius: 12px;
            overflow: hidden;
        }
        
        .card-img-container {
            height: 160px;
            position: relative;
        }
        
        .card-tag {
            position: absolute;
            top: 8px;
            left: 8px;
            font-size: 12px;
            padding: 2px 8px;
            border-radius: 4px;
        }
        
        .time-left {
            position: absolute;
            bottom: 8px;
            left: 8px;
            background-color: rgba(0, 0, 0, 0.6);
            font-size: 12px;
            padding: 2px 8px;
            border-radius: 4px;
        }
        
        .card-now {
            background-color: #3a0ca3;
        }
        
        .card-tonight {
            background-color: #480ca8;
        }
        
        .fits-now-btn {
            color: #14b8a6;
            font-size: 12px;
            background: transparent;
            border: none;
            padding: 4px 8px;
        }
        
        .time-btn {
            color: #14b8a6;
            font-size: 12px;
            background: transparent;
            border: none;
            padding: 4px 8px;
        }
        
        .episode-info {
            color: #888;
            font-size: 14px;
        }
        
        .schedule-item {
            border-left: 4px solid #7b2cbf;
            padding-left: 16px;
        }
        
        .time-slot {
            font-size: 14px;
        }
        
        .thumbnail {
            width: 96px;
            height: 64px;
            border-radius: 6px;
        }
        
        .genre-tag {
            background-color: #333;
            font-size: 12px;
            padding: 2px 8px;
            border-radius: 4px;
            margin-right: 8px;
        }
        
        .content-info {
            color: #888;
            font-size: 14px;
        }
        
        .purple-link {
            color: #7b2cbf;
            text-decoration: none;
        }
        
        .notification-dot {
            position: absolute;
            top: -4px;
            right: -4px;
            width: 10px;
            height: 10px;
            background-color: #ffc107;
            border-radius: 50%;
            border: 2px solid #000;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column align-items-center py-4">
        <div class="logo mb-5">S</div>
        
        <div class="nav-icon active">
            <i class="bi bi-house-door-fill"></i>
        </div>
        <div class="nav-icon">
            <i class="bi bi-search"></i>
        </div>
        <div class="nav-icon">
            <i class="bi bi-calendar3"></i>
        </div>
        <div class="nav-icon">
            <i class="bi bi-collection"></i>
        </div>
        <div class="nav-icon">
            <i class="bi bi-bell"></i>
        </div>
        
        <div class="mt-auto profile-icon">
            <?php echo htmlspecialchars($firstLetter); ?>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header class="d-flex justify-content-between align-items-center p-3">
            <h1 class="h5 fw-bold">Home</h1>
            <div class="d-flex align-items-center gap-3">
                <div class="position-relative">
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-0 text-secondary">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control bg-dark border-0 text-light" placeholder="Search content...">
                    </div>
                </div>
                <div class="position-relative">
                    <i class="bi bi-bell-fill fs-5"></i>
                    <span class="notification-dot"></span>
                </div>
            </div>
        </header>
        
        <!-- Banner -->
        <div class="banner mx-3 mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="h3 fw-bold mb-2">Perfect timing, <?php echo htmlspecialchars($username); ?></h2>
                    <p class="mb-0">You have a 45-minute lunch break today. We've curated content that fits your schedule perfectly.</p>
                </div>
                <div class="col-md-4 text-end">
                    <button class="sync-btn text-white d-flex align-items-center gap-2 ms-auto">
                        Sync My Schedule
                        <span class="indicator"></span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Continue Watching -->
        <div class="px-3 mb-5">
            <div class=" Wd-flex justify-content-between align-items-center mb-3">
                <h3 class="h5 fw-bold">Continue Watching</h3>
                <a href="#" class="purple-link">View All</a>
            </div>
            
            <!-- Tabs -->
            <div class="mb-3">
                <button class="tab-btn active">All</button>
                <button class="tab-btn">Fits Now</button>
                <button class="tab-btn">Tonight</button>
                <button class="tab-btn">Weekend</button>
            </div>
            
            <!-- Content Cards -->
            <div class="row g-3">
                <!-- Card 1 -->
                <div class="col-md-3">
                    <div class="content-card">
                        <div class="card-img-container" style="background-color: #1e3a8a;">
                            <span class="card-tag card-now">Now</span>
                            <span class="time-left">15 min left</span>
                        </div>
                        <div class="mt-2">
                            <h4 class="h6 fw-semibold mb-1">The Last Kingdom</h4>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="episode-info">S4E7</span>
                                <button class="fits-now-btn">
                                    <i class="bi bi-play-fill"></i> Fits now
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Card 2 -->
                <div class="col-md-3">
                    <div class="content-card">
                        <div class="card-img-container" style="background-color: #312e81;">
                            <span class="card-tag card-tonight">Tonight</span>
                            <span class="time-left">32 min left</span>
                        </div>
                        <div class="mt-2">
                            <h4 class="h6 fw-semibold mb-1">Space Odyssey</h4>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="episode-info">S1E3</span>
                                <button class="time-btn">
                                    <i class="bi bi-clock"></i> 9:00 PM
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Card 3 -->
                <div class="col-md-3">
                    <div class="content-card">
                        <div class="card-img-container" style="background-color: #164e63;">
                            <span class="card-tag card-now">Now</span>
                            <span class="time-left">8 min left</span>
                        </div>
                        <div class="mt-2">
                            <h4 class="h6 fw-semibold mb-1">Tokyo Stories</h4>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="episode-info">S2E5</span>
                                <button class="fits-now-btn">
                                    <i class="bi bi-play-fill"></i> Fits now
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Card 4 -->
                <div class="col-md-3">
                    <div class="content-card">
                        <div class="card-img-container" style="background-color: #7f1d1d;">
                            <span class="card-tag card-tonight">Tonight</span>
                            <span class="time-left">55 min left</span>
                        </div>
                        <div class="mt-2">
                            <h4 class="h6 fw-semibold mb-1">Western Horizons</h4>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="episode-info">S3E9</span>
                                <button class="time-btn">
                                    <i class="bi bi-clock"></i> 8:30 PM
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Today's Schedule -->
        <div class="px-3 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="h5 fw-bold">Today's Schedule</h3>
                <a href="#" class="purple-link">Manage Schedule</a>
            </div>
            
            <div class="row g-4">
                <!-- Schedule Item 1 -->
                <div class="col-md-4">
                    <div class="schedule-item">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="time-slot fw-medium">12:15 PM - 1:00 PM</span>
                            <div>
                                <i class="bi bi-calendar3 me-1"></i>
                                <i class="bi bi-calendar3-fill text-primary"></i>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-3">
                            <div class="thumbnail" style="background-color: #1e3a8a;"></div>
                            <div>
                                <h4 class="h6 fw-semibold mb-1">The Last Kingdom</h4>
                                <p class="content-info mb-1">Fits your lunch break perfectly</p>
                                <div>
                                    <span class="genre-tag">Action</span>
                                    <span class="genre-tag">Historical</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Schedule Item 2 -->
                <div class="col-md-4">
                    <div class="schedule-item">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="time-slot fw-medium">5:30 PM - 6:15 PM</span>
                            <div>
                                <i class="bi bi-calendar3 me-1"></i>
                                <i class="bi bi-calendar3-fill text-primary"></i>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-3">
                            <div class="thumbnail" style="background-color: #164e63;"></div>
                            <div>
                                <h4 class="h6 fw-semibold mb-1">Tokyo Stories + Quick Comedy</h4>
                                <p class="content-info mb-1">Optimized for your commute time</p>
                                <div>
                                    <span class="genre-tag">Drama</span>
                                    <span class="genre-tag">Comedy</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Schedule Item 3 -->
                <div class="col-md-4">
                    <div class="schedule-item">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="time-slot fw-medium">9:00 PM - 10:30 PM</span>
                            <div>
                                <i class="bi bi-calendar3 me-1"></i>
                                <i class="bi bi-calendar3-fill text-primary"></i>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-3">
                            <div class="thumbnail" style="background-color: #312e81;"></div>
                            <div>
                                <h4 class="h6 fw-semibold mb-1">Space Odyssey</h4>
                                <p class="content-info mb-1">Evening relaxation time</p>
                                <div>
                                    <span class="genre-tag">Sci-Fi</span>
                                    <span class="genre-tag">Adventure</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>