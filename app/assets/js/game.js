require('./vendor/readmore/readmore');
import React from 'react';
import ReactDOM from 'react-dom'
import GameRoom from './components/GameRoom';
import '../css/game.scss';

window.onload=($("#story").readMore({
  expandTrigger: ".prompt",
  previewHeight: 200,
  fadeColor1: "rgba(14,14,14,0)",
  fadeColor2: "rgba(14,14,14,1)"
}));

ReactDOM.render(
  <GameRoom gameRoom={JSON.parse(window.gameRoom)} isAuthenticated={window.isAuthenticated} className="mt-5 mb-4 mb-md-5"/>,
  document.getElementById('game-room-root')
);
