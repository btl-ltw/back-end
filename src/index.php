<?php
header('Content-Type: application/json');

require '../vendor/autoload.php';

// Cấu hình bắt lỗi Exception
set_exception_handler(function ($exception) {
    http_response_code(500);
    echo json_encode([
        'date' => date('Y-m-d H:i:s'),
        'code' => "500",
        'message' => $exception->getMessage(),
        'file' => $exception->getFile(),
        'line' => $exception->getLine()
    ]);
});

// Cấu hình bắt lỗi PHP Warning, Notice
set_error_handler(function ($severity, $message, $file, $line) {
    http_response_code(500);
    echo json_encode([
        'date' => date('Y-m-d H:i:s'),
        'code' => "500",
        'message' => $message,
        'file' => $file,
        'line' => $line
    ]);
    exit();
});

function isDirectSubclass($childClass, $parentClass) {
    $parent = get_parent_class($childClass);
    return $parent === $parentClass;
}

try {
    spl_autoload_register(function ($class) {
        $fileStructure = [
            'controller',
            'model',
            'repository',
            'service'
        ];
        $pathName = [];

        foreach ($fileStructure as $file) {
            if(str_contains($class, ucfirst($file))) {
                $path    = "./" . "$file";

                array_push($pathName, $path . "/" . $class . ".php");

                $files = scandir($path);

                $directories = array_filter($files, function($item) use ($path) {
                    return is_dir($path . '/' . $item) && $item !== '.' && $item !== '..';
                });

                foreach ($directories as $direc) {
                    $pathFinder = $path . "/" . $direc .  "/" . $class . ".php";

                    array_push($pathName, $pathFinder);
                }

                break;
            }
        }

        if(count($pathName) === 0)
            return;

        $find = [false, null];

        foreach ($pathName as $path) {
            if (file_exists($path)) {
                $find = [true, $path];
                break;
            }
        }

        if (!$find[0]) {
            throw new Exception("Không tìm thấy controller thích hợp: " . $class);
        }

        require $find[1];

        if (!class_exists($class)) {
            throw new Exception("Không tìm thấy class: " . $class);
        }
    });

    $uri = $_SERVER["REQUEST_URI"];
    $parsed_url = parse_url($uri);
    $path = isset($parsed_url['path']) ? $parsed_url['path'] : '';
    $part = explode("/", $path);

    $n = count($part);

    if($n < 2 || !isset($part[$n-1]))
        throw new Exception("Endpoint không hợp lệ!");

    //Khởi tạo Env
    envLoaderService::loadEnv();

    //Kiểm tra cấu trúc URI
    $uri_path = [];

    for ($i = 0; $i < $n; $i++) {
        $className = isset($part[$i]) ? $part[$i] . "Controller" : null;

        if($className && class_exists($className)) {
//            $controller = new $className();

            array_push($uri_path, $className);
        } else {
            throw new Exception("Không tồn tại endpoint này " . $className);
        }
    }

    //Đi qua middleware
    for ($i = 0; $i < count($uri_path) - 1; $i ++) {
//        $class = get_class($uri_path[$i]);
//        $subclass = get_class($uri_path[$i+1]);

        if(!isDirectSubclass($uri_path[$i+1], $uri_path[$i]))
            throw new Exception("Cấu trúc api không hợp lệ");

//        if(method_exists($uri_path[$i], "middleware"))
//            $uri_path[$i]->middleware();
    }

    // Gửi request đến controller
    $className = isset($part[$n-1]) ? ($part[$n-1]) . 'Controller' : null;

    $controller = new $className();

    $method = $_SERVER['REQUEST_METHOD'];

    if (method_exists($controller, $method)) {
        $controller->$method();
    } else {
        throw new Exception("Method này không hợp lệ");
    }

    //Trả về Http.ok
    http_response_code(200);
    $responseData = [
        'date' => date('Y-m-d H:i:s'),
        'code' => "200",
        'message' => "Call api thành công nhưng không có kết quả trả về!",
        'path' => $_SERVER["REQUEST_URI"]
    ];

    echo json_encode($responseData);
} catch (Exception $e) {
    http_response_code(400);

    $responseData = [
        'date' => date('Y-m-d H:i:s'),
        'code' => "400",
        'message' => $e->getMessage(),
        'path' => $_SERVER["REQUEST_URI"]
    ];

    echo json_encode($responseData);
}