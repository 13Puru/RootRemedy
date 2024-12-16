<?php include 'getfeatured.php'; ?>
<?php
$status = '';
if (isset($_GET['status'])) {
    $status = $_GET['status'];
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" lang="en" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="shortcut icon" href="assets/favicon.png" type="png" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>RootRemedy - About</title>

    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

    <!-- fonts style -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet" />

    <!-- font awesome style -->
    <link href="css/font-awesome.min.css" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" />
    <!-- responsive style -->
    <link href="css/responsive.css" rel="stylesheet" />
    <style>
         @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        @keyframes slideInLeft {
        from {
            transform: translateX(-100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }


    .animate-on-scroll {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 1s, transform 1s;
    }

    .animate-slide-in-left {
        animation: slideInLeft 2s ease-out;
    }

    .animate-slide-in-right {
        animation: slideInRight 2s ease-out;
    }
    p{
        font-size: 18px;
    }
    .para p{
        font-size: 30px;
    }
    </style>
</head>

<body>
    <div class="nav_and_bg">
        <!-- Background Image -->
        <img src="assets/featured_bg.jpg" alt="Background image" class="search-bg">

        <!-- Centered Text -->
        <div class="centered-text">
    <h1>About Us</h1>
    <p>
        Welcome to <strong>Root Remedy</strong>, your gateway to the wealth of traditional medicinal knowledge from Northeast India. Root Remedy is dedicated to preserving and promoting the incredible benefits of indigenous plants and herbs that have been used for centuries to heal and nurture.  
    </p>
    <p>
        Our platform is more than just a database—it's a bridge between tradition and technology. Whether you're exploring medicinal plants, searching for natural remedies, or seeking expert consultation, Root Remedy is here to guide you.  
    </p>
    <p>
        We are committed to empowering communities with sustainable healthcare solutions by bringing the wisdom of nature to the digital forefront. Together, let’s rediscover the power of nature's pharmacy and build a healthier future.  
    </p>
    <p><strong>Discover. Learn. Heal.</strong></p>
    <div class="para"><p><strong>Scroll to meet our strong team with skillfull members</strong></p></div>
</div>


        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg custom_nav-container">
            <a class="navbar-brand" href="index.php">
                <span>
                    <img src="assets/logo.png" width="250px" />
                </span>
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class=""> </span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="filteredsearch.php"> Filtered Search</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#enquire">Enquire</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Get in touch</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
    <div class="about-section">
    <div class="container mx-auto" id="team-section">
    <h2 class="text-3xl font-bold text-center text-black mb-8 drop-shadow-lg">
    Our Team
</h2>

    <!-- Guide Card -->
    <div class="w-full md:w-full lg:w-full flex justify-center mt-6 mb-6 animate-on-scroll animate-slide-in-left">
        <div class="w-full max-w-3xl bg-teal-100 rounded-2xl shadow-lg card-hover overflow-hidden p-4">
            <div class="flex flex-col md:flex-row items-center">
                <div class="w-40 h-40 md:flex-shrink-0 mx-auto md:mx-0 mt-4 md:mt-0 mb-4 md:mb-0 rounded-full overflow-hidden border-4 border-white shadow-md">
                    <img src="/rootremedy/user/assets/abhijit.jpg" alt="Project Guide" class="w-full h-full object-cover">
                </div>
                <div class="text-center md:text-left px-4">
                    <h3 class="text-xl font-bold text-teal-900 mb-2">Dr. Abhijit Bora</h3>
                    <p class="text-teal-700 mb-4 italic">Academic Mentor & Advisor</p>
                    <div class="bg-white/50 rounded-lg p-3 mx-2 md:mx-0">
                        <h4 class="text-md font-semibold text-teal-900 mb-2">Key Contributions</h4>
                        <ul class="space-y-1 text-teal-800 text-sm">
                            <li>Project Conceptualization</li>
                            <li>Technical Guidance</li>
                            <li>Research Supervision</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex flex-wrap justify-center gap-6">
        <!-- Developer Cards -->
        <div class="w-64 bg-green-100 rounded-2xl shadow-lg card-hover overflow-hidden animate-on-scroll animate-slide-in-right">
            <div class="w-32 h-32 mx-auto mt-4 mb-2 rounded-full overflow-hidden border-4 border-white shadow-md">
                <img src="/rootremedy/user/assets/purab.jpeg" alt="Purab Das" class="w-full h-full object-cover">
            </div>
            <div class="text-center px-4 pb-4">
                <h3 class="text-lg font-bold text-green-900 mb-2">Purab Das</h3>
                <p class="text-green-700 mb-3 italic">Backend/Frontend Developer</p>
                <div class="bg-white/50 rounded-lg p-3">
                    <h4 class="text-md font-semibold text-green-900 mb-2">Key Contributions</h4>
                    <ul class="space-y-1 text-green-800 text-sm">
                        <li>Major Implementations</li>
                        <li>Medicine Reco. Sys</li>
                        <li>Search functionality</li>
                        <li>Database Design</li>
                        <li>Chatbot Integration</li>
                        <li>Admin Panel Implementations</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="w-64 bg-emerald-100 rounded-2xl shadow-lg card-hover overflow-hidden animate-on-scroll animate-slide-in-right">
            <div class="w-32 h-32 mx-auto mt-4 mb-2 rounded-full overflow-hidden border-4 border-white shadow-md">
                <img src="/rootremedy/user/assets/srijani.jpg" alt="Srijani Deb" class="w-full h-full object-cover">
            </div>
            <div class="text-center px-4 pb-4">
                <h3 class="text-lg font-bold text-green-900 mb-2">Srijani Deb</h3>
                <p class="text-green-700 mb-3 italic">Frontend Designer</p>
                <div class="bg-white/50 rounded-lg p-3">
                    <h4 class="text-md font-semibold text-green-900 mb-2">Key Contributions</h4>
                    <ul class="space-y-1 text-green-800 text-sm">
                        <li>Comprehensive Research</li>
                        <li>Data Collection and Insertion</li>
                        <li>Report Structuring</li>
                        <li>Initial Design Concepts</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="w-64 bg-lime-100 rounded-2xl shadow-lg card-hover overflow-hidden animate-on-scroll animate-slide-in-right">
            <div class="w-32 h-32 mx-auto mt-4 mb-2 rounded-full overflow-hidden border-4 border-white shadow-md">
                <img src="/rootremedy/user/assets/abhishek.jpeg" alt="Abhishek Shekhar" class="w-full h-full object-cover">
            </div>
            <div class="text-center px-4 pb-4">
                <h3 class="text-lg font-bold text-green-900 mb-2">Abhishek Shekhar</h3>
                <p class="text-green-700 mb-3 italic">Frontend Designer</p>
                <div class="bg-white/50 rounded-lg p-3">
                    <h4 class="text-md font-semibold text-green-900 mb-2">Key Contributions</h4>
                    <ul class="space-y-1 text-green-800 text-sm">
                        <li>Enhanced User Interface</li>
                        <li>Report Structuring</li>
                        <li>Initail Design Concepts</li>
                        <li>UI Optimization</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
    </div>

    <footer class="footer_section">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-3 footer_col">
                    <div class="footer_contact">
                        <h4>Reach at..</h4>
                        <div class="contact_link_box">
                            <a href="https://maps.app.goo.gl/qrnwvZ2drezLP9MQA" target="_blank">
                                <i class="fa fa-map-marker" aria-hidden="true"></i>
                                <span> Click to get location</span>
                            </a>
                            <a href="">
                                <i class="fa fa-phone" aria-hidden="true"></i>
                                <span> +91 1234567890 </span>
                            </a>
                            <a href="">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                <span> rootremedy@gmail.com </span>
                            </a>
                        </div>
                    </div>
                    <div class="footer_social">
                        <a href="">
                            <i class="fa fa-facebook" aria-hidden="true"></i>
                        </a>
                        <a href="">
                            <i class="fa fa-twitter" aria-hidden="true"></i>
                        </a>
                        <a href="">
                            <i class="fa fa-linkedin" aria-hidden="true"></i>
                        </a>
                        <a href="">
                            <i class="fa fa-instagram" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-2 footer_col">
                    <div class="footer_link_box">
                        <h4>Links</h4>
                        <div class="footer_links">
                            <a class="active" href="index.php"> Home </a>
                            <a class="" href="about.php"> Filtered Search </a>
                            <a class="" href="index.php#enquire"> Enquire </a>
                            <a class="" href="about.php"> About Us </a>
                            <a class="" href="contact.php"> Get In Touch </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 footer_col">
                    <h4>Our Location</h4>
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d872.9648967683238!2d91.6187429878337!3d26.129819089292443!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x375a43f3fffffff9%3A0x122d2ba3a82829ab!2sAssam%20Don%20Bosco%20University%2C%20Azara%20Guwahati!5e1!3m2!1sen!2sin!4v1729932004231!5m2!1sen!2sin"
                        style="border: 0" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>

                <div class="col-md-6 col-lg-3 footer_col">
                    <h4>Newsletter</h4>
                    <?php if ($status == 'success'): ?>
                        <div id="alert" class="alert alert-success alert-dismissible fade show" role="alert">
                            Subscribed!
                        </div>
                    <?php elseif ($status == 'error'): ?>
                        <div id="alert" class="alert alert-danger alert-dismissible fade show" role="alert">
                            Unable to Subscribe.
                        </div>
                    <?php endif; ?>
                    <form action="subscribe.php" method="POST">
                        <input type="email" name="subscribers" placeholder="Enter email" required />
                        <button type="submit">Subscribe</button>
                    </form>
                </div>
            </div>
            <div class="footer-info">
                <p>
                    &copy; <span id="displayYear"></span> All Rights Reserved By Root
                    Remedy
          </p>
            </div>
        </div>
    </footer>
    <!-- jQuery -->
    <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
    <!-- popper js -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <!-- bootstrap js -->
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <!-- custom js -->
    <script type="text/javascript" src="js/custom.js"></script>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("animate-slide-in-left", "animate-slide-in-right");
                        entry.target.style.opacity = "1";
                        entry.target.style.transform = "translateY(0)";
                    }
                });
            },
            { threshold: 0.2 } // Trigger when 20% of the section is visible
        );

        document.querySelectorAll(".animate-on-scroll").forEach((element) => {
            observer.observe(element);
        });
    });
</script>

</body>

</html>