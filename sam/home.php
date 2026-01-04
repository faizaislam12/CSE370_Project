<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PQLR Airways</title>

    <style>
        /* RESET */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background-color: #f5f5f5;
        }

        /* NAVBAR */
        header {
            background-color: #001025ff; /* premium airline color */
            padding: 30px 60px;
        }

        nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        nav h2 {
            color: white;
            letter-spacing: 1px;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 25px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
        }

        nav ul li a:hover {
            text-decoration: underline;
        }

        /* HERO SECTION */
        .hero {
            background-color: #001b42ff;
            background-size: cover;
            background-position: center;
            height: 85vh;
            display: flex;
            align-items: center;
            padding: 60px;
            color: white;
        }

        .hero-content {
            max-width: 550px;
        }

        .hero-content h1 {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .hero-content p {
            font-size: 18px;
            margin-bottom: 30px;
        }

        /* SEARCH BOX */
        .search-box {
            background: white;
            padding: 25px;
            border-radius: 8px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .search-box input,
        .search-box button {
            padding: 10px;
            font-size: 14px;
        }

        .search-box input {
            flex: 1;
        }

        .search-box button {
            background-color: #5a0f2e;
            color: white;
            border: none;
            cursor: pointer;
        }

        .search-box button:hover {
            background-color: #7b1843;
        }

        /* FOOTER */
        footer {
            background-color: #001025ff;
            color: #aaa;
            text-align: center;
            padding: 15px;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <header>
        <nav>
            <h2>PQLR Airways</h2>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Book</a></li>
                <li><a href="#">Manage Booking</a></li>
                <li><a href="#">Flight Status</a></li>
                <li><a href="#">Sign In</a></li>
            </ul>
        </nav>
    </header>

    <!-- HERO -->
    <section class="hero">
        <div class="hero-content">
            <h1>Going Places Together</h1>
            <p>Discover destinations. Book flights. Travel with comfort.</p>

            <form class="search-box" action = "search.php" method="GET">
                <input type="text" name = "from_city" placeholder="From">
                <input type="text" name = "to_city" placeholder="To">
                <input type="date" name = "date" placeholder = "Date">
                <button type="submit">Search Flights</button>
            </form>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <p>&copy; 2025 SkyLine Airways. All rights reserved.</p>
    </footer>

</body>
</html>
