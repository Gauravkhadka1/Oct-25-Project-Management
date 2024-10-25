<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<script>
    let timers = {};
    
    function startTimer(taskId) {
        if (!timers[taskId]) {
            timers[taskId] = {
                startTime: new Date().getTime(),
                elapsedTime: 0,
                running: true
            };
        } else {
            timers[taskId].startTime = new Date().getTime() - timers[taskId].elapsedTime;
            timers[taskId].running = true;
        }
        updateTimer(taskId);
    }

    function pauseTimer(taskId) {
        if (timers[taskId] && timers[taskId].running) {
            timers[taskId].elapsedTime = new Date().getTime() - timers[taskId].startTime;
            timers[taskId].running = false;
        }
    }

    function stopTimer(taskId) {
        clearTimeout(timers[taskId]?.timeout);
        timers[taskId] = null;
        document.getElementById(`time-${taskId}`).innerText = '00:00:00';
    }

    function updateTimer(taskId) {
        if (timers[taskId] && timers[taskId].running) {
            let currentTime = new Date().getTime() - timers[taskId].startTime;
            let hours = Math.floor(currentTime / (1000 * 60 * 60));
            let minutes = Math.floor((currentTime % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((currentTime % (1000 * 60)) / 1000);
            
            document.getElementById(`time-${taskId}`).innerText = 
                `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            timers[taskId].timeout = setTimeout(() => updateTimer(taskId), 1000);
        }
    }
</script>
</body>
</html><?php /**PATH C:\xampp\htdocs\new project management\resources\views/frontends/layouts/footer.blade.php ENDPATH**/ ?>