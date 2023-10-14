<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
define ('Attempts',6);
// Initialize game variables
if (!isset($_SESSION['targetWord'])) {
    $_SESSION['targetWord'] = str_split('jumpy'); // You can change this word
    $_SESSION['guesses'] = array_fill(0, count($_SESSION['targetWord']), "");
    $_SESSION['attempts'] = Attempts;
    $_SESSION['usedLetters'] = [];
    $_SESSION['previousGuesses'] = [];
    $_SESSION['message'] = '';
}


function checkWin(){
    return $_SESSION['guesses'] === $_SESSION['targetWord'];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    var_dump($_SESSION);
    if (isset($_POST['check'])) {
        $guess = $_POST['guess'];
        $_SESSION['previousGuesses'][] = implode('', $_POST['guess']);
        foreach ($guess as $index => $guessLetter){
            $guessLetter = strtolower($guessLetter);
            if(!in_array($guessLetter,$_SESSION['usedLetters'])){
                $_SESSION['usedLetters'][] = $guessLetter;
                if(in_array($guessLetter, $_SESSION['targetWord'])){
                    $_SESSION['guesses'][$index] = $guessLetter;
                }
            }
        }
        //decrease count
        if (!checkWin()){
            $_SESSION['attempts']--;
        }

        if (checkWin() == true) {
            $_SESSION['message'] = "Congratulations! You guessed the word correctly!";
        } elseif ($_SESSION['attempts'] == 0) {
            $_SESSION['message'] = "Sorry, you have run out of attempts. The correct word was " . implode('', $_SESSION['targetWord']) . ". Please try again";
        }
    }
    
    elseif (isset($_POST['reset'])) {
        session_destroy();
        header("Location: " . $_SERVER['PHP_SELF']); // Redirect to the initial page
        exit();
    } elseif (isset($_POST['random'])) {
        //CHANGE THIS LATER
        $_SESSION['targetWord'] = str_split('apple'); // You can change this word
        $_SESSION['guesses'] = array_fill(0, count($_SESSION['targetWord']), "");
        $_SESSION['attempts'] = Attempts;
        $_SESSION['usedLetters'] = [];
        $_SESSION['message'] = '';
        $_SESSION['previousGuesses'] = [];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.3/dist/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.5.0/dist/semantic.min.css">
        <script src="https://cdn.jsdelivr.net/npm/semantic-ui@2.5.0/dist/semantic.min.js"></script>
        <link rel="stylesheet" href="word.css">
        <style>
        .toast-container{--radius: 5px;--stack-gap: 20px;--safe-area-gap: env(safe-area-inset-bottom);position:fixed;display:block;max-width:468px;bottom:calc(env(safe-area-inset-bottom) + 20px);bottom:calc(var(--safe-area-gap, 0px) + 20px);right:20px;z-index:5000;transition:all .4s ease}.toast-container .toast{position:absolute;bottom:0;right:0;width:468px;transition:all .4s ease;transform:translate3d(0,86px,0);opacity:0}.toast-container .toast .toast-inner{--toast-bg: none;--toast-fg: #fff;--toast-border-color: #eaeaea;box-sizing:border-box;border-radius:var(--radius);border:1px solid #eaeaea;border:1px solid var(--toast-border-color);display:flex;align-items:center;justify-content:space-between;padding:24px;color:#fff;color:var(--toast-fg);background-color:none;background-color:var(--toast-bg);height:var(--height);transition:all .25s ease}.toast-container .toast .toast-inner.default{--toast-fg: #000;--toast-bg: #fff;box-shadow:0 5px 10px #0000001f}.toast-container .toast .toast-inner.success{--toast-bg: #0076ff;--toast-border-color: var(--toast-bg)}.toast-container .toast .toast-inner.error{--toast-bg: #e00;--toast-border-color: var(--toast-bg)}.toast-container .toast .toast-inner.warning{--toast-bg: #f5a623;--toast-border-color: var(--toast-bg)}.toast-container .toast .toast-inner.dark{--toast-bg: #000;--toast-fg: #fff;--toast-border-color: #333}.toast-container .toast .toast-inner.dark .toast-button{--button-fg: #000;--button-bg: #fff;--button-border: #fff;--button-border-hover: #fff;--button-fg-hover: #fff}.toast-container .toast .toast-inner.dark .toast-button.cancel-button{--cancel-button-bg: #000;--cancel-button-fg: #888;--cancel-button-border: #333}.toast-container .toast .toast-inner.dark .toast-button.cancel-button:hover{color:#fff;border-color:var(--button-border)}.toast-container .toast .toast-button:hover{border-color:var(--button-border-hover);background-color:transparent;color:var(--button-fg-hover)}.toast-container .toast .toast-button.cancel-button{--cancel-button-bg: #fff;--cancel-button-fg: #666;--cancel-button-border: #eaeaea;margin-right:10px;color:#666;color:var(--cancel-button-fg);border-color:#eaeaea;border-color:var(--cancel-button-border);background-color:#fff;background-color:var(--cancel-button-bg)}.toast-container .toast .toast-button.cancel-button:hover{--cancel-button-fg: #000;--cancel-button-border: #000}.toast-container .toast .default .toast-button{--button-fg: #fff;--button-bg: #000;--button-border: #000;--button-border-hover: #000;--button-fg-hover: #000}.toast-container .toast:after{content:"";position:absolute;left:0;right:0;top:calc(100% + 1px);width:100%;height:1000px;background:transparent}.toast-container .toast.toast-1{transform:translateZ(0);opacity:1}.toast-container .toast:not(:last-child){--i: calc(var(--index) - 1);transform:translate3d(0,calc(1px - (var(--stack-gap) * calc(var(--index) - 1))),0) scale(calc(1 - .05 * (var(--index) - 1)));transform:translate3d(0,calc(1px - (var(--stack-gap) * calc(var(--index) - 1))),0) scale(calc(1 - .05 * calc(var(--index) - 1)));transform:translate3d(0,calc(1px - (var(--stack-gap) * var(--i))),0) scale(calc(1 - .05 * var(--i)));opacity:1}.toast-container .toast:not(:last-child) .toast-inner{height:var(--front-height)}.toast-container .toast:not(:last-child) .toast-inner .toast-text{opacity:0}.toast-container .toast.toast-4{opacity:0}.toast-container:has(.toast-2):hover{bottom:30px;bottom:calc(var(--safe-area-gap, 0px) + 30px)}.toast-container:hover .toast{transform:translate3d(0,calc(var(--hover-offset-y) - var(--stack-gap) * (var(--index) - 1)),0)}.toast-container:hover .toast .toast-inner{height:var(--height)}.toast-container:hover .toast .toast-text{opacity:1!important}@media (max-width: 440px){.toast-container{max-width:90vw;right:5vw}.toast-container .toast{width:90vw}}
        </style>
        <script id="transcript-settings" data-css="chrome-extension://klknhfgkblobpfimidmhkclikdalnoke/react/static/css/main.css" data-react="chrome-extension://klknhfgkblobpfimidmhkclikdalnoke/react/static/js/main.js" data-aiwebm="chrome-extension://klknhfgkblobpfimidmhkclikdalnoke/images/ai.webm" data-math-ocr="" data-start-hidden="" data-hideframe="" data-start-light="" data-autoanswer="true" data-searchmethod="2" data-livestream="" data-snapshot-keybind="shift+ctrl+1" data-search-keybind="shift+ctrl+2" data-search-ai-keybind="shift+ctrl+3" data-hide-ui-keybind="shift+ctrl+4" data-buttoncolor="#2563ff" data-logocolor="#ffffff" data-buttonscale="100" data-language="en"></script>
    </head>

    <body>
        <div class="ui segment inverted black center aligned">
            <div class="ui attached segment center aligned inverted black">
                <h1>Guess Which Word </h1>
            </div>
            <br>
            <form id="nextGuess" method="POST" action="">
                <div class="previousGuesses">
                    <table>
                        <tr>
                            <?php 
                                $previousGuesses = $_SESSION['previousGuesses'];
                                for ($i = 0; $i < count($previousGuesses); $i += 5) {
                                    $slice = array_slice($previousGuesses, $i, 5);
                                    echo "<div class='ui input'>";
                                    foreach ($slice as $guess) {
                                        echo "<input class='ng' value='$guess' readonly>";
                                    }
                                    echo "</div><br>";
                                }
                            ?>
                        </tr>
                    </table>
                </div>
                <br>
                <table>
                    <tbody>
                        <tr>
                            <td>
                                <input class="ng" name="guess[0]" maxlength="1" size="1">
                            </td>
                            <td>
                                <input class="ng" name="guess[1]" maxlength="1" size="1">
                            </td>
                            <td>
                                <input class="ng" name="guess[2]" maxlength="1" size="1">
                            </td>
                            <td>
                                <input class="ng" name="guess[3]" maxlength="1" size="1">
                            </td>
                            <td>
                                <input class="ng" name="guess[4]" maxlength="1" size="1">
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class ="notification">
                    <?php echo isset($_SESSION['message']) ? $_SESSION['message'] : ''; ?>
                </div>
                <div class="attempts">
                    <?php echo $_SESSION['attempts'] . " Attempts Remaining"; ?>
                </div>
                <button class="ui button centered" type="submit" name="check">Check Word</button>
            </form>
            <br>
            <form method="POST" action="">
                <input hidden="" name="reset" value="reset">
                <button type="submit" class="ui button small centered">Reset Tries</button>
            </form>
            <br>
            <hr>
            <div class="keyArea">
                <div class="ui letters unused"> <span>a</span></div>
                <div class="ui letters unused"> <span>b</span></div>
                <div class="ui letters unused"> <span>c</span></div>
                <div class="ui letters unused"> <span>d</span></div>
                <div class="ui letters unused"> <span>e</span></div>
                <div class="ui letters unused"> <span>f</span></div>
                <div class="ui letters unused"> <span>g</span></div>
                <div class="ui letters unused"> <span>h</span></div>
                <div class="ui letters unused"> <span>i</span></div>
                <div class="ui letters unused"> <span>j</span></div>
                <div class="ui letters unused"> <span>k</span></div>
                <div class="ui letters unused"> <span>l</span></div>
                <div class="ui letters unused"> <span>m</span></div>
                <div class="ui letters unused"> <span>n</span></div>
                <div class="ui letters unused"> <span>o</span></div>
                <div class="ui letters unused"> <span>p</span></div>
                <div class="ui letters unused"> <span>q</span></div>
                <div class="ui letters unused"> <span>r</span></div>
                <div class="ui letters unused"> <span>s</span></div>
                <div class="ui letters unused"> <span>t</span></div>
                <div class="ui letters unused"> <span>u</span></div>
                <div class="ui letters unused"> <span>v</span></div>
                <div class="ui letters unused"> <span>w</span></div>
                <div class="ui letters unused"> <span>x</span></div>
                <div class="ui letters unused"> <span>y</span></div>
                <div class="ui letters unused"> <span>z</span></div>
            </div>
            <form method="POST" action="">
                <input type="hidden" name="random" value="random">
                <button type="submit" class="ui button small centered">Reset to Random Word</button>
            </form>
        </div>
    </body>
</html>
