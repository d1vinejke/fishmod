const smallFish = document.getElementById("smallFish");
const bigFish = document.getElementById("bigFish");
const game = document.querySelector('.game');
const screen = document.querySelector('.playScreen');

document.addEventListener('keydown', function (event) {
    if (event.key === 'ArrowUp') {
        smallFish.classList.add('smallFish-up')
    } else if (event.key === 'ArrowDown') {
        smallFish.classList.add('smallFish-down')
    }
});
document.addEventListener('keyup', function (event) {
    if (event.key === 'ArrowUp') {
        smallFish.classList.toggle('smallFish-up')
    } else if (event.key === 'ArrowDown') {
        smallFish.classList.toggle('smallFish-down')
    }
});
let isAlive = setInterval(function () {
    let smallFishTop = parseInt(window.getComputedStyle(smallFish).getPropertyValue("top"));
    let bigFishLeft = parseInt(window.getComputedStyle(bigFish).getPropertyValue("left"));

    if (bigFishLeft > 500 && bigFishLeft < 528 && smallFishTop > 250 && smallFishTop < 470) {
        game.classList.add('hidden')
        screen.classList.remove('hidden')
        let input = document.getElementById('login');
        input.value = '';
        let lastPlayedTime = document.getElementById('display').innerText

        axios.get('/lib/ResponseHandler.php', {
            params: {
                'axios': {
                    'TYPE': 'INSERT',
                    'TABLE_NAME': 'results',
                    'CONDITIONS': '',
                    'LEFT_JOINS': '',
                    'DATA': {
                        'user_id': document.getElementById('user_id').innerText,
                        'playTime': '00:' + lastPlayedTime,
                        'date': 'auto'
                    }
                }
            }
        })
            .then(function (response) {
                console.log(response);
                alert(
                    response.data[0].topList[0].login + ' - ' + response.data[0].topList[0].playTime + '\n' +
                    response.data[0].topList[1].login + ' - ' + response.data[0].topList[1].playTime + '\n' +
                    response.data[0].topList[2].login + ' - ' + response.data[0].topList[2].playTime
                );
            })
            .catch(function (error) {
                console.log(error);
            });
    }
}, 10)

let form = document.getElementById("Form");

function handleForm(event) {
    event.preventDefault();
    let login = document.forms["Form"]["login"];
    if (login.value != null && login.value != "") {
        axios.get('/lib/ResponseHandler.php', {
            params: {
                'axios': {
                    'TYPE': 'INSERT',
                    'TABLE_NAME': 'users',
                    'CONDITIONS': '',
                    'LEFT_JOINS': '',
                    'DATA': {
                        'login': login.value
                    }
                }
            }
        })
            .then(function (response) {
                document.getElementById('user_id').innerText = response.data[0].userData.user_id
                screen.classList.add('hidden')
                game.classList.remove('hidden')
                resetTimer();
                startTimer();
            })
            .catch(function (error) {
                console.log(error);
            })
    }
}

form.addEventListener('submit', handleForm);

//Таймер    
let timer;
let time = 0;

// Функция для обновления отображаемого времени
function updateTime() {
    let minutes = Math.floor((time % 3600) / 60);
    let seconds = time % 60;
    minutes = String(minutes).padStart(2, '0');
    seconds = String(seconds).padStart(2, '0');
    document.getElementById('display').innerText = (`${minutes}:${seconds}`);
}

// Функция для запуска таймера
function startTimer() {
    timer = setInterval(function () {
        time++;
        updateTime();
    }, 1000);
}

// Функция для сброса таймера
function resetTimer() {
    clearInterval(timer);
    time = 0;
    updateTime();
}

