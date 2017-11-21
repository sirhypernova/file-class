<?php
class Files {
    /**
     * Base Directory to display files from
     */
	protected static $basedir;
	/**
	 * Option to prevent viewing of parent directory
	 */
	protected static $backlog;
	/**
	 * Allowed File Extentions
	 */
	protected static $alcontent;
	/**
	 * Maximum file size, in bytes
	 */
	protected static $maxsize;
	function __construct ($backlog = false, $basedir = __DIR__, $alcontent = ['js', 'css', 'txt', 'doc', 'docx', 'pdf', 'jpg', 'jpeg', 'png', 'gif'], $maxsize = 2097152) {
		self::$backlog = $backlog;
		self::$basedir = $basedir;
		self::$alcontent = $alcontent;
		self::$maxsize = $maxsize;
	}
	/**
	 * Get a File
	 */
    public function get($fname = null) {
        $basedir = Files::$basedir.'/';
        if (!file_exists($basedir)) {
            mkdir($basedir);
        }
        if ($fname == null) {
            return false;
        } else {
            if (Files::$backlog == false) {
                if (0 === strpos(realpath($basedir.'/'.$fname),$basedir)) {
                    $fpdir = $basedir.'/'.$fname;
                    if (file_exists($fpdir)) {
                        if (in_array(pathinfo($fpdir,PATHINFO_EXTENSION),Files::$alcontent)) {
                            return new File($fpdir);
                        } elseif (is_dir($fpdir)) {
                            return new Folder($fpdir);
                        }
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                $fpdir = $basedir.'/'.$fname;
                if (file_exists($fpdir)) {
                    if (in_array(pathinfo($fpdir,PATHINFO_EXTENSION),Files::$alcontent)) {
                        return new File($fpdir);
                    } elseif (is_dir($fpdir)) {
                        return new Folder($fpdir);
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }
        }
        
        
    }
    /**
     * Create a new File
     */
    public function create ($filename, $type = "file", $dir = null, $content = null) {
        $basedir = Files::$basedir.'/';
        $fext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!in_array($fext, Files::$alcontent) && $type != "dir") {
            return false;
        }
        if (!file_exists($basedir)) {
            mkdir($basedir);
        }
        if (strlen($content) > Files::$maxsize) {
            return false;
        }
        if ($dir == null) {
            if (file_exists($basedir.'/'.$filename) && file_exists($basedir.'/'.$dir.'/')) {
                return false;
            } else {
                if ($type == "file") {
                    if ($content == null) {
                        file_put_contents($basedir.'/'.$filename, "");
                        return new File($basedir.'/'.$filename);
                    } else {
                        file_put_contents($basedir.'/'.$filename, $content);
                        return new File($basedir.'/'.$filename);
                    }
                } elseif ($type == "dir" || $type == "directory" || $type == "folder") {
                    mkdir($basedir.'/'.$filename);
                    return new Folder($basedir.'/'.$filename);
                }
            }
        } else {
            if (Files::$backlog == false) {
                if (0 === strpos(realpath($basedir.'/'.$dir),$basedir)) {
                    if (file_exists($basedir.'/'.$dir.'/'.$filename) && file_exists($basedir.'/'.$dir.'/')) {
                        return false;
                    } else {
                        if ($type == "file") {
                            if ($content == null) {
                                file_put_contents($basedir.'/'.$dir.'/'.$filename, "");
                                return new File($basedir.'/'.$dir.'/'.$filename);
                            } else {
                                file_put_contents($basedir.'/'.$dir.'/'.$filename, $content);
                                return new File($basedir.'/'.$dir.'/'.$filename);
                            }
                        } elseif ($type == "dir" || $type == "directory" || $type == "folder") {
                            mkdir($basedir.'/'.$dir.'/'.$filename);
                            return new Folder($basedir.'/'.$dir.'/'.$filename);
                        }
                    }
                }
            } else {
                if (file_exists($basedir.'/'.$dir.'/'.$filename) && file_exists($basedir.'/'.$dir.'/')) {
                    return false;
                } else {
                    if ($type == "file") {
                        if ($content == null) {
                            file_put_contents($basedir.'/'.$dir.'/'.$filename, "");
                            return new File($basedir.'/'.$dir.'/'.$filename);
                        } else {
                            file_put_contents($basedir.'/'.$dir.'/'.$filename, $content);
                            return new File($basedir.'/'.$dir.'/'.$filename);
                        }
                    } elseif ($type == "dir" || $type == "directory" || $type == "folder") {
                        mkdir($basedir.'/'.$dir.'/'.$filename);
                        return new Folder($basedir.'/'.$dir.'/'.$filename);
                    }
                }
            }
        }
        
    }
    /**
     * Upload a file
     */
    public function upload ($file, $dir = null) {
        $basedir = Files::$basedir.'/';
        if (isset($file)) {
            if ($dir == null) {
                $canupload = 1;
                $fileType = pathinfo($file["name"], PATHINFO_EXTENSION);
                if ($file["size"] > Files::$maxsize) {
                    return false;
                }
                if (!in_array($fileType, Files::$alcontent)) {
                    return false;
                }
                if (file_exists($tfile)) {
                    return false;
                }
                if (move_uploaded_file($file["tmp_name"], "$basedir/{$file["name"]}")) {
                    if (is_dir("$basedir/$dir/{$file["name"]}")) {
                        return new Folder("$basedir/$dir/{$file["name"]}");
                    } else {
                        return new File("$basedir/$dir/{$file["name"]}");
                    }
                } else {
                    return false;
                }
            } else {
                if (Files::$backlog == false) {
                    if (0 === strpos(realpath($basedir.'/'.$dir),$basedir)) {
                        $canupload = 1;
                        $fileType = pathinfo($file["name"], PATHINFO_EXTENSION);
                        if ($file["size"] > 2 * 1024 * 1024) {
                            return false;
                        }
                        if (!in_array($fileType, Files::$alcontent)) {
                            return false;
                        }
                        if (file_exists($basedir.'/'.$dir.'/'.$file["name"])) {
                            return false;
                        }
                        if (move_uploaded_file($file["tmp_name"], "$basedir/$dir/{$file["name"]}")) {
                            if (is_dir("$basedir/$dir/{$file["name"]}")) {
                                return new Folder("$basedir/$dir/{$file["name"]}");
                            } else {
                                return new File("$basedir/$dir/{$file["name"]}");
                            }
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                } else {
                    $canupload = 1;
                    $fileType = pathinfo($file["name"], PATHINFO_EXTENSION);
                    if ($file["size"] > 2 * 1024 * 1024) {
                        return false;
                    }
                    if (!in_array($fileType, Files::$alcontent)) {
                        return false;
                    }
                    if (file_exists($basedir.'/'.$dir.'/'.$file["name"])) {
                        return false;
                    }
                    if (move_uploaded_file($file["tmp_name"], "$basedir/$dir/{$file["name"]}")) {
                        if (is_dir("$basedir/$dir/{$file["name"]}")) {
                            return new Folder("$basedir/$dir/{$file["name"]}");
                        } else {
                            return new File("$basedir/$dir/{$file["name"]}");
                        }
                    } else {
                        return false;
                    }
                }
            }
        } else {
            return false;
        }
    }
    /**
     * Get an array of all files and folders
     */
	public function getAll($dir = null) {
        $basedir = Files::$basedir.'/';
        if (!file_exists($basedir)) {
            mkdir($basedir);
        }
        if ($dir == null) {
            $files = array_diff(scandir($basedir), ['.', '..']);
            $allfiles = [];
            foreach ($files as $file) {
                if (in_array(pathinfo($file,PATHINFO_EXTENSION),Files::$alcontent) || is_dir($basedir.'/'.$file)) {
                    if (is_dir($basedir.'/'.$file)) {
                        $fpdir = realpath($basedir.'/'.$file);
                        $allfiles['folders'][] = new Folder($fpdir);
                    } else {
                        $fpdir = realpath($basedir.'/'.$file);
                        $allfiles['files'][] = new File($fpdir);
                    }
                }
            }
            return $allfiles;
        } else {
        	if (Files::$backlog == false) {
	            if (0 === strpos(realpath($basedir.'/'.$dir),$basedir)) {
	                $files = array_diff(scandir($basedir.'/'.$dir), ['.','..']);
	                $allfiles = [];
	                $allfiles['folders'] = [];
	                $allfiles['files'] = [];
	                foreach ($files as $file) {
	                    if (in_array(pathinfo($file,PATHINFO_EXTENSION),Files::$alcontent) || is_dir($basedir.'/'.$dir.'/'.$file)) {
    	                    if (is_dir($basedir.'/'.$dir.'/'.$file)) {
    	                        $fpdir = realpath($basedir.'/'.$dir.'/'.$file);
    	                        $allfiles['folders'][] = new Folder($fpdir);
    	                    } else {
    	                        $fpdir = realpath($basedir.'/'.$dir.'/'.$file);
    	                        $allfiles['files'][] = new File($fpdir);
    	                    }
	                    }
	                }
	                return $allfiles;   
	            } else {
	                return false;
	            }
        	} else {
        		$files = array_diff(scandir($basedir.'/'.$dir), ['.','..']);
                $allfiles = [];
                $allfiles['folders'] = [];
                $allfiles['files'] = [];
                foreach ($files as $file) {
                    if (in_array(pathinfo($file,PATHINFO_EXTENSION),Files::$alcontent) || is_dir($basedir.'/'.$dir.'/'.$file)) {
                        if (is_dir($basedir.'/'.$dir.'/'.$file)) {
                            $fpdir = realpath($basedir.'/'.$dir.'/'.$file);
                            $allfiles['folders'][] = new Folder($fpdir);
                        } else {
                            $fpdir = realpath($basedir.'/'.$dir.'/'.$file);
                            $allfiles['files'][] = new File($fpdir);
                        }
                    }
                }
                return $allfiles;  
        	}
        }
        
        
    }
}
/**
 * File class, for individual files
 */
class File extends Files {
    private $pathinfo;
    function __construct ($fpdir) {
        $pathinfo = pathinfo($fpdir);
        /**
         * File Extension
         */
        $this->ext = $pathinfo['extension'];
        /**
         * File size in bytes
         */
        $this->sizeb = filesize($fpdir);
        if ($this->sizeb >= 1024) {
            /**
             * File size in kilobytes
             */
            $this->sizekb = round(filesize($fpdir) / 1024, 2);
        }
        if ($this->sizeb >= 1024*1024) {
            /**
             * File size in megabytes
             */
            $this->sizemb = round(filesize($fpdir) / 1024 / 1024, 2);
        }
        /**
         * Full name of file
         */
        $this->name = $pathinfo['filename'];
        $this->fullname = $pathinfo['basename'];
        $this->fullpath = realpath($fpdir);
    }
    function __toString() {
        return $this->fullname;
    }
    /**
     * Delete file
     */
    public function delete () {
        if (file_exists($this->fullpath)) {
            if (!in_array($this->ext, Files::$alcontent)) {
                return false;
            }
            if (unlink($this->fullpath)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    /**
     * Get file data
     */
    public function data () {
        if (file_exists($this->fullpath)) {
                return file_get_contents($this->fullpath);
        } else {
            return false;
        }
    }
    /**
     * Set file contents
     */
    public function edit ($content = null) {
        $basedir = realpath(Files::$basedir.'/');
        if ($this->type == "dir") {
            return false;
        }
        if (!file_exists($basedir)) {
            mkdir($basedir);
        }
        if (strlen($content) > Files::$maxsize) {
            return false;
        }
        if (0 === strpos(realpath($this->fullpath),realpath($basedir))) {
            if ($content == null) {
                file_put_contents($this->fullpath, "");
                return new File(realpath($this->fullpath));
            } else {
                file_put_contents($this->fullpath, $content);
                return new File(realpath($this->fullpath));
            }
        }
        
    }
}
class Folder extends Files {
    private $pathinfo;
    function __construct ($fpdir) {
        $pathinfo = pathinfo($fpdir);
        $this->name = $pathinfo['basename'];
        $this->fullpath = realpath($fpdir);
    }
    function __toString() {
        return $this->name;
    }
    /**
	 * Get a File
	 */
    public function get($fname = null) {
        $basedir = $this->fullpath;
        if (!file_exists($basedir)) {
            mkdir($basedir);
        }
        if ($fname == null) {
            return false;
        } else {
            if (Files::$backlog == false) {
                if (0 === strpos(realpath($basedir.'/'.$fname),$basedir)) {
                    $fpdir = $basedir.'/'.$fname;
                    if (file_exists($fpdir)) {
                        if (in_array(pathinfo($fpdir,PATHINFO_EXTENSION),Files::$alcontent)) {
                            return new File($fpdir);
                        } elseif (is_dir($fpdir)) {
                            return new Folder($fpdir);
                        }
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                $fpdir = $basedir.'/'.$fname;
                if (file_exists($fpdir)) {
                    if (in_array(pathinfo($fpdir,PATHINFO_EXTENSION),Files::$alcontent)) {
                        return new File($fpdir);
                    } elseif (is_dir($fpdir)) {
                        return new Folder($fpdir);
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }
        }
        
        
    }
    /**
     * Get an array of all files and folders
     */
    public function getAll($dir = null) {
        $basedir = $this->fullpath;
        if (!file_exists($basedir)) {
            mkdir($basedir);
        }
        if ($dir == null) {
            $files = array_diff(scandir($basedir), ['.', '..']);
            $allfiles = [];
            foreach ($files as $file) {
                if (in_array(pathinfo($file,PATHINFO_EXTENSION),Files::$alcontent) || is_dir($basedir.'/'.$file)) {
                    if (is_dir($basedir.'/'.$file)) {
                        $fpdir = realpath($basedir.'/'.$file);
                        $allfiles['folders'][] = new Folder($fpdir);
                    } else {
                        $fpdir = realpath($basedir.'/'.$file);
                        $allfiles['files'][] = new File($fpdir);
                    }
                }
            }
            return $allfiles;
        } else {
        	if (Files::$backlog == false) {
	            if (0 === strpos(realpath($basedir.'/'.$dir),$basedir)) {
	                $files = array_diff(scandir($basedir.'/'.$dir), ['.','..']);
	                $allfiles = [];
	                $allfiles['folders'] = [];
	                $allfiles['files'] = [];
	                foreach ($files as $file) {
	                    if (in_array(pathinfo($file,PATHINFO_EXTENSION),Files::$alcontent) || is_dir($basedir.'/'.$dir.'/'.$file)) {
    	                    if (is_dir($basedir.'/'.$dir.'/'.$file)) {
    	                        $fpdir = realpath($basedir.'/'.$dir.'/'.$file);
    	                        $allfiles['folders'][] = new Folder($fpdir);
    	                    } else {
    	                        $fpdir = realpath($basedir.'/'.$dir.'/'.$file);
    	                        $allfiles['files'][] = new File($fpdir);
    	                    }
	                    }
	                }
	                return $allfiles;   
	            } else {
	                return false;
	            }
        	} else {
        		$files = array_diff(scandir($basedir.'/'.$dir), ['.','..']);
                $allfiles = [];
                $allfiles['folders'] = [];
                $allfiles['files'] = [];
                foreach ($files as $file) {
                    if (in_array(pathinfo($file,PATHINFO_EXTENSION),Files::$alcontent) || is_dir($basedir.'/'.$dir.'/'.$file)) {
                        if (is_dir($basedir.'/'.$dir.'/'.$file)) {
                            $fpdir = realpath($basedir.'/'.$dir.'/'.$file);
                            $allfiles['folders'][] = new Folder($fpdir);
                        } else {
                            $fpdir = realpath($basedir.'/'.$dir.'/'.$file);
                            $allfiles['files'][] = new File($fpdir);
                        }
                    }
                }
                return $allfiles;  
        	}
        }
        
        
    }
    /**
     * Upload a file
     */
    public function upload ($file, $dir = null) {
        $basedir = $this->fullpath;
        if (isset($file)) {
            if ($dir == null) {
                $canupload = 1;
                $fileType = pathinfo($file["name"], PATHINFO_EXTENSION);
                if ($file["size"] > Files::$maxsize) {
                    return false;
                }
                if (!in_array($fileType, Files::$alcontent)) {
                    return false;
                }
                if (file_exists($tfile)) {
                    return false;
                }
                if (move_uploaded_file($file["tmp_name"], "$basedir/{$file["name"]}")) {
                    if (is_dir("$basedir/$dir/{$file["name"]}")) {
                        return new Folder("$basedir/$dir/{$file["name"]}");
                    } else {
                        return new File("$basedir/$dir/{$file["name"]}");
                    }
                } else {
                    return false;
                }
            } else {
                if (Files::$backlog == false) {
                    if (0 === strpos(realpath($basedir.'/'.$dir),$basedir)) {
                        $canupload = 1;
                        $fileType = pathinfo($file["name"], PATHINFO_EXTENSION);
                        if ($file["size"] > 2 * 1024 * 1024) {
                            return false;
                        }
                        if (!in_array($fileType, Files::$alcontent)) {
                            return false;
                        }
                        if (file_exists($basedir.'/'.$dir.'/'.$file["name"])) {
                            return false;
                        }
                        if (move_uploaded_file($file["tmp_name"], "$basedir/$dir/{$file["name"]}")) {
                            if (is_dir("$basedir/$dir/{$file["name"]}")) {
                                return new Folder("$basedir/$dir/{$file["name"]}");
                            } else {
                                return new File("$basedir/$dir/{$file["name"]}");
                            }
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                } else {
                    $canupload = 1;
                    $fileType = pathinfo($file["name"], PATHINFO_EXTENSION);
                    if ($file["size"] > 2 * 1024 * 1024) {
                        return false;
                    }
                    if (!in_array($fileType, Files::$alcontent)) {
                        return false;
                    }
                    if (file_exists($basedir.'/'.$dir.'/'.$file["name"])) {
                        return false;
                    }
                    if (move_uploaded_file($file["tmp_name"], "$basedir/$dir/{$file["name"]}")) {
                        if (is_dir("$basedir/$dir/{$file["name"]}")) {
                            return new Folder("$basedir/$dir/{$file["name"]}");
                        } else {
                            return new File("$basedir/$dir/{$file["name"]}");
                        }
                    } else {
                        return false;
                    }
                }
            }
        } else {
            return false;
        }
    }
    /**
     * Create a new File
     */
    public function create ($filename, $type = "file", $dir = null, $content = null) {
        $basedir = $this->fullpath;
        $fext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!in_array($fext, Files::$alcontent) && $type != "dir") {
            return false;
        }
        if (!file_exists($basedir)) {
            mkdir($basedir);
        }
        if (strlen($content) > Files::$maxsize) {
            return false;
        }
        if ($dir == null) {
            if (file_exists($basedir.'/'.$filename) && file_exists($basedir.'/'.$dir.'/')) {
                return false;
            } else {
                if ($type == "file") {
                    if ($content == null) {
                        file_put_contents($basedir.'/'.$filename, "");
                        return new File($basedir.'/'.$filename);
                    } else {
                        file_put_contents($basedir.'/'.$filename, $content);
                        return new File($basedir.'/'.$filename);
                    }
                } elseif ($type == "dir" || $type == "directory" || $type == "folder") {
                    mkdir($basedir.'/'.$filename);
                    return new Folder($basedir.'/'.$filename);
                }
            }
        } else {
            if (Files::$backlog == false) {
                if (0 === strpos(realpath($basedir.'/'.$dir),$basedir)) {
                    if (file_exists($basedir.'/'.$dir.'/'.$filename) && file_exists($basedir.'/'.$dir.'/')) {
                        return false;
                    } else {
                        if ($type == "file") {
                            if ($content == null) {
                                file_put_contents($basedir.'/'.$dir.'/'.$filename, "");
                                return new File($basedir.'/'.$dir.'/'.$filename);
                            } else {
                                file_put_contents($basedir.'/'.$dir.'/'.$filename, $content);
                                return new File($basedir.'/'.$dir.'/'.$filename);
                            }
                        } elseif ($type == "dir" || $type == "directory" || $type == "folder") {
                            mkdir($basedir.'/'.$dir.'/'.$filename);
                            return new Folder($basedir.'/'.$dir.'/'.$filename);
                        }
                    }
                }
            } else {
                if (file_exists($basedir.'/'.$dir.'/'.$filename) && file_exists($basedir.'/'.$dir.'/')) {
                    return false;
                } else {
                    if ($type == "file") {
                        if ($content == null) {
                            file_put_contents($basedir.'/'.$dir.'/'.$filename, "");
                            return new File($basedir.'/'.$dir.'/'.$filename);
                        } else {
                            file_put_contents($basedir.'/'.$dir.'/'.$filename, $content);
                            return new File($basedir.'/'.$dir.'/'.$filename);
                        }
                    } elseif ($type == "dir" || $type == "directory" || $type == "folder") {
                        mkdir($basedir.'/'.$dir.'/'.$filename);
                        return new Folder($basedir.'/'.$dir.'/'.$filename);
                    }
                }
            }
        }
        
    }
    /**
     * Delete folder
     */
    public function delete () {
        if (file_exists($this->fullpath)) {
            if (is_dir($this->fullpath) && count(array_diff(scandir($this->fullpath), ['.', '..'])) == 0) {
                if (rmdir($this->fullpath)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                function rrmdir($src) {
                    $dir = opendir($src);
                    while(false !== ( $file = readdir($dir)) ) {
                        if (( $file != '.' ) && ( $file != '..' )) {
                            $full = $src . '/' . $file;
                            if ( is_dir($full) ) {
                                rrmdir($full);
                            }
                            else {
                                unlink($full);
                            }
                        }
                    }
                    closedir($dir);
                    rmdir($src);
                }
                $rmdir = rrmdir($this->fullpath);
                if ($rmdir == null) {
                    return true;
                } elseif ($rmdir = true) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }
    /**
     * Gets recursive directory size (in bytes)
     */
    public function size () {
        function rSize ($dir) {
            $folder = array_diff(scandir($dir), ['.', '..']);
            $filesize = 0;
            foreach ($folder as $file) {
                if (is_dir($dir.'/'.$file)) {
                    $filesize += rSize($dir.'/'.$file);
                } else {
                    $filesize += filesize($dir.'/'.$file);
                }
            }
            return $filesize;
        }
        return rSize($this->fullpath);
    }
}