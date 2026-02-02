var numSelected=null;
var tileSelected=null;
var scoreSubmitted = false;
var errors=0;
var board=[
    "--74916-5",
    "2---6-3-9",
    "-----7-1-",
    "-586----4",
    "--3----9-",
    "--62--187",
    "9-4-7---2",
    "67-83----",
    "81--45---"

]
var solutions=[
    "387491625",
    "241568379",
    "569327418",
    "758619234",
    "123784596",
    "496253187",
    "934176852",
    "675832941",
    "812945763"
]
window.onload=function() {
    setGame();
}
function setGame(){
    // digits 1-9
    for(let i=1; i<=9; i++){
        let number=document.createElement("div");
        number.id=i
        number.innerText=i;
        number.addEventListener("click", selectNumber);
        number.classList.add("number");
        document.getElementById("digits").appendChild(number);
        
    }
    //board
    for(let r=0; r<9; r++){
        for(let c=0;c<9;c++){
            let tile=document.createElement("div");
            tile.id=r.toString() + "-" +c.toString();  
            tile.innerText=board[r][c];
           
           if (board[r][c] != "-") {
           tile.innerText = board[r][c];
           tile.classList.add("tile-start")
           } else {
           tile.innerText = ""; 
           }
           if(r==2 || r==5){
            tile.classList.add("horizontal-line")
           }
            if(c==2 || c==5){
            tile.classList.add("vertical-line")
           }
         
            tile.addEventListener("click", selectTile);
            tile.classList.add("tile");
            document.getElementById("board").append(tile);   
         }
    }
}
function selectNumber(){
    if(numSelected != null)
   { 
    numSelected.classList.remove("number-selected");
   }
    numSelected=this;
    numSelected.classList.add("number-selected");

}
function selectTile(){
    if(numSelected){
        if(this.innerText != ""){
            return;
        }

        let coords = this.id.split("-");
        let r = parseInt(coords[0]);
        let c = parseInt(coords[1]);

        if(solutions[r][c] == numSelected.id){
            this.innerText = numSelected.id;

            if (!scoreSubmitted && isBoardComplete()) {
              scoreSubmitted = true;
              const finalScore = computeSudokuScore();
              alert(`Puzzle complete! Score: ${finalScore}`);
              submitScore(finalScore);
            }

        } else {
            errors += 1;
            document.getElementById("errors").innerText = errors;
        }
    }
}

async function submitScore(finalScore) {
  try {
    const baseUrl = window.GAMEBITS?.baseUrl ?? '';
    const gameKey = window.GAMEBITS?.game ?? 'sudoku';

    const res = await fetch(`${baseUrl}/games/submit_score.php`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ game: gameKey, score: finalScore })
    });

    const data = await res.json();
    if (!data.ok) {
      console.warn("Score not saved:", data.error);
      return;
    }
    console.log("Score saved:", data.score_id);
  } catch (e) {
    console.warn("Score submit failed:", e);
  }
}
function isBoardComplete() {
  for (let r = 0; r < 9; r++) {
    for (let c = 0; c < 9; c++) {
      const tile = document.getElementById(`${r}-${c}`);
      if (!tile || tile.innerText === "") return false;
    }
  }
  return true;
}


function computeSudokuScore() {
  return Math.max(0, 1000 - (errors * 50));
}