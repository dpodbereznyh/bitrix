<?

use Bitrix\Main\Mail\Event;


function messMail($arFields)
{
if($arFields['IBLOCK_ID'] != 38) return;

$res2 = CIBlockElement::GetList(
                array(),
                array('IBLOCK_ID' => 17,'ID'=>$arFields['PROPERTY_VALUES'][316]),
                false,
                false,
                array('ID','IBLOCK_ID',"DETAIL_PAGE_URL")
            );

$arr=$res2->GetNext();

$C_FIELDS=['STARS'=>$arFields['PROPERTY_VALUES'][319],
			'CATALOG'=>$arFields['PROPERTY_VALUES'][316],
			'MESSAGE'=>$arFields['PROPERTY_VALUES'][320],
            'NAME'=>$arFields['PROPERTY_VALUES'][321],
			'DETAIL_PAGE_URL'=>$arr['DETAIL_PAGE_URL'],
			'ID'=>$arFields['ID']];

Event::send(array(
    "EVENT_NAME" => "MESS_ADD",
    "LID" => "s1",
    "C_FIELDS" => $C_FIELDS
));

}

AddEventHandler("iblock", "OnAfterIBlockElementAdd", "messMail");

?>