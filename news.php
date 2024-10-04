<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Website</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 15px 0;
            text-align: center;
        }

        header .logo h1 {
            margin: 0;
            font-size: 32px;
        }

        nav ul {
            list-style: none;
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        nav ul li {
            margin: 0 15px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
        }

        .featured-news {
            display: flex;
            margin: 20px;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        }

        .featured-news img {
            width: 50%;
            margin-right: 20px;
        }

        .news-info h2 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .news-info p {
            font-size: 16px;
            margin-bottom: 15px;
        }

        .news-info button {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .news-categories {
            display: flex;
            justify-content: space-around;
            margin: 20px;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        }

        .category {
            width: 23%;
            text-align: center;
        }

        .category img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .category h3 {
            font-size: 22px;
            margin-bottom: 10px;
        }

        .category p {
            font-size: 14px;
            color: #555;
        }

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 15px 0;
            margin-top: 20px;
        }

        footer p {
            margin: 0;
        }
    </style>
</head>
<body>

    <header>
        <div class="logo">
            <h1>Daily News</h1>
        </div>
        <nav>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Sports</a></li>
                <li><a href="#">Technology</a></li>
                <li><a href="#">Health</a></li>
                <li><a href="#">Entertainment</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="featured-news">
            <img src="featured-news.jpg" alt="Featured News">
            <div class="news-info">
                <h2>Breaking News: Major Event Happening Now</h2>
                <p id="news-details">Details about the main headline go here. It's an exciting event that everyone is talking about.</p>
                <button id="read-more-btn">Read More</button>
            </div>
        </section>

        <section class="news-categories">
            <div class="category">
                <img src="sports.jpg" alt="Sports News">
                <h3>Sports</h3>
                <p>Latest updates on sports events...</p>
            </div>
            <div class="category">
                <img src="tech.jpg" alt="Tech News">
                <h3>Technology</h3>
                <p>Latest innovations in technology...</p>
            </div>
            <div class="category">
                <img src="health.jpg" alt="Health News">
                <h3>Health</h3>
                <p>Tips and advice for healthy living...</p>
            </div>
            <div class="category">
                <img src="entertainment.jpg" alt="Entertainment News">
                <h3>Entertainment</h3>
                <p>What's trending in movies and music...</p>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Daily News. All Rights Reserved.</p>
    </footer>

    <script>
        document.getElementById('read-more-btn').addEventListener('click', function() {
            const details = document.getElementById('news-details');
            details.innerHTML = 'This is the full article content. You can replace this text with more detailed information about the news.';
        });
    </script>

</body>
</html>
