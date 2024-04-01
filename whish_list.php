<?
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Entity;
use Bitrix\Main\Application;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;

class WhishListTable extends Entity\DataManager{
    public static function getTableName()
    {
        return 'WhishList';
    }
    public static function getMap() 
    {
        return [
            new IntegerField('ID', [
                'primary'=>true,
                'autocomplete'=>true
            ]),
            new StringField('TimeStampCreated'),
            new IntegerField('UserID'),
            new IntegerField('ProductID'),	
        ];
    }
}

Class WhishList{
    public $USER;

    public function __construct( $USER ){

        $this->isAuthorized = $USER->IsAuthorized();
        if( $this->isAuthorized ){
            $this->UserID = $USER->GetID();
        };

        $connection = Application::getInstance()->getConnection();
            if(!$connection->isTableExists(WhishListTable::getTableName()))
                WhishListTable::getEntity()->createDbTable();
    }

    /**
     * Добавляет товар в избранное
     * @param int $ProductID — ID-товара
     */
    public function AddWhishList(int $ProductID ){
        
        if( $this->isAuthorized ){
            $SearchItem = array_column(WhishListTable::getList([
                'select' => ['ID'],
                'filter' => [
                    '=UserID' => $this->UserID,
                    '=ProductID' => $ProductID,
                ],
                ])->fetchAll(), 'ID');
    
            if( empty($SearchItem) ){
                WhishListTable::add([
                    'TimeStampCreated'=> time(),
                    'UserID' => $this->UserID,
                    'ProductID'=> $ProductID,
                ]);
            };
        }else{
            $CurrentCookie = (isset($_COOKIE['WHISH_LIST'])) ? unserialize($_COOKIE['WHISH_LIST']) : array();
            if( !isset($CurrentCookie[$ProductID]) ) $CurrentCookie[] = $ProductID;
            $time = time()+(30*24*60*60);
            setcookie('WHISH_LIST', serialize($CurrentCookie), $time, "/");
        };

    }

    /**
     * Получаем id избранных товаров
     */
    public function GetWhishList(){

        if( $this->isAuthorized ){
            $result = array_column(WhishListTable::getList([
                'select' => ['ProductID'],
                'filter' => ['=UserID' => $this->UserID],
                ])->fetchAll(), 'ProductID');
    
            return $result;
        }else{
            return unserialize($_COOKIE['WHISH_LIST']);
        };

    }

    /**
     * Удаляет товар из избранного
     * @param int $ProductID — ID-товара
     */
    public function RemoveWhishListItem( int $ProductID ){

        if( $this->isAuthorized ){
            $ItemID = array_column(WhishListTable::getList([
                'select' => ['ID'],
                'filter' => [
                    '=UserID' => $this->UserID,
                    '=ProductID' => $ProductID,
                ],
                ])->fetchAll(), 'ID');
    
            foreach ($ItemID as $arkey => $arItem) {
                WhishListTable::delete($arItem);
            };
        }else{
            if( !isset($_COOKIE['WHISH_LIST']) ) return;

            $CurrentCookie = unserialize($_COOKIE['WHISH_LIST']);

            foreach ($CurrentCookie as $arItem) {
                if( $arItem != $ProductID ){
                    $CookieData[] = $arItem;
                };
            };

            $time = time()+(30*24*60*60);
            setcookie('WHISH_LIST', serialize($CookieData), $time, "/");
        };

    }
}
?>