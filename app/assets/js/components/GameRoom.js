import React, {Component} from 'react';

const GroupAction = ({gameRoom, gameState, anyPersonaInPendingGame, personaJoinedGame}) => {
  // if (anyPersonaInPendingGame) {
  //   return <div className="col-3">
  //     <a href="{{ path('rpg_game_leave', {'locationSlug': game.location.slug, 'gameSlug': game.slug}) }}" class="nk-btn link-effect-4">
  //       {{ 'game.text.cancel_group'|trans }}
  //     </a>
  //   </div>
  // }
};

class GameRoom extends Component {
  constructor(props) {
    super(props);

    this.state = {
      currentPlayingPersonas: props.gameRoom.playingPersonas.length,
      isFull: props.gameRoom.maxPlayingPersonas === props.gameRoom.playingPersonas.length,
      playingPersonas: props.gameRoom.playingPersonas,
      pendingPersonas: props.gameRoom.pendingPersonas,
      anyPersonaInPendingGame: props.gameRoom.pendingPersonas.some(item => props.gameRoom.currentPlayerPersonas.includes(item)),
      personaJoinedGame: props.gameRoom.playingPersonas.some(item => props.gameRoom.currentPlayerPersonas.includes(item)),
    }
  }

  render() {

    const gameRoom = this.props.gameRoom;

    return (
      <div>
        <div className="row justify-content-between">
          <div className="col-3">
            Groupe: {this.state.currentPlayingPersonas}/{gameRoom.maxPlayingPersonas}
          </div>
        </div>

        {this.props.isAuthenticated &&
        !this.props.isCurrentGameMaster &&
        this.state.gameState === 'published' && (
          <GroupAction
            gameRoom={gameRoom}
            gameState={this.state.gameState}
            anyPersonaInPendingGame={this.state.anyPersonaInPendingGame}
            personaJoinedGame={this.state.personaJoinedGame}
          />
        )}
      </div>
    );
  }
}

export default GameRoom;
