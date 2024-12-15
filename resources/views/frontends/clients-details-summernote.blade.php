<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Summernote Example</title>

    <!-- Include Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

    <style>
        /* Optional custom styles */
        .container {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Summernote Example</h2>
        <textarea id="summernote"></textarea>
    </div>

    <!-- Include jQuery (required for Summernote) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Summernote
            $('#summernote').summernote({
                height: 300,  // Set height of editor
                placeholder: 'Write your text here...',
                tabsize: 2
            });
        });
    </script>
</body>
</html>
