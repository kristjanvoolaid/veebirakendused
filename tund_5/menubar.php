<style>
    body {
        margin: 0;
    }
 
    .navbar {
        overflow: hidden;
        background-color: #333;
        position: fixed;
        top: 0;
        width: 100%;
    }

    .navbar a {
        float: left;
        display: block;
        color: #f2f2f2;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
    }

    .navbar a:hover {
        background: #ddd;
        color: black;
    }

    .main {
        margin-top: 30px; /* Add a top margin to avoid content overlay */
    }
</style>

<div class="navbar">
    <a href="home.php">Home</a>
    <a href="school_log.php">Insert school logs</a>
    <a href="readschoollogs.php">School logs</a>
    <a href="photoUpload.php">Upload pictures</a>
    <a href="semipublicgallery.php">Gallery</a>
    <a href="privategallery.php">Your gallery</a>
    <a href="?logout=1">Log out</a>
</div>
