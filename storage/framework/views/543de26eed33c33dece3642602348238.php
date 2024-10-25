<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<!-- jQuery library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Caret.js - Required for At.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.caret/0.3.1/jquery.caret.min.js"></script>

<!-- At.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.atwho/1.3.0/js/jquery.atwho.min.js"></script>

<!-- Your custom At.js initialization file -->
<script src="<?php echo e(url('frontend/js/at.js')); ?>"></script>

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
</html><?php /**PATH C:\xampp\htdocs\Oct-25-Project-Management\resources\views/frontends/layouts/footer.blade.php ENDPATH**/ ?>