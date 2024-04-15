function startMonitoring() {
    // Retrieve the analyzingTime value, assuming it's in milliseconds
    var countdownTime = document.getElementById("analyzingTime").value;

    // Hide the start button to prevent multiple clicks
    document.getElementById("startBtn").style.display = 'none';

    // Initialize the countdown timer display
    document.getElementById("countdownTimer").innerHTML = (countdownTime / 1000) + "<span>s</span>";

    // Countdown function to update the timer every second
    function countdown() {
        // Calculate seconds from countdownTime
        var seconds = Math.floor((countdownTime % (1000 * 60)) / 1000);

        // Display the result in the element with id="countdownTimer"
        document.getElementById("countdownTimer").innerHTML = seconds + "<span>s</span>";

        // If the countdown is finished, write some text and stop the interval
        if (countdownTime <= 0) {
            clearInterval(interval);
            document.getElementById("countdownTimer").innerHTML = "0<span>s</span>";

            // Set a timeout to wait for 3 seconds
            setTimeout(function() {
                // Assuming the analyzingTime value is in milliseconds and needs to be converted to seconds for display
                var analyzingTimeInSeconds = document.getElementById("analyzingTime").value / 1000;
                document.getElementById("countdownTimer").innerHTML = analyzingTimeInSeconds + "<span>s</span>";
            }, 3000); // 3000 milliseconds = 3 seconds
        }

        // Decrease countdownTime by 1 second
        countdownTime -= 1000;
    }

    // Call countdown function immediately
    countdown();

    // Update the countdown every 1 second
    var interval = setInterval(countdown, 1000);
}

// Function to restart the countdown timer
function restartMonitoring() {
    startMonitoring(); // Simply start the countdown again
}

window.onload = function() {
    document.getElementById("startBtn").onclick = startMonitoring;
};