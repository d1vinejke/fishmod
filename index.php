<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Рыбовые</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="header-game">
        <h1 class="header-game-h1">Не дай меня съесть</h1>
    </header>

    <main>
        <div class="game hidden">
            <img class="smallFish-initial" id="smallFish" src="img/smallFish.png" alt="рыба маленькая">
            <img id="bigFish" src="img/bigFish.png" alt="рыба побольше">

            <div class="container">
                <div id="display">00:00</div>
                <div id="user_id"></div>
            </div>

        </div>
        <div class="playScreen">
            <div class="fishes">
                <img id="smallFish-screen" src="img/smallFish.png" alt="рыба маленькая">
                <img id="bigFish-screen" src="img/bigFish.png" alt="рыба побольше">
            </div>
            <div class="playScreen-buttons">
                <form name="Form" id="Form">
                    <input type="text" id="login" name="login" required placeholder="Например, Иван" title="Введите имя"/>
                    <button type="submit" id="bnt-start">Старт</button>
                </form>
            </div>
        </div>
    </main>

    <footer class="footer-game">
        <p class="footer-game-p">Здесь могла быть ваша реклама</p>
    </footer>
    <script src="axios.min.js"></script>
    <script src="script.js"></script>
</body>
</html>