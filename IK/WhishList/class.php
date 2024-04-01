<?require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
session_start();
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SystemException;
use Bitrix\Main\Loader;
use Bitrix\Main\Type\Date;

use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

use \Bitrix\Main\Application;
use \Bitrix\Iblock\SectionTable;
use \Bitrix\Iblock\ElementTable;
use \Bitrix\Iblock\PropertyTable;
session_start();

Loader::includeModule('iblock');

class WhishListComponent extends CBitrixComponent implements Controllerable{

    public function configureActions(){
        // сбрасываем фильтры по-умолчанию
        return [
            'AddWhishList' => [
                'prefilters' => [],
                'postfilters' => []
            ],
            'RemoveWhishList' => [
                'prefilters' => [],
                'postfilters' => []
            ],
            'GetWhishListCount' => [
                'prefilters' => [],
                'postfilters' => []
            ]
        ];
    }

    public function executeComponent(){// подключение модулей (метод подключается автоматически)
        try{
            // Проверка подключения модулей
            $this->checkModules();
            // формируем arResult
            $this->getResult($this->form_id);
            // подключение шаблона компонента
            $this->includeComponentTemplate();
        }
        catch (SystemException $e){
            ShowError($e->getMessage());
        }
    }

    protected function checkModules(){// если модуль не подключен выводим сообщение в catch (метод подключается внутри класса try...catch)
        if (!Loader::includeModule('iblock')){
            throw new SystemException(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'));
        }
    }


    public function onPrepareComponentParams($arParams){//обработка $arParams (метод подключается автоматически)
        return $arParams;
    }

    protected function getResult(){ // подготовка массива $arResult (метод подключается внутри класса try...catch)
        // Формируем массив arResult

        // Передаем параметры в сессию, чтобы получить иметь доступ в ajax
        $_SESSION['arParams'] = $this->arParams;
    }

    public function AddWhishListAction(){
        $request = Application::getInstance()->getContext()->getRequest();
        // получаем файлы, post
        $post = $request->getPostList();
        $files = $request->getFileList()->toArray();
        // Получаем параметры компонента из сессии
        $this->arParams = $_SESSION['arParams'];
    
        Global $USER;
        $WhishList = new WhishList($USER);
        $WhishList->AddWhishList( $post['ProductID'] );

        return true;
    } 

    public function RemoveWhishListAction(){
        $request = Application::getInstance()->getContext()->getRequest();
        // получаем файлы, post
        $post = $request->getPostList();
        $files = $request->getFileList()->toArray();
        // Получаем параметры компонента из сессии
        $this->arParams = $_SESSION['arParams'];

        Global $USER;
        $WhishList = new WhishList($USER);
        $WhishList->RemoveWhishListItem( $post['ProductID'] );

        return true;
    }

    public function GetWhishListCountAction(){
        $request = Application::getInstance()->getContext()->getRequest();
        // получаем файлы, post
        $post = $request->getPostList();
        $files = $request->getFileList()->toArray();
        // Получаем параметры компонента из сессии
        $this->arParams = $_SESSION['arParams'];

        Global $USER;
        $WhishList = new WhishList($USER);
        return count($WhishList->GetWhishList());
    } 

}