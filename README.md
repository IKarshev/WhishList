# whishlist

Добавление товаров в избранное на 1C-Bitrix

## Установка

1. Подключить в `init.php` файл `whish_list.php`.
2. Скопировать `IK` в `/bitrix/components/` или в `/local/components/`.
3. Подключить Jquery.
4. Подключить `script.js` и `style.css` в header.

## Использование


<b>Узнать добавлен ли товар в избранное можно так:</b>

```
Global $USER;
$WhishList = new WhishList($USER);
$WhishListItemsArray = $WhishList->GetWhishList();

if( in_array($arResult['ID'], $WhishListItemsArray) ){
	$arResult['WhishList'] = true;
}else{
	$arResult['WhishList'] = false;
};
```


<b>Выводим кнопку:</b>

```
<a class="WhishList <?=($arResult['WhishList']) ? 'remove-list' : 'add-list' ?>" href="" data-ProductID="<?=$arResult['ID']?>"></a>
```