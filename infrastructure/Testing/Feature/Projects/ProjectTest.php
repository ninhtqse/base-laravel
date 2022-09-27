<?php

namespace Infrastructure\Testing\Feature\Projects;

use Infrastructure\Testing\BaseTest\BaseFeatureTrait;
use Infrastructure\Testing\TestCase;
use Maatwebsite\Excel\Facades\Excel;

class ProjectTest extends TestCase
{
    use BaseFeatureTrait;

    public function setup(): void
    {
        parent::setup();
    }

    const CASE1 = 1;
    const CASE2 = 2;
    const CASE3 = 3;
    const CASE4 = 4;

    /**
     * @param array $arr
     * @dataProvider data_provider_login
     */
    public function test_login($arr)
    {
        //Mock Facades
        Excel::shouldReceive('import')->once()->andReturnTrue();
        //Call Api
        $response = $this->json('POST','/api/v1/projects/import/validate', $arr, []);
        switch ($arr['case']) {
            case self::CASE1:
                $response->assertStatus(200)->assertJson([
                    'status' => "success",
                    'code' => "AWS007",
                ]);
                break;
            case self::CASE2:
                $response->assertStatus(200)->assertJson([
                    'status' => "success",
                    'code' => "AWS007",
                    'data' => [
                        "valid_csv" => false
                    ]
                ]);
                break;
            case self::CASE3:
                $response->assertStatus(200)->assertJson([
                    'status' => "error",
                    'code' => "AWE004"
                ]);
                break;
            case self::CASE4:
                $response->assertStatus(200)->assertJson([
                    'status' => "error",
                    'code' => "AWE004"
                ]);
                break;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */
    public function data_provider_login()
    {
        //success
        $case1 = [
            'project' => [
                'file_name' => 'test1',
                'file_data' => 'IuODl+ODreOCuOOCp+OCr+ODiOWQje+8iDMw5paH5a2X5Lul5LiL77yJIiwi5ouF5b2T6ICFIiwi6ZaL5aeL5bm05pyI5pelIiwi57WC5LqG5bm05pyI5pelIiwi6KiA6KqeIOKAu++8iOacgOS9jjHjgaTvvIkiLCLntYzpqJMiLCLjg4Tjg7zjg6vvvIjmnIDlpKfvvJPjgaTvvIkiLCLntYzpqJNfMSIsIk9T44O755Kw5aKD77yI5pyA5aSnM+OBpO+8iSIsIue1jOmok18yIiwiRGF0YWJhc2UiLCLntYzpqJNfMyIsIkZX44O744Gd44Gu5LuW77yI5pyA5aSnNuOBpO+8iSIsIue1jOmok180Iiwi5qWt5YuZ5YaF5a6577yINTAw5paH5a2X5Lul5LiL77yJIOKAuyIsIuWPguiAg1VSTCIsIuS+nemgvOWFg+S8muekvuWQjSIsIuWLn+mbhuS6uuaVsCIsIuaciOmhjeWNmOS+oe+8iOacgOS9ju+8iSIsIuaciOmhjeWNmOS+oe+8iOacgOWkp++8iSIsIuWLn+mbhuiBt+eoriIsIuOBneOBruS7liIsIuWLn+mbhuiBt+WLmeWGheWuueKAouW/heimgeOBquOCueOCreODq+KAouadoeS7tuOBquOBqSIsIuWLn+mbhuOCueODhuODvOOCv+OCuSIsIuWLpOWLmeWcsCjpg73pgZPlupznnIwpIiwi5Yuk5YuZ5ZywIiwi5pyA5a+E6aeFIiwi6Z2i6KuH5Zue5pWwIiwi5Yuf6ZuG5Yi26ZmQIiwi5ouF5b2T6ICF5oOF5aCxIiwi5YKZ6ICDIg0KIuS+iynpioDooYzlkJHjgZHli5jlrprns7vjgrfjgrnjg4bjg6DmlLnkv64iLCLnrqHnkIbogIXjga7ogbciLCI3LzMvMjAyMiIsIjEwLzE1LzIwMjIiLCLkvospIEphdmE7VU1MIEJQTU47UHJlemnliLbkvZw7TW9ubyBEZXZlbG9wO01pY3Jvc29mdCBPbmVub3RlO1dpbmRvd3MgU2VydmVy5qeL56+J44O76YGL55SoO0VESVVTIFBybzU7RkFTUDtDb3JlIGRhdGE7QXBwbGUgTG9naWNQcm87VUkgVVjjg4fjgrbjgqTjg7M7RGVzaWduIHBhdHRlcm47SFAgT3BlbnZpZXcgZGVzaWduIiwi57WM6aiT44Gq44GXOzHlubTku6XkuIs7MeW5tDsy5bm0OzPlubQ7NOW5tDs15bm0OzblubQ7N+W5tDs45bm0OznlubQ7MTDlubQ7MTDlubTku6XkuIoiLCJTbGFjayIsIjPlubQiLCJDZW50T1MiLCIy5bm0IiwiT3JhY2xlIiwiMuW5tCIsIlNwcmluZyBCb290IiwiMuW5tCIsIualreWLmeWGheWuueOCkuabuOOBjeOBvuOBl+OCh+OBhiIsIiIsIkFNRUxBIFRlY2hub2xvZ3kuIEpTQyIsIjEyIiwiMTAwMDAiLCIyMDAwMCIsIiIsIi5ORVTnkrDlooPjgoRNaWNyb3NvZnQgQXp1cmXjgavplqLjgZnjgovjgqLjg5fjg6rjgrHjg7zjgrfjg6fjg7PplovnmbrntYzpqJMiLCLpgbjmip7jgZfjgabjgY/jgaDjgZXjgYQiLCIiLCIiLCIiLCIiLCIiLCIiLCIiLCIiDQoi44GT44GT77yIM+ODrOOCs+ODvOODieebru+8ieOBi+OCieWun+mam+OBq+eZu+mMsuOBmeOCi+ODl+ODreOCuOOCp+OCr+ODiOaDheWgseOCkuiomOi8ieOBl+OBpuOBj+OBoOOBlSIsIiIsIiIsIiIsIiIsIiIsIiIsIiIsIiIsIiIsIiIsIiIsIiIsIiIsIiIsIiIsIiIsIiIsIiIsIiIsIiIsIiIsIiIsIiIsIiIsIiIsIiIsIiIsIiIsIiIsIiINCiJwcm9qZWN0IDIxMSIsIm5pbmh0cXNlQGdtYWlsLmNvbSIsIjIwMjItMDgtMTIiLCIyMDIyLTA4LTEzIiwiIiwiIiwiU3RhdGUiLCLntYzpqJPjgarjgZciLCJMQVBBQ0siLCLntYzpqJPjgarjgZciLCIiLCIiLCJBV1MgR3JvdW5kIFN0YXRpb24iLCLntYzpqJPjgarjgZciLCJidXNpbmVzc19jb250ZW50IiwiaHR0cHM6Ly9jb2NvcmVwby5jb20iLCJ0ZXN0IiwiMTAiLCIxMDAwMDAiLCIyMDAwMDAiLCLjgrXjg7zjg5Djg7zjgqjjg7Pjgrjjg4vjgqI744Gd44Gu5LuWIiwidGVzdCIsImRlc2NyaXB0aW9uIiwi5Yuf6ZuG5LitIiwiIiwiIiwiIiwiIiwiIiwiIiwibm90ZSAxMjMi'
            ],
            'case' => self::CASE1
        ];
        //error file
        $case2 = [
            'project' => [
                'file_name' => 'test2',
                'file_data' => base64_encode('example')
            ],
            'case' => self::CASE2
        ];
        //max file name
        $case3 = [
            'project' => [
                'file_name' => $this->genRandomString(256),
                'file_data' => base64_encode('example')
            ],
            'case' => self::CASE3
        ];
        //max file data
        $case4 = [
            'project' => [
                'file_name' => $this->genRandomString(255),
                'file_data' => base64_encode($this->genRandomString(10000000)),
            ],
            'case' => self::CASE4
        ];
        return [
            [$case1],
            [$case2],
            [$case3],
            [$case4]
        ];
    }
}
