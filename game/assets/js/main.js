let isPlay = false;
let items = [];
let balls = [
  {}
]
let position = {
  player1: { x: 300, y: 390, isJump: false, isWalk: false, isKick: false, isGoal: false, facing: 'right' },
  player2: { x: 550, y: 390, isJump: false, isWalk: false, isKick: false, isGoal: false, facing: 'right' },
};
let gameMetadata = {
  score: { player1: 0, player2: 0 },
  teams: { player1: 'Brazil', player2: 'Japan' },
}

const welcomeScreem = document.querySelector('#welcome-screen');
const gameScreen = document.querySelector('#game-screen');

const entityContainer = document.querySelector('.entity');
const flagsContainer = document.querySelector('.flags');

const renderPlayer = () => {
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

renderPlayer()
renderFlag()