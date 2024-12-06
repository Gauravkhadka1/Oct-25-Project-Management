<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Checker</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .responsive-checker {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            width: 90%;
            margin: 20px auto;
        }

        .device {
            border: 2px solid #ccc;
            border-radius: 10px;
            overflow: hidden;
            text-align: center;
            background: #f9f9f9;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .device h3 {
            background-color: #0078D7;
            color: white;
            margin: 0;
            padding: 10px;
        }

        .device iframe {
            display: block;
            border: none;
        }

        #desktop iframe {
            width: 1920px;
            height: 1080px;
            transform: scale(0.3); /* Scale to fit smaller containers */
            transform-origin: top left;
        }

        #laptop iframe {
            width: 1366px;
            height: 768px;
            transform: scale(0.5);
            transform-origin: top left;
        }

        #tablet iframe {
            width: 768px;
            height: 1024px;
            transform: scale(0.8);
            transform-origin: top left;
        }

        #mobile iframe {
            width: 375px;
            height: 667px;
            transform: scale(1);
            transform-origin: top left;
        }
    </style>
</head>
<body>
    <div class="responsive-checker">
        <div class="device" id="desktop">
            <h3>Desktop (1920x1080)</h3>
            <iframe src="https://example.com"></iframe>
        </div>
        <div class="device" id="laptop">
            <h3>Laptop (1366x768)</h3>
            <iframe src="https://example.com"></iframe>
        </div>
        <div class="device" id="tablet">
            <h3>Tablet (768x1024)</h3>
            <iframe src="https://example.com"></iframe>
        </div>
        <div class="device" id="mobile">
            <h3>Mobile (375x667)</h3>
            <iframe src="https://example.com"></iframe>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const urlInput = prompt("Enter a URL to preview (e.g., https://example.com):", "https://example.com");

            if (urlInput) {
                document.querySelectorAll('.device iframe').forEach(iframe => {
                    iframe.src = urlInput;
                });
            }
        });
    </script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/frontends/responsive/all-devices.blade.php ENDPATH**/ ?>