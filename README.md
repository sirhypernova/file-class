# file-class
A file manager class written in PHP, by SirHyperNova

1. [Getting Started](#start)
2. [Usage](#usage)
  <ul>
    <li><a href="#usage">Class <code>Files</code> Parameters</a></li>
    <li><a href="#files-functions">Class <code>Files</code> Functions</a></li>
  </ul>

## <a id="start"></a>Getting Started
Firstly, download [class.php](class.php)
After that, it is quite simple to import this library in to your own PHP code
Use this:
```php
include_once('class.php');
```
## <a id="usage"></a> Usage
### Class `Files` parameters
After you have included the library in to your PHP file, you need the following parameters and rules
<br>All parameters are used, in order as part of the function `_construct`

<ul>
  <li>
    <b>$backlog</b> : Decides whether to allow viewing of the parent directory. Default: <code>false</code>
    <br><b>Value</b> : boolean
  </li>
  <li>
    <b>$basedir</b> : The origin directory to create the object based on. Default: <code>__DIR__</code>
    <br><b>Value</b> : string
  </li>
  <li>
    <b>$alcontent</b> : The allowed file extensions to read and write. Default: <code>['js', 'css', 'txt', 'doc', 'docx', 'pdf', 'jpg', 'jpeg', 'png', 'gif']</code>
    <br><b>Value</b> : Array
  </li>
  <li>
    <b>$maxsize</b> : The maximum file size to read and write. Default: <code>2097152</code>
    <br><b>Value</b> : Integer
  </li>
</ul>
Example:
<pre lang="php">
$files = new Files(false,__DIR__,['txt','jpg','png'],2097152);
</pre>
<h3><a id="files-functions"></a>Class <code>Files</code> functions</h3>
<ul>
  <li>
    <code>get ($fname = null)</code> : Get a file based off of its name (<code>$fname</code>) and path. Returns a <code>File</code> or <code>Folder</code> object, depending
  </li>
  <li>
    <code>create ($filename, $type = 'file', $dir = null, $content = null)</code> : Create a file with said parameters, <code>$filename</code> being the file name, <code>$type</code> being the file type (Folder/File), <code>$dir</code> being the directory to create the file in, and finally <code>$content</code>, which specifies the file content. Only usable with <code>$type = 'file'</code>. Returns a <code>File</code> or <code>Folder</code> object
  </li>
  <li>
    <code>upload ($file, $dir = null)</code> : Upload a file to said directory, <code>$file</code> being a form file, and <code>$dir</code> being the directory to upload the file to. Returns a <code>File</code> object
  </li>
</ul>
