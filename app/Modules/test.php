<?php
$dir = new DirectoryIterator(dirname(__FILE__));
foreach ($dir as $fileinfo) {
	if(is_dir($fileinfo)){
		$dir2 = new DirectoryIterator(dirname(__FILE__).'/'.$fileinfo);
		foreach ($dir2 as $fileinfo2) {
			// if(is_dir($fileinfo2)){
				// if(strpos($fileinfo2,'controllers')){
				if($fileinfo2->getFilename()=='models'){
				echo ($fileinfo2->getFilename()."\n");
					// echo($fileinfo2->getFilename());
					$dir3 = new DirectoryIterator(dirname(__FILE__).'/'.$fileinfo.'/'.$fileinfo2);
					foreach ($dir3 as $fileinfo3) {
						if(pathinfo($fileinfo3, PATHINFO_EXTENSION)=='php'){
							if (!$fileinfo3->isDot()) {
								$new=ucfirst ($fileinfo3) ;
								rename(dirname(__FILE__).'/'.$fileinfo.'/'.$fileinfo2.'/'.$fileinfo3,dirname(__FILE__).'/'.$fileinfo.'/'.$fileinfo2.'/'.$new);
								// echo 'done';
								// var_dump($fileinfo3->getFilename().'===='.$new);
							}
						}
					}
				}
			// }
		}
	}
}
?> 