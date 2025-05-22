<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\UI\PageNavigation;
use Bitrix\Main\Type\DateTime;

class BlogPostsComponent extends CBitrixComponent
{
  
    protected function getProduct($postId)
    {
        $arSelect = array("ID", "IBLOCK_ID", "NAME", "PREVIEW_PICTURE", "PROPERTY_BLOG_POST_ID","DETAIL_PAGE_URL");
        $arFilter = array("PROPERTY_BLOG_POST_ID" => $postId);
        if ($res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect)) {
            while ($ob = $res->GetNext()) {
                $product = $ob;
            }
            return $product;
        }
        return [];
    }

    protected function getTotalCount()
    {
        global $DB;
        $sql = "SELECT COUNT(DISTINCT c.ID) as CNT FROM b_blog_comment c";
        $result = $DB->Query($sql);
        $resultCount = $result->fetch();

        return $resultCount['CNT'];
    }

    protected function getReviews($limit, $offset)
    {
        global $DB;
        $sql = "
            SELECT p.ID as POST_ID, p.TITLE as POST_TITLE, p.DETAIL_TEXT as POST_DETAIL_TEXT, c.ID as COMMENT_ID, c.AUTHOR_NAME as COMMENT_AUTHOR_NAME, c.DATE_CREATE as COMMENT_DATE, c.POST_TEXT as COMMENT_TEXT, uc.UF_ASPRO_COM_RATING as COMMENT_RATING
            FROM b_blog_post p
            INNER JOIN b_blog_comment c ON p.ID = c.POST_ID
            LEFT JOIN b_uts_blog_comment uc ON c.ID = uc.VALUE_ID
            ORDER BY c.DATE_CREATE DESC
            LIMIT $limit OFFSET $offset
        ";
        $result = $DB->Query($sql);

        $posts = [];
        while ($row = $result->fetch()) {
            $posts[$row['COMMENT_ID']]['POST_ID'] = $row['POST_ID'];
            $posts[$row['COMMENT_ID']]['TITLE'] = $row['POST_TITLE'];
            $posts[$row['COMMENT_ID']]['COMMENT_ID'] = $row['COMMENT_ID'];

            $date = new DateTime($row['COMMENT_DATE'], "Y-m-d H:i:s");
            $formattedDate = FormatDate('j F Y', $date->getTimestamp());
            $posts[$row['COMMENT_ID']]['COMMENT_DATE'] = $formattedDate;

            $posts[$row['COMMENT_ID']]['COMMENT_AUTHOR_NAME'] = $row['COMMENT_AUTHOR_NAME'];
            $posts[$row['COMMENT_ID']]['COMMENT_TEXT'] = $row['COMMENT_TEXT'];
            if ($row['COMMENT_RATING'] == null) {
                $row['COMMENT_RATING'] = '0';
            }
            $posts[$row['COMMENT_ID']]['COMMENT_RATING'] = $row['COMMENT_RATING'];

            $prod=$this->getProduct($row['POST_ID']);
            $posts[$row['COMMENT_ID']]['DETAIL_LINK'] = $prod["~DETAIL_PAGE_URL"];

            $previewPicture = $prod["PREVIEW_PICTURE"];
            if (!empty($previewPicture)) {
                $previewPictureImg = CFile::GetPath($previewPicture);
            } else {
                $previewPictureImg = '/local/templates/aspro-lite/images/svg/noimage_product.svg';
            }
            $posts[$row['COMMENT_ID']]['PREVIEW_PICTURE'] = $previewPictureImg;
        }

        return $posts;
    }

    public function executeComponent()
    {
        if (!Loader::includeModule("blog")) {
            $this->AbortResultCache();
            ShowError("blog module is not installed");
            return;
        }

        $nav = new PageNavigation("page");
        $nav->allowAllRecords(true)
            ->setPageSize($this->arParams['PAGE_SIZE'])
            ->initFromUri();

        $totalCount = $this->getTotalCount();
        $nav->setRecordCount($totalCount);
        $this->arResult['NAV'] = $nav;

        $cacheId = $this->GetCacheID($nav->getCurrentPage());

        if ($this->StartResultCache($this->arParams["CACHE_TIME"], $cacheId)) {
            $this->arResult['ITEMS'] = $this->getReviews($nav->getLimit(), $nav->getOffset());
            $this->SetResultCacheKeys(["NAV"]);
            $this->includeComponentTemplate();
        }
    }
}
