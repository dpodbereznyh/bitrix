Во всех компонентах определяем время последнего изменения. Можно дату последнего кэширования $GLOBALS['lastModified']

<?

AddEventHandler('main', 'OnEpilog', 'CheckIfModifiedSince');

function CheckIfModifiedSince()
{
    $lastModified=$GLOBALS['lastModified'];

    if ($lastModified)
    {
        header("Cache-Control: public");
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $lastModified) . ' GMT');

        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $lastModified) {
            $GLOBALS['APPLICATION']->RestartBuffer();CHTTP::SetStatus('304 Not Modified');
            exit();
            
        }
    }
}

?>