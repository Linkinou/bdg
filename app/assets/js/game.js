require('./vendor/readmore/readmore');
import '../css/game.scss';

$("#story").readMore({
  expandTrigger: ".prompt",
  previewHeight: 200,
  fadeColor1: "rgba(14,14,14,0)",
  fadeColor2: "rgba(14,14,14,1)"
});