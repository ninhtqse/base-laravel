<?php

namespace Infrastructure\Libraries;

use Illuminate\Support\Facades\Storage;

class HelperFunction
{
    public function __construct()
    {
    }

    public function genPassword(int $length = 6): string
    {
        $arrChar = [
            'Q','W','E','R','T','Y','U','I','O','P','A','S','D','F','G','H','J','K','L','Z','X','C','V','B','N','M'
        ];
        $arrCharSpecial = ['!','@','#','$','%','^','&','*'];
        $pass = $arrChar[rand(0, count($arrChar) - 1)] .
            $arrCharSpecial[rand(0, count($arrCharSpecial) - 1)] .
            rand(0, 9);
        for ($i = 0; $i < $length - 3; $i++) {
            $upLow = rand(1, 2);
            if ($upLow == 1) {
                $pass = $pass . $arrChar[rand(0, count($arrChar) - 1)];
            } else {
                $pass = $pass . strtolower($arrChar[rand(0, count($arrChar) - 1)]);
            }
        }
        return $pass;
    }

    /**
     * @param array $data
     * @param string $keyFile: Chuỗi base64 encode data file
     * @param string $folder: Vị trí upload file
     * @param bool $useFileName: Không nối uuid vào file_name
     */
    public function createFileBase64($data, $keyFile, $folder, $useFileName = true): array
    {
        if (!@$data[$keyFile]) {
            $data[$keyFile] = 1;
            unset($data[$keyFile]);
            return $data;
        }
        $fileName = $data[$keyFile]['file_name'];
        $content = $data[$keyFile]['file_data'];
        $time = date('Y-m-d');
        $folderYear = date('Y', strtotime($time));
        $folderMonth = date('m', strtotime($time));
        $sCurrentDay = date('d', strtotime($time));

        preg_match('/.([0-9]+) /', microtime(), $m);
        $storage = Storage::disk('public');
        $folder = 'uploads' . "/$folder/" . $folderYear . '/' . $folderMonth . '/' . $sCurrentDay;
        $checkDirectory = $storage->exists($folder);
        if (!$checkDirectory) {
            $storage->makeDirectory($folder);
        }
        if ($useFileName) {
            $fileName = uuid() . "_$fileName";
        }
        $storage->put($folder . '/' . $fileName, base64_decode($content), 'public');
        $data[$keyFile] = domain() . '/storage' . '/' . $folder . '/' . $fileName;
        return $data;
    }

    /**
     * @param array $data: Chuỗi base64 encode data file
     * @param string $keyFile
     * @param string $folder: Vị trí upload file
     * @param string $pathFileOld: Vị trí file cũ
     * @param bool $useFileName: Không nối uuid vào file_name
     */
    public function updateFileBase64($data, $keyFile, $folder, $pathFileOld, $useFileName = true)
    {
        if (isset($data[$keyFile])) {
            $content = $data[$keyFile]['file_data'];
            $fileName = $data[$keyFile]['file_name'];
            if ($pathFileOld) {
                $this->deleteFileBase64($pathFileOld);
            }
            $time = date('Y-m-d');
            $folderYear = date('Y', strtotime($time));
            $folderMonth = date('m', strtotime($time));
            $sCurrentDay = date('d', strtotime($time));

            preg_match('/.([0-9]+) /', microtime(), $m);
            $storage = Storage::disk('public');
            $folder = 'uploads' . "/$folder/" . $folderYear . '/' . $folderMonth . '/' . $sCurrentDay;
            $checkDirectory = $storage->exists($folder);
            if (!$checkDirectory) {
                $storage->makeDirectory($folder);
            }
            if ($useFileName) {
                $fileName = uuid() . "_$fileName";
            }
            $storage->put($folder . '/' . $fileName, base64_decode($content), 'public');
            $data[$keyFile] = domain() . '/storage' . '/' . $folder . '/' . $fileName;
        }
        return $data;
    }

    /**
     * @fileName: Đường dẫn file
     */
    public function deleteFileBase64($fileName)
    {
        $fileName = \str_replace(domain() . '/storage', '', $fileName);
        if ($fileName) {
            Storage::disk('public')->delete($fileName);
        }
    }

    /**
     * @pathFile: Đường dẫn file
     * @hostName: Nếu đường dẫn file có host name. Truyền true để bỏ đi
     */
    public function getFileBase64($pathFile, $hostName = false)
    {
        if ($hostName) {
            $pathFile = str_replace(domain().'/storage/', '', $pathFile);
        }
        return Storage::disk('public')->get($pathFile);
    }

    /**
     * @file_name: Tên file
     * @file_data: Nội dung file đã được base64_encode
     * @folder: folder lưu file
     */
    public function saveTempFile($file_name, $file_data, $folder)
    {
        $path = $folder.'/'.date('Ymd').'/'.uuid().'_'.$file_name;
        Storage::disk('temp')->put($path, base64_decode($file_data));
        return $path;
    }

    public function deleteTempFile($path)
    {
        return Storage::disk('temp')->delete($path);
    }

    public function getPathTempFile($path)
    {
        return Storage::disk('temp')->path($path);
    }
}
