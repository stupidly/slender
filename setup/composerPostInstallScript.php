<?php
    function copyfolder($src, $dst){
        foreach (scandir($src) as $file) {
            $srcfile = rtrim($src, '/') .'/'. $file; 
            $dstfile = rtrim($dst, '/') .'/'. $file; 
            if (!is_readable($srcfile)) { 
                continue; 
            } 
            if ($file != '.' && $file != '..') {
                if (is_dir($srcfile)) {
                    if (!file_exists($dstfile)) {
                        mkdir($dstfile); 
                    } 
                    xcopy($srcfile, $dstfile); 
                } else { 
                    copy($srcfile, $dstfile); 
                } 
            } 
        }
    }
    set_include_path("..");
	file_exists(".env") || copy(".env.example", ".env");
    file_exists("log") || mkdir("log");
    file_exists("public/img") || mkdir("public/img");
    file_exists("public/js") || mkdir("public/js");
    file_exists("public/js/bootstrap.js") || copy("vendor/twbs/bootstrap/dist/js/bootstrap.js", "public/js/bootstrap.js");
    file_exists("public/js/jquery.js") || copy("vendor/components/jquery/jquery.js", "public/js/jquery.js");
    file_exists("public/css") || mkdir("public/css");
    file_exists("resources/scss") || mkdir("resources/scss");
    if(!file_exists("resources/scss/bootstrap.custom.scss")){
    	$inPath = "vendor/twbs/bootstrap/scss/bootstrap.scss";
    	$outPath = "resources/scss/bootstrap.custom.scss";
		$str = file_get_contents($inPath);
		$str = preg_replace("/\@import \"/", "@import \"../../vendor/twbs/bootstrap/scss/", $str);
		file_put_contents($outPath, $str);
    }
    if(!file_exists("resources/scss/font-awesome.custom.scss")){
        $inPath = "vendor/components/font-awesome/scss/fontawesome.scss";
        $outPath = "resources/scss/font-awesome.custom.scss";
        $str = file_get_contents($inPath);
        $str = preg_replace("/\@import '/", "@import '../../vendor/components/font-awesome/scss/", $str);
        $str = preg_replace("/'/", "\"", $str);
        file_put_contents($outPath, $str);
        $file = fopen($outPath, "a");
        fwrite($file, "@import \"../../vendor/components/font-awesome/scss/fa-regular.scss\";" . PHP_EOL);
        fwrite($file, "@import \"../../vendor/components/font-awesome/scss/fa-solid.scss\";" . PHP_EOL);
        fwrite($file, "@import \"../../vendor/components/font-awesome/scss/fa-brands.scss\";" . PHP_EOL);
    }
    if(!file_exists("public/webfonts")){
        mkdir("public/webfonts");
        $inPath = "vendor/components/font-awesome/webfonts";
        $outPath = "public/webfonts";
        copyfolder($inPath, $outPath);
    }
    file_exists("public/css/app.css") || copy("vendor/twbs/bootstrap/dist/css/bootstrap.css", "public/css/app.css");
