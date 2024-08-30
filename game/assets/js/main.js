let isPlay = false;

let items = [];
let balls = [
  { x: 472, y: 225, gravity: 1, ball: 0 }
]
let position = {
  player1: { x: 300, y: 390, isJump: false, isWalk: false, isKick: false, isGoal: false, facing: 'right', gravity: 0.8 },
  player2: { x: 550, y: 390, isJump: false, isWalk: false, isKick: false, isGoal: false, facing: 'right', gravity: 0.8 },
};
let gameMetadata = {
  playerName: '',
  score: { player1: 0, player2: 0 },
  teams: { player1: '', player2: '' },
  timer: 0
}

const welcomeScreen = document.querySelector('#welcome-screen');
const gameScreen = document.querySelector('#game-screen');

const entityContainer = document.querySelector('.entity');
const ballsContainer = document.querySelector('.balls');
const flagsContainer = document.querySelector('.flags');

const playerNameContainer = document.querySelector('.player-name > h1')
const player1board = document.querySelector('#team-left')
const player2board = document.querySelector('#team-right')

const timerEl = document.querySelector('.timer-count')
const playBtn = welcomeScreen.querySelector('[type="submit"]')

/**
 * Validate all input & select
 */
function validate() {
  function isValid() {
    let check1 = Array.from(document.querySelectorAll('[name="username"]')).every(item => {
      return item.value.length > 0
    })

    let check2 = Array.from(document.querySelectorAll('select')).every(item => {
      return item.value.length > 0
    })

    if (check1 && check2) {
      playBtn.removeAttribute('disabled')
    } else {
      playBtn.setAttribute('disabled', true)
    }
  }
  isValid()

  document.querySelector('[name="username"]').addEventListener('change', function (e) {
    validate()
  })

  document.querySelectorAll('select').forEach((seg) => {
    seg.addEventListener('change', function (e) {
      validate()
    })
  })
}
validate();

welcomeScreen.querySelector('form').addEventListener('submit', function (e) {
  e.preventDefault();

  isPlay = true;

  balls[0].ball = document.querySelector('[name="ball"]').value
  gameMetadata.playerName = document.querySelector('[name="username"]').value
  gameMetadata.teams.player1 = document.querySelector('[name="country"]').value

  if (document.querySelector('[name="computer_country"]').value == 'random') {
    const country = [
      'Brazil', 'England', 'Germany', 'Italy', 'Japan', 'Netherlands', 'Portugal', 'Spain'
    ];

    gameMetadata.teams.player2 = country[Math.floor(Math.random() * country.length)];
  } else {
    gameMetadata.teams.player2 = document.querySelector('[name="computer_country"]').value
  }

  if (document.querySelector('[name="level"]').value == 'easy') {
    gameMetadata.timer = 30
  } else if (document.querySelector('[name="level"]').value == 'medium') {
    gameMetadata.timer = 20
  } else {
    gameMetadata.timer = 15
  }

  // render
  renderTimer(gameMetadata.timer)
  renderPlayer()
  renderBall()
  renderFlag()
  movePlayer({
    playerData: {
      who: 'player1',
      element: 'player-1'
    },
    key: {
      leftKey: 'KeyA',
      rightKey: 'KeyD',
      jumpKey: 'KeyW',
      kickKey: 'Space'
    }
  })

  updateTimer();

  welcomeScreen.style.display = 'none'
  gameScreen.style.display = 'block'
})

/**
 * Render player
 */
const renderPlayer = () => {
  // show player name
  playerNameContainer.textContent = gameMetadata.playerName

  // render team flag in leaderboard
  player1board.querySelector('img').setAttribute('src', `assets/img/Flag/${gameMetadata.teams.player1}.png`)
  player1board.querySelector('p').textContent = gameMetadata.teams.player1

  player2board.querySelector('img').setAttribute('src', `assets/img/Flag/${gameMetadata.teams.player2}.png`)
  player2board.querySelector('p').textContent = gameMetadata.teams.player2

  // render player 1
  const createPlayer1 = document.createElement('img')
  createPlayer1.src = `assets/img/Characters/Character - ${gameMetadata.teams.player1}/Idle/Idle_000.png`
  createPlayer1.classList.add('player')
  createPlayer1.style.left = `${position.player1.x}px`
  createPlayer1.style.top = `${position.player1.y}px`
  createPlayer1.setAttribute('id', 'player-1')
  entityContainer.append(createPlayer1)

  // render player 2
  const createPlayer2 = document.createElement('img')
  createPlayer2.src = `assets/img/Characters/Character - ${gameMetadata.teams.player2}/Idle/Idle_000.png`
  createPlayer2.classList.add('player')
  createPlayer2.classList.add('left')
  createPlayer2.style.left = `${position.player2.x}px`
  createPlayer2.style.top = `${position.player2.y}px`
  createPlayer2.setAttribute('id', 'player-2')
  entityContainer.append(createPlayer2)
}

/**
 * Render Flag
 */
const renderFlag = () => {
  for (let i = 0; i <= 6; i++) {
    const createFlag1 = document.createElement('img');
    createFlag1.src = `assets/img/Flag/${gameMetadata.teams.player1}.png`
    flagsContainer.append(createFlag1)

    const createFlag2 = document.createElement('img');
    createFlag2.src = `assets/img/Flag/${gameMetadata.teams.player2}.png`
    flagsContainer.append(createFlag2)
  }
}

/**
 * Render Ball
 */
const renderBall = () => {
  balls.forEach((seg, index) => {
    const createBall = document.createElement('img')
    createBall.src = `assets/img/ball-0${seg.ball}.png`;
    createBall.setAttribute('id', `ball-${index}`)
    createBall.classList.add('balls')
    ballsContainer.append(createBall)
  })
}

const renderTimer = (time) => {
  timerEl.textContent = time
}

/**
 * Handle user movement key
 */
const movePlayer = ({
  playerData: { who, element },
  key: { leftKey, rightKey, jumpKey, kickKey }
}) => {
  window.addEventListener('keydown', function (e) {
    const { code } = e;

    switch (code) {
      case leftKey:
        position[who].isWalk = true;
        position[who].facing = 'left';
        break;

      case rightKey:
        position[who].isWalk = true;
        position[who].facing = 'right';
        break;

      case jumpKey:
        if (!position[who].isJump) {
          position[who].isJump = true;
          position[who].y = -15;
        }
        break;

      case kickKey:
        if (!position[who].isKick) {
          position[who].isKick = true;
          document.getElementById(element).src = `./assets/img/Characters/Character - ${gameMetadata.teams[who]}/Kick/Kick_001.png`;
        }
        break;

      default:
        break;
    }
  });

  window.addEventListener('keyup', function (e) {
    const { code } = e;

    switch (code) {
      case leftKey:
      case rightKey:
        if (position[who].isWalk) {
          position[who].isWalk = false;
        }
        break;

      case kickKey:
        position[who].isKick = false;
        document.getElementById(element).src = `./assets/img/Characters/Character - ${gameMetadata.teams[who]}/Idle/Idle_000.png`;
        break;

      default:
        break;
    }
  })
}

const handlePlayerMovement = () => {
  if (position.player1.isWalk) {
    const player1El = document.getElementById('player-1');
    const player1Pos = parseInt(player1El.style.left, 10);

    if (position.player1.facing === 'right' && player1Pos <= 770) {
      position.player1.x += 4;
      player1El.classList.remove('left');
    }

    if (position.player1.facing === 'left' && player1Pos >= 80) {
      position.player1.x -= 4;
      player1El.classList.add('left');
    }

    player1El.style.left = `${position.player1.x}px`;
  }
}

const handlePlayerPhysics = () => {
  if (position.player1.isJump) {
    const player1El = document.getElementById('player-1');
    let top = parseInt(player1El.style.top, 10);

    position.player1.y += position.player1.gravity;
    top += position.player1.y;

    if (top >= 390) {
      top = 390;

      position.player1.isJump = false;
      position.player1.y = 390;
    }

    player1El.style.top = `${top}px`;
  }
}

/**
 * Update Timer
 */
const updateTimer = () => {
  const count = setInterval(() => {
    if (gameMetadata.timer > 0) {
      gameMetadata.timer--;
      renderTimer(gameMetadata.timer);
    } else {
      clearInterval(count);
      alert('Game Over!');

      if (gameMetadata.score.player1 > gameMetadata.score.player2) {
        alert('Player 1 WIN!');
      } else if (gameMetadata.score.player1 < gameMetadata.score.player2) {
        alert('Player 2 WIN!');
      } else if (gameMetadata.score.player1 == gameMetadata.score.player2) {
        alert('Draw')
      }

      isPlay = false;
      gameScreen.style.display = 'none';
      welcomeScreen.style.display = 'flex';
    }
  }, 1000);
}

const animate = () => {
  if (isPlay) {
    handlePlayerMovement();
    handlePlayerPhysics();
  }

  window.requestAnimationFrame(animate)
}

window.requestAnimationFrame(animate)