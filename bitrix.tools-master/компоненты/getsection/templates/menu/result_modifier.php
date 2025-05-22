<?

if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

?>
<script>
console.log(<?=json_encode($arParams, JSON_UNESCAPED_UNICODE)?>);
console.log(<?=json_encode($arResult, JSON_UNESCAPED_UNICODE)?>);
</script>
<?