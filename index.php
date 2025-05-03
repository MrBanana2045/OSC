<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	
    file_put_contents("code.txt", $_POST['code']);
    $data = file_exists("commit.json") ? json_decode(file_get_contents("commit.json"), true) : [];

    $commitEntry = [
        'user' => $_SERVER['REMOTE_ADDR'],
        'code' => $_POST['code'],
        'time' => date('h:i:s')
    ];
    $data['commits'][] = $commitEntry;
    file_put_contents("commit.json", json_encode($data, JSON_PRETTY_PRINT));
}

$code_content = '';
if (file_exists("code.txt")) {
    $code_content = file_get_contents("code.txt");
}
?>
<!DOCTYPE html>
<html lang="fa">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Script Commit</title>
<style>
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
    }
    .container {
        display: flex;
        position: absolute;
        left:0;
        right:0;
        top:0;
        bottom:0;
    }
    .line-numbers {
        background-color: #10141B;
        color:#505050;
        padding: 10px;
        text-align: right;
        user-select: none;
        font-family: monospace;
        font-size: 14px;
        line-height: 1.4em;
    }
    .editor {
        width: 100%;
        height: 100%;
        font-family: monospace;
        font-size: 14px;
        line-height: 1.4em;
        border: none;
        resize: none;
        background: #1A202C;
        color:white;
        outline: none;
        padding: 10px;
        box-sizing: border-box;
    }
    .bottom-bar {
        position: fixed;
        top: 0px;
        right: 0;
        text-align: center;
        border-radius:0px 0px 0px 20px;
        background: #00DE04;
    }
    .log{
    	position: fixed;
        bottom: 0px;
        padding-top:5px;
        right: 0;
        text-align: center;
        border-radius:20px 0px 0px 0px;
        background: #00DE04;
    }
    .show {
    	position: fixed;
        top:50px;
        right:50px;
        left:50px;
        bottom:50px;
        background:#10141B;
        color:white;
        padding:10px;
        overflow-y: auto;
        border:2px solid black;
        border-radius:20px;
        box-shadow: 5px 5px 5px rgba(0,0,0,0.5);
        display: none;
    }
    button, input[type="submit"] {
        margin: 0 10px;
        font-size: 20px;
        cursor: pointer;
        background: none;
        border:none;
    }
</style>
</head>
<body>
<form method="POST" onsubmit="syncCode(); return true;">
<div class="container">
    <div class="line-numbers" id="lineNumbers">1</div>
    <textarea name="code" class="editor" id="codeArea" oninput="updateLineNumbers()"><?php echo htmlspecialchars($code_content); ?></textarea>
</div>
<div class="bottom-bar">
    <input type="submit" value=""><svg width="30px" height="30px" viewBox="0 0 24 24" fill="#292D32" xmlns="http://www.w3.org/2000/svg">
<path d="M5.75 3C4.23122 3 3 4.23122 3 5.75V18.25C3 19.7688 4.23122 21 5.75 21H9.99852C9.99129 20.8075 10.011 20.6088 10.0613 20.4075L10.2882 19.5H7.5V14.25C7.5 13.8358 7.83579 13.5 8.25 13.5H14.8531L16.2883 12.0648C16.1158 12.0225 15.9355 12 15.75 12H8.25C7.00736 12 6 13.0074 6 14.25V19.5H5.75C5.05964 19.5 4.5 18.9404 4.5 18.25V5.75C4.5 5.05964 5.05964 4.5 5.75 4.5H7V7.25C7 8.49264 8.00736 9.5 9.25 9.5H13.75C14.9926 9.5 16 8.49264 16 7.25V4.52344C16.3582 4.58269 16.6918 4.75246 16.9519 5.01256L18.9874 7.0481C19.3156 7.37629 19.5 7.8214 19.5 8.28553V10.007C19.5709 10.0024 19.642 10 19.713 10H19.7151C20.1521 10.0002 20.59 10.0874 21 10.2615V8.28553C21 7.42358 20.6576 6.59693 20.0481 5.98744L18.0126 3.9519C17.4031 3.34241 16.5764 3 15.7145 3H5.75ZM8.5 7.25V4.5H14.5V7.25C14.5 7.66421 14.1642 8 13.75 8H9.25C8.83579 8 8.5 7.66421 8.5 7.25Z" fill="#292D32"></path>
<path d="M19.7152 11H19.7131C19.1285 11.0003 18.5439 11.2234 18.0979 11.6695L12.1955 17.5719C11.8513 17.916 11.6072 18.3472 11.4892 18.8194L11.0315 20.6501C10.8325 21.4462 11.5536 22.1674 12.3497 21.9683L14.1804 21.5106C14.6526 21.3926 15.0838 21.1485 15.4279 20.8043L21.3303 14.9019C22.223 14.0093 22.223 12.5621 21.3303 11.6695C20.8843 11.2234 20.2998 11.0003 19.7152 11Z" fill="#292D32"></path>
</svg>
    <button type="button" onclick="downloadCode();"><svg fill="#292D32" width="30px" height="30px" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="M 16 6 C 13.351563 6 11.050781 7.238281 9.40625 9.0625 C 9.269531 9.046875 9.148438 9 9 9 C 6.800781 9 5 10.800781 5 13 C 3.269531 14.054688 2 15.835938 2 18 C 2 21.300781 4.699219 24 8 24 L 13 24 L 13 22 L 8 22 C 5.78125 22 4 20.21875 4 18 C 4 16.339844 5.007813 14.921875 6.4375 14.3125 L 7.125 14.03125 L 7.03125 13.28125 C 7.011719 13.117188 7 13.023438 7 13 C 7 11.882813 7.882813 11 9 11 C 9.140625 11 9.296875 11.019531 9.46875 11.0625 L 10.09375 11.21875 L 10.46875 10.71875 C 11.75 9.074219 13.75 8 16 8 C 19.277344 8 22.011719 10.253906 22.78125 13.28125 L 22.96875 14.0625 L 23.8125 14.03125 C 24.023438 14.019531 24.070313 14 24 14 C 26.21875 14 28 15.78125 28 18 C 28 20.21875 26.21875 22 24 22 L 19 22 L 19 24 L 24 24 C 27.300781 24 30 21.300781 30 18 C 30 14.84375 27.511719 12.316406 24.40625 12.09375 C 23.183594 8.574219 19.925781 6 16 6 Z M 15 18 L 15 26 L 12 26 L 16 30 L 20 26 L 17 26 L 17 18 Z" fill="#292D32" style=""></path></svg></button>
</div>
</form>
<div class="show" id="showlog">
	<button onclick='document.getElementById("showlog").style.display = "none";' style="background:#00DE04; position: fixed; top:40px; right:35px; font-size:30px; color:white; border-radius:50px; width:50px; color:#292D32;">×</button>
	<p style="text-align:center;">Log Commit</p>
	<?php
    $data = json_decode(file_get_contents("commit.json"), true);
    if ($data !== null && isset($data['commits'])) {
        foreach ($data['commits'] as $commit) {
            echo "<b><a style='color:#00DE04'>IP</a> : " . htmlspecialchars($commit['user']) . "</b><br>";
            echo "<b><a style='color:#00DE04'>Code</a> : " . htmlspecialchars($commit['code']) . "</b><br>";
            echo "<b><a style='color:#00DE04'>Time</a> : " . htmlspecialchars($commit['time']) . "</b><hr>";
        }
    }
?>
	</div>
<div class="log">
	<button onclick="showlog()"><svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M20 14.25C21.2426 14.25 22.25 13.2426 22.25 12C22.25 10.7574 21.2426 9.75 20 9.75C18.7574 9.75 17.75 10.7574 17.75 12C17.75 13.2426 18.7574 14.25 20 14.25Z" fill="#292D32"></path>
<path d="M20 6.25C21.2426 6.25 22.25 5.24264 22.25 4C22.25 2.75736 21.2426 1.75 20 1.75C18.7574 1.75 17.75 2.75736 17.75 4C17.75 5.24264 18.7574 6.25 20 6.25Z" fill="#292D32"></path>
<path d="M20 22.25C21.2426 22.25 22.25 21.2426 22.25 20C22.25 18.7574 21.2426 17.75 20 17.75C18.7574 17.75 17.75 18.7574 17.75 20C17.75 21.2426 18.7574 22.25 20 22.25Z" fill="#292D32"></path>
<path d="M4 14.25C5.24264 14.25 6.25 13.2426 6.25 12C6.25 10.7574 5.24264 9.75 4 9.75C2.75736 9.75 1.75 10.7574 1.75 12C1.75 13.2426 2.75736 14.25 4 14.25Z" fill="#292D32"></path>
<path d="M19 12.75C19.41 12.75 19.75 12.41 19.75 12C19.75 11.59 19.41 11.25 19 11.25H11.75V7C11.75 5.42 12.42 4.75 14 4.75H19C19.41 4.75 19.75 4.41 19.75 4C19.75 3.59 19.41 3.25 19 3.25H14C11.58 3.25 10.25 4.58 10.25 7V11.25H5C4.59 11.25 4.25 11.59 4.25 12C4.25 12.41 4.59 12.75 5 12.75H10.25V17C10.25 19.42 11.58 20.75 14 20.75H19C19.41 20.75 19.75 20.41 19.75 20C19.75 19.59 19.41 19.25 19 19.25H14C12.42 19.25 11.75 18.58 11.75 17V12.75H19Z" fill="#292D32"></path>
</svg></button>
	</div>
<script>
function updateLineNumbers() {
    const codeArea = document.getElementById('codeArea');
    const lineNumbers = document.getElementById('lineNumbers');
    const lines = codeArea.value.split('\n').length;
    let lineNumberText = '';
    for (let i = 1; i <= lines; i++) {
        lineNumberText += i + '<br>';
    }
    document.getElementById('lineNumbers').innerHTML = lineNumberText;
}
window.onload = updateLineNumbers;
function downloadCode() {
    const code = document.getElementById('codeArea').value;
    const blob = new Blob([code], { type: 'text/plain' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.download = 'code.txt';
    link.href = url;
    link.click();
    URL.revokeObjectURL(url);
}
function showlog() {
	document.getElementById("showlog").style.display = "block";
	}
</script>
</body>
</html>
