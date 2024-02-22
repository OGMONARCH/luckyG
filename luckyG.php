<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Number Guessing Game</title>
</head>
<body>

<h1>Number Guessing Game</h1>

<p id="aiGuess">AI's Guess: <span id="aiGuessValue"></span></p>
<p id="feedback"></p>
<input type="text" id="guessInput1" maxlength="2" placeholder="Enter first two digits">
<input type="text" id="guessInput2" maxlength="2" placeholder="Enter next two digits">
<input type="text" id="guessInput3" maxlength="2" placeholder="Enter last two digits">
<button onclick="checkGuess()">Submit Guess</button>
<span id="timer"></span>

<script>
    alert('When timer is  0 or less, you lose! and page relaods');
    var countdown = 30;

    function updateTimer() {
        var timer = document.getElementById('timer');
        timer.innerText = formatTime(countdown);

        if (countdown <= 3) {
            timer.classList.add('red');
        }

        countdown--;

        if (countdown < 0) {
            location.reload();
        } else {
            setTimeout(updateTimer, 3000);
        }
    }

    function formatTime(seconds) {
        var minutes = Math.floor(seconds / 60);
        var remainingSeconds = seconds % 60;

        minutes = minutes < 10 ? '0' + minutes : minutes;
        remainingSeconds = remainingSeconds < 10 ? '0' + remainingSeconds : remainingSeconds;

        return minutes + ':' + remainingSeconds;
    }

    function checkGuess() {
        var guess1 = document.getElementById('guessInput1').value;
        var guess2 = document.getElementById('guessInput2').value;
        var guess3 = document.getElementById('guessInput3').value;

        if (guess1 === '' || guess2 === '' || guess3 === '') {
            alert('Please enter all six digits.');
            return;
        }

        var userGuess = guess1 + guess2 + guess3;

        // Send the user's guess to the server using a simple form submission
        var form = new FormData();
        form.append('userGuess', userGuess);

        fetch('guess.php', {
            method: 'POST',
            body: form
        })
        .then(response => response.json())
        .then(data => {
            // Format AI's guess with spacing after every two digits
            var formattedAiGuess = data.aiGuess.match(/.{1,2}/g).join(' ');
            document.getElementById('aiGuessValue').innerText = formattedAiGuess;

            document.getElementById('feedback').innerText = data.message;

            // If the user's guess is correct, display earning message
            if (data.correct) {
                document.getElementById('feedback').innerText += ' You have earned $50!';
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Start the countdown timer
    setTimeout(updateTimer, 100);
</script>

</body>
</html>
