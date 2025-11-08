<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Моя гра</title>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background: #f3f5f9;
            margin: 0;
            padding: 40px;
            color: #2d2d2d;
        }

        .container {
            max-width: 700px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 25px;
            letter-spacing: 1px;
            color: #333;
        }

        h2, h3 {
            text-align: center;
            color: #444;
            margin-top: 40px;
        }

        ul, ol {
            padding-left: 20px;
            line-height: 1.6;
        }

        .card {
            background: white;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
            margin-bottom: 25px;
        }

        #game_form {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin: 25px 0;
        }

        #game_form button {
            background: white;
            border: 2px solid #ccc;
            padding: 12px 25px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 10px;
            transition: all 0.25s ease;
            min-width: 110px;
        }

        #game_form button:hover {
            border-color: #4e79ff;
            color: #4e79ff;
            box-shadow: 0 0 12px rgba(78, 121, 255, 0.3);
            transform: translateY(-2px);
        }

        #game_form button:active {
            transform: translateY(1px);
        }

        .leaders ol li,
        .games ol li {
            background: #fff;
            padding: 10px 15px;
            margin: 6px 0;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        .centered-list {
            text-align: left;
            max-width: 400px;
            margin: 0 auto;
        }
    </style>
</head>
<body>

<div id="animation-layer" style="
    position: fixed;
    top:0; left:0;
    width:100%; height:100%;
    pointer-events:none;
    z-index:9999;
"></div>

<div id="text-layer" style="
    position: fixed;
    top:0; left:0;
    width:100%; height:100%;
    pointer-events:none;
    z-index:10000;
"></div>

<div class="container">


    <h1>Гра “Камінь, ножиці, папір”</h1>

    <?php if ($lastResult): ?>
        <div class="card">
            <h2>Результат останнього раунду</h2>
            <p><strong>Player:</strong> <?= htmlspecialchars($lastResult['player']) ?></p>
            <p><strong>Computer:</strong> <?= htmlspecialchars($lastResult['computer']) ?></p>
            <p><strong>Result:</strong> <?= htmlspecialchars($lastResult['result']) ?></p>
        </div>
    <?php endif; ?>

    <div class="card">
        <h2>Ваш хід</h2>
        <form id="game_form">
            <button type="submit" name="choice" value="rock">Камінь</button>
            <button type="submit" name="choice" value="paper">Папір</button>
            <button type="submit" name="choice" value="scissors">Ножиці</button>
        </form>
    </div>

    <div class="card stats">
        <h2>Статистика</h2>
        <ul class="centered-list">
            <li>Перемоги: <?= $stats['wins'] ?></li>
            <li>Поразки: <?= $stats['losses'] ?></li>
            <li>Нічиї: <?= $stats['draws'] ?></li>
            <li>Серія перемог: <?= $stats['streak'] ?></li>
        </ul>
    </div>

    <?php if (!empty($achievements)): ?>
        <div class="card achievements">
            <h2>Досягнення</h2>
            <ul class="centered-list">
                <?php foreach ($achievements as $achievement): ?>
                    <li><?= htmlspecialchars($achievement) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card leaders">
        <h2>Таблиця лідерів</h2>
        <ol class="centered-list">
            <?php foreach ($leaders as $leader => $value): ?>
                <li><?= htmlspecialchars($leader) ?> — <?= $value ?> очок</li>
            <?php endforeach; ?>
        </ol>
    </div>

    <?php if(is_array($games) && count($games) > 1): ?>
        <div class="card games">
            <h2>Games</h2>
            <ol class="centered-list">
                <?php foreach ($games as $item => $game): ?>
                    <li>time: <?= htmlspecialchars($game['time']) ?> — result: <?= $game['result'] ?></li>
                <?php endforeach; ?>
            </ol>
        </div>
    <?php endif; ?>

</div>

<script>
    $('#game_form button').on('click', function (e) {
        e.preventDefault();

        const playerChoice = $(this).val();
        $.ajax({
            method: "POST",
            url: '/game',
            data: { choice: playerChoice }
        })
            .done(function (resp) {
                // console.log('Успіх:', resp);
                // location.reload();

                absurdAnimation(resp); // припускаємо, що resp = { result: 'win' | 'lose' | 'draw' }

                // Через 3 секунди можна перезавантажити або оновити статистику
                setTimeout(()=>location.reload(), 3000);

            })
            .fail(function (err) {
                console.log('Помилка:', err);
            });
    });

    function randomInt(max) {
        return Math.floor(Math.random() * max);
    }

    function absurdAnimation(result) {
        const aminLayer = $('#animation-layer');
        const textLayer = $('#text-layer');
        aminLayer.empty(); // очищаємо попередні анімації
        textLayer.empty(); // очищаємо попередні анімації

        const winText = ['Ти крутий!', 'Виграв супер!', 'Радій по можеш'];
        const loseText = ['Ти програв!', 'Сором!', 'Ганьба', 'Фуууу', 'ти лох'];
        const drawText = ['Нічия!', 'Справедливо', 'Нічого', 'Таке собі', 'Ні риба ні мясо'];

        const winEmoji = ['👍','🚀','🤩','😍','🥳','👏','✨'];
        const loseEmoji = ['👎','🤮','💩','🤢'];
        const drawEmoji = ['🥱','🫢','😑','🩼'];

        if(result === 'win') {
            // Літаючі котики
            for(let i=0; i<10; i++){
                let randEmoji = winEmoji[randomInt(winEmoji.length)];
                const cat = $('<div>'+randEmoji+'</div>').css({
                    position: 'absolute',
                    fontSize: '40px',
                    top: Math.random()*window.innerHeight,
                    left: Math.random()*window.innerWidth,
                    transform: 'rotate(0deg)',
                    opacity: 1
                });

                let randText = winText[randomInt(winText.length)];
                const text = $('<div>'+ randText + '</div>').css({
                    position: 'absolute',
                    fontSize: '40px',
                    top: Math.random()*window.innerHeight,
                    left: Math.random()*window.innerWidth,
                    transform: 'rotate(0deg)',
                    opacity: 1
                });
                aminLayer.append(cat);
                cat.animate({
                    top: Math.random()*window.innerHeight,
                    left: Math.random()*window.innerWidth,
                    opacity: 0,
                    rotate: 720
                }, 2000, 'swing', () => cat.remove());
                aminLayer.append(text);
                text.animate({
                    top: Math.random()*window.innerHeight,
                    left: Math.random()*window.innerWidth,
                    opacity: 0
                }, 2000, () => text.remove());

            }
        } else if(result === 'lose') {
            // Літаючі піци з фейєрверком
            for(let i=0; i<8; i++){
                let randEmoji = loseEmoji[randomInt(loseEmoji.length)];
                const pizza = $('<div>'+randEmoji+'</div>').css({
                    position: 'absolute',
                    fontSize: '50px',
                    top: Math.random()*window.innerHeight,
                    left: Math.random()*window.innerWidth,
                    opacity: 1
                });
                let randText = loseText[randomInt(loseText.length)];
                const text = $('<div>'+ randText + '</div>').css({
                    position: 'absolute',
                    fontSize: '40px',
                    top: Math.random()*window.innerHeight,
                    left: Math.random()*window.innerWidth,
                    transform: 'rotate(0deg)',
                    opacity: 1
                });

                aminLayer.append(pizza);
                pizza.animate({
                    top: Math.random()*window.innerHeight,
                    left: Math.random()*window.innerWidth,
                    opacity: 0
                }, 2500, 'swing', () => pizza.remove());

                aminLayer.append(text);
                text.animate({
                    top: Math.random()*window.innerHeight,
                    left: Math.random()*window.innerWidth,
                    opacity: 0
                }, 2000, () => text.remove());
            }
        } else if(result === 'draw') {
            // Танцюючі поні
            for(let i=0; i<6; i++){
                let randEmoji = drawEmoji[randomInt(drawEmoji.length)];

                const pony = $('<div>'+randEmoji+'</div>').css({
                    position: 'absolute',
                    fontSize: '60px',
                    top: Math.random()*window.innerHeight,
                    left: Math.random()*window.innerWidth,
                    opacity: 1
                });

                let randText = drawText[randomInt(drawText.length)];
                const text = $('<div>'+ randText + '</div>').css({
                    position: 'absolute',
                    fontSize: '40px',
                    top: Math.random()*window.innerHeight,
                    left: Math.random()*window.innerWidth,
                    transform: 'rotate(0deg)',
                    opacity: 1
                });
                aminLayer.append(pony);
                pony.animate({
                    top: Math.random()*window.innerHeight,
                    left: Math.random()*window.innerWidth,
                    opacity: 0
                }, 3000, 'linear', () => pony.remove());

                aminLayer.append(text);
                text.animate({
                    top: Math.random()*window.innerHeight,
                    left: Math.random()*window.innerWidth,
                    opacity: 0
                }, 2000, () => text.remove());
            }
        }
    }

</script>

</body>
</html>
