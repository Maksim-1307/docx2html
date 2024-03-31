<h2>features supported:</h2>
<ul>
    <li>glow property</li>
    <li>p tag</li>
</ul>

<h2>planned features:</h2>
<ul>
    <li>save_html and get_html functions</li>
    <li>background-color, color, font-size, border, text-decoration and other properties</li>
    <li>headers, tables, images and other elements</li>
</ul>
<h2>Usage</h2>
<p>
require_once "DocxToHtml.php";

$handler = new Handler("path/to/your/file.docx");
echo $handler->get_html(); // returns html code
$handler->save_html("path/to/save"); // saves an html file
</p>