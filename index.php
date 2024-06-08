<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Upload Images</title>
</head>

<body>
    <h1>Upload Images with Logo</h1>
    <form action="/uploadController.php" method="post" enctype="multipart/form-data">
        <input type="file" name="images[]" accept="image/*" multiple required>
        <button type="submit">Upload Images</button>
    </form>
</body>

</html>