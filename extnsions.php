<?php

CJSCore::RegisterExt(
	"fullpage_js", 
	array(
		"js" => SITE_TEMPLATE_PATH . "/assets/vendor/fullPage.js/fullpage.min.js",
		"css" => SITE_TEMPLATE_PATH . "/assets/vendor/fullPage.js/fullpage.min.css",
		"skip_core" => true,
	)
);
CJSCore::RegisterExt(
	"swiper", 
	array(
		"js" => SITE_TEMPLATE_PATH . "/assets/vendor/swiper/swiper-bundle.min.js",
		"css" => SITE_TEMPLATE_PATH . "/assets/vendor/swiper/swiper-bundle.min.css",
		"skip_core" => true,
	)
);
// CJSCore::RegisterExt(
// 	"fm_utils", 
// 	array(
// 		"js" => SITE_TEMPLATE_PATH . "/assets/js/utils.js",
// 		"skip_core" => false,
// 	)
// );