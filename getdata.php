<?php
    if (!empty($_POST["name"]) && isset($_POST["submit"])) {
        $name = $_POST["name"];
        
        $contents = @file_get_contents("https://www.instagram.com/$name");
        if ($contents == FALSE) {
            echo "<h1>Not Found</h1>";
            exit();
        }
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($contents);

        $data = array();
        foreach ($dom->getElementsByTagName("meta") as $tag) {
            $og = $tag->getAttribute("property");
            if ($og == "og:image") {
                $data[0] = $tag->getAttribute("content");
            }
            else if ($og == "og:title") {
                $data[1] = $tag->getAttribute("content");
                $data[1] = preg_replace("(• Instagram photos and videos)", "", $data[1]);
            }
            else if ($og == "og:description") {
                $data[2] = $tag->getAttribute("content");
            }
            else if ($og == "og:url") {
                $data[3] = $tag->getAttribute("content");
            }
        }
    }
    else {
        // header("location: index.php");
        echo "<script type='text/javascript'>
            alert('Please input name');
            window.location.replace(\"http://localhost/authIG/index.php\");
        </script>";
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile</title>
    <link rel="stylesheet" href="style.css?v1">
</head>
<body>
    <div class="profile">
        <h1><?php echo $data[1]; ?></h1>
        <img src="<?php echo $data[0]; ?>">
        <h3><?php echo $data[2]; ?></h3>
        <p><?php echo $data[3]; ?></p>
        <a href="index.php">Go Back</a>
    </div>
</body>
</html>