<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desktop Preview</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        iframe {
            display: block;
            width: 100%;
            height: 1080px;
            border: none;
        }
    </style>
</head>
<body>
    <iframe id="desktopPreview" src=""></iframe>
    <script>
        const url = prompt("Enter website URL (e.g., https://example.com):");
        if (url) {
            const validUrl = url.startsWith('http://') || url.startsWith('https://') ? url : `https://${url}`;
            document.getElementById('desktopPreview').src = validUrl;
        } else {
            alert("Invalid URL.");
        }
    </script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/frontends/responsive/desktop.blade.php ENDPATH**/ ?>