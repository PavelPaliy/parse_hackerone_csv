<?php
if(!isset($argv[1])){
    print("Введите расположение csv файла!".PHP_EOL);
    die;
}

$path = $argv[1];
$row = 1;

$result = array (
    'target' =>
        array (
            'scope' =>
                array (
                    'advanced_mode' => true,
                    'exclude' =>
                        array (
                        ),
                    'include' =>
                        array (


                        ),
                ),
        ),
);
if (($handle = fopen($path, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        if(isset($data[1]) && $data[1]==="URL"){

            $subArr  = array (
                'enabled' => true,
                // 'file' => '^/.*',
                'host' => $data[0],
                'protocol' => 'any',
            );
            if(isset($data[4]) && $data[4]==='true'){
                $result['target']['scope']['include'][] = $subArr;
            }else  if(isset($data[4]) && $data[4]==='false'){
                $result['target']['scope']['exclude'][] = $subArr;
            }

        }else if(isset($data[1]) && $data[1]==="WILDCARD"){
            $regex = $data[0];
            $regex = str_replace(".", "\.", $regex);
            $regex = str_replace("*", ".*", $regex);



            $subArr  = array (
                'enabled' => true,
                // 'file' => '^/.*',
                'host' => $regex,
                'protocol' => 'any',
            );
            if(isset($data[4]) && $data[4]==='true'){
                $result['target']['scope']['include'][] = $subArr;
            }else  if(isset($data[4]) && $data[4]==='false'){
                $result['target']['scope']['exclude'][] = $subArr;
            }

        }
    }
    fclose($handle);
}

file_put_contents("result.json", json_encode($result));