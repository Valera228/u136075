<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!CModule::IncludeModule("socialnetwork"))
{
	ShowError(GetMessage("SONET_MODULE_NOT_INSTALL"));
	return;
}

CPageOption::SetOptionString("main", "nav_page_in_session", "N");
CSocNetTools::InitGlobalExtranetArrays();

if ($arParams["USE_KEYWORDS"] != "N")
{
	$arParams["USE_KEYWORDS"] = "Y";
}

$arParams["USER_ID"] = IntVal($arParams["USER_ID"]);
if ($arParams["USER_ID"] <= 0)
{
	$arParams["USER_ID"] = IntVal($GLOBALS["USER"]->GetID());
}

$arParams["PAGE"] = Trim($arParams["PAGE"]);
if (
	$arParams["PAGE"] != "group_request_group_search" 
	&& $arParams["PAGE"] != "user_groups" 
	&& $arParams["PAGE"] != "groups_list" 
	&& $arParams["PAGE"] != "groups_subject"
)
{
	$arParams["PAGE"] = "user_groups";
}

if (intval($arParams["THUMBNAIL_SIZE"]) <= 0)
{
	$arParams["THUMBNAIL_SIZE"] = 48;
}

$user4Groups = $arParams["USER_ID"];
$user2Request = 0;
if ($arParams["PAGE"] == "group_request_group_search")
{
	$user4Groups = IntVal($GLOBALS["USER"]->GetID());
	$user2Request = $arParams["USER_ID"];
}

if ($arParams["PAGE"] == "groups_list")
{
	if (array_key_exists("filter_name", $_REQUEST) && strlen($_REQUEST["filter_name"]) > 0)
	{
		$arResult["filter_name"] = $_REQUEST["filter_name"];
		$arParams["CACHE_TIME"] = 0;
	}

	if (array_key_exists("filter_my", $_REQUEST) && $_REQUEST["filter_my"] == "Y")
		$arResult["filter_my"] = $_REQUEST["filter_my"];

	if (array_key_exists("filter_subject_id", $_REQUEST) && intval($_REQUEST["filter_subject_id"]) > 0)
		$arResult["filter_subject_id"] = $_REQUEST["filter_subject_id"];

	if (array_key_exists("filter_archive", $_REQUEST) && $_REQUEST["filter_archive"] == "Y")
		$arResult["filter_archive"] = $_REQUEST["filter_archive"];

	if (intval($arParams["SUBJECT_ID"]) == -1)
		$arResult["filter_archive"] = "Y";

	if (array_key_exists("filter_extranet", $_REQUEST) && strlen($_REQUEST["filter_extranet"]) > 0)
		$arResult["filter_extranet"] = $_REQUEST["filter_extranet"];

	if (array_key_exists("filter_tags", $_REQUEST) && strlen($_REQUEST["filter_tags"]) > 0)
		$arResult["filter_tags"] = $_REQUEST["filter_tags"];

	if (array_key_exists("tags", $_REQUEST) && strlen($_REQUEST["tags"]) > 0)
	{
		$arResult["~tags"] = $_REQUEST["tags"];
		$arResult["tags"] = htmlspecialcharsbx($arResult["~tags"]);
	}
}

if ($arParams["PAGE"] == "groups_subject" && intval($arParams["SUBJECT_ID"]) > 0)
	$arResult["filter_subject_id"] = intval($arParams["SUBJECT_ID"]);

$arResult["WORKGROUPS_PATH"] = COption::GetOptionString("socialnetwork", "workgroups_list_page", false, SITE_ID);

$arParams["SET_NAV_CHAIN"] = ($arParams["SET_NAV_CHAIN"] == "N" ? "N" : "Y");

if(strLen($arParams["USER_VAR"])<=0)
	$arParams["USER_VAR"] = "user_id";
if(strLen($arParams["GROUP_VAR"])<=0)
	$arParams["GROUP_VAR"] = "group_id";
if(strLen($arParams["PAGE_VAR"])<=0)
	$arParams["PAGE_VAR"] = "page";

$arParams["PATH_TO_USER"] = trim($arParams["PATH_TO_USER"]);
if(strlen($arParams["PATH_TO_USER"])<=0)
	$arParams["PATH_TO_USER"] = htmlspecialcharsbx($APPLICATION->GetCurPage()."?".$arParams["PAGE_VAR"]."=user&".$arParams["USER_VAR"]."=#user_id#");

$arParams["PATH_TO_GROUP"] = trim($arParams["PATH_TO_GROUP"]);
if (strlen($arParams["PATH_TO_GROUP"]) <= 0)
	$arParams["PATH_TO_GROUP"] = htmlspecialcharsbx($APPLICATION->GetCurPage()."?".$arParams["PAGE_VAR"]."=group&".$arParams["GROUP_VAR"]."=#group_id#");

$arParams["PATH_TO_GROUP_EDIT"] = trim($arParams["PATH_TO_GROUP_EDIT"]);
if (strlen($arParams["PATH_TO_GROUP_EDIT"]) <= 0)
	$arParams["PATH_TO_GROUP_EDIT"] = htmlspecialcharsbx($APPLICATION->GetCurPage()."?".$arParams["PAGE_VAR"]."=group_edit&".$arParams["GROUP_VAR"]."=#group_id#");

$arParams["PATH_TO_GROUP_CREATE"] = trim($arParams["PATH_TO_GROUP_CREATE"]);
if (strlen($arParams["PATH_TO_GROUP_CREATE"]) <= 0)
	$arParams["PATH_TO_GROUP_CREATE"] = htmlspecialcharsbx($APPLICATION->GetCurPage()."?".$arParams["PAGE_VAR"]."=group_create&".$arParams["USER_VAR"]."=#user_id#");

$arParams["PATH_TO_GROUP_SEARCH"] = trim($arParams["PATH_TO_GROUP_SEARCH"]);
if (strlen($arParams["PATH_TO_GROUP_SEARCH"]) <= 0)
	$arParams["PATH_TO_GROUP_SEARCH"] = htmlspecialcharsbx($APPLICATION->GetCurPage()."?".$arParams["PAGE_VAR"]."=group_search");

$arParams["PATH_TO_GROUP_REQUEST_USER"] = trim($arParams["PATH_TO_GROUP_REQUEST_USER"]);
if (strlen($arParams["PATH_TO_GROUP_REQUEST_USER"]) <= 0)
	$arParams["PATH_TO_GROUP_REQUEST_USER"] = htmlspecialcharsbx($APPLICATION->GetCurPage()."?".$arParams["PAGE_VAR"]."=group_request_user&".$arParams["USER_VAR"]."=#user_id#&".$arParams["GROUP_VAR"]."=#group_id#");

$arParams["PATH_TO_LOG"] = trim($arParams["PATH_TO_LOG"]);
if (strlen($arParams["PATH_TO_LOG"]) <= 0)
	$arParams["PATH_TO_LOG"] = htmlspecialcharsbx($APPLICATION->GetCurPage()."?".$arParams["PAGE_VAR"]."=log");

$arParams["ITEMS_COUNT"] = IntVal($arParams["ITEMS_COUNT"]);
if ($arParams["ITEMS_COUNT"] <= 0)
	$arParams["ITEMS_COUNT"] = 30;

$arParams["COLUMNS_COUNT"] = IntVal($arParams["COLUMNS_COUNT"]);
if ($arParams["COLUMNS_COUNT"] <= 0)
	$arParams["COLUMNS_COUNT"] = 3;

$arParams["DATE_TIME_FORMAT"] = Trim($arParams["DATE_TIME_FORMAT"]);
$arParams["DATE_TIME_FORMAT"] = ((StrLen($arParams["DATE_TIME_FORMAT"]) <= 0) ? $DB->DateFormatToPHP(CSite::GetDateFormat("FULL")) : $arParams["DATE_TIME_FORMAT"]);

/***************** CACHE ****************************************/
if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 3600;
if ($arParams["CACHE_TYPE"] == "Y" || ($arParams["CACHE_TYPE"] == "A" && COption::GetOptionString("main", "component_cache_on", "Y") == "Y"))
	$arParams["CACHE_TIME"] = intval($arParams["CACHE_TIME"]);
else
	$arParams["CACHE_TIME"] = 0;

$groupCache = new CPHPCache;
$cachePath = str_replace(array(":", "//"), "/", "/".SITE_ID."/".$componentName);
/********************************************************************/

$arResult["FatalError"] = "";

if (
	(
		in_array($arParams["PAGE"], array("group_request_group_search", "user_groups"))
		|| $arResult["filter_my"] == "Y"
	)
	&& (
		$user4Groups <= 0
	)
)
	$arResult["FatalError"] = GetMessage("SONET_C36_NO_USER_ID").". ";

if (StrLen($arResult["FatalError"]) <= 0)
{
	if ($arParams["PAGE"] == "group_request_group_search")
	{
		if ($user2Request <= 0)
			$arResult["FatalError"] = GetMessage("SONET_C36_NO_USER_ID").". ";
		elseif ($user2Request == $user4Groups)
			$arResult["FatalError"] = GetMessage("SONET_C36_SELF").". ";
	}
}

if (strlen($arResult["FatalError"]) <= 0)
{
	if ($arParams["PAGE"] == "groups_list")
	{
		$arResult["Subjects"] = array();
		$dbSubjects = CSocNetGroupSubject::GetList(
			array("SORT" => "ASC", "NAME" => "ASC"),
			array("SITE_ID" => SITE_ID),
			false,
			false,
			array("ID", "NAME")
		);
		while ($arSubject = $dbSubjects->GetNext())
			$arResult["Subjects"][$arSubject["ID"]] = $arSubject["NAME"];
	}
	elseif ($arParams["PAGE"] == "groups_subject" && intval($arResult["filter_subject_id"]) > 0)
	{

		$arResult["Subjects"] = array();
		$dbSubjects = CSocNetGroupSubject::GetList(
			array("SORT" => "ASC", "NAME" => "ASC"),
			array("SITE_ID" => SITE_ID, "ID" => intval($arResult["filter_subject_id"])),
			false,
			false,
			array("ID", "NAME")
		);
		if ($arSubject = $dbSubjects->GetNext())
			$arResult["Subject"] = $arSubject;
	}

}

if (
	StrLen($arResult["FatalError"]) <= 0
	&& intval($user4Groups) > 0
)
{
	$dbUser = CUser::GetByID($user4Groups);
	$arResult["User"] = $dbUser->GetNext();

	if (!is_array($arResult["User"]))
		$arResult["FatalError"] = GetMessage("SONET_P_USER_NO_USER").". ";
	if (CModule::IncludeModule('extranet') && !CExtranet::IsProfileViewable($arResult["User"]))
		return false;
}

if (StrLen($arResult["FatalError"]) <= 0)
{
	$arResult["UserRequest"] = false;
	if ($user2Request > 0)
	{
		$dbUser = CUser::GetByID($user2Request);
		$arResult["UserRequest"] = $dbUser->GetNext();

		if (!is_array($arResult["UserRequest"]))
			$arResult["FatalError"] = GetMessage("SONET_P_USER_NO_USER").". ";
		if (CModule::IncludeModule('extranet') && !CExtranet::IsProfileViewable($arResult["UserRequest"]))
			return false;
	}
}

if (StrLen($arResult["FatalError"]) <= 0)
{
	$arResult["CurrentUserPerms"] = CSocNetUserPerms::InitUserPerms($GLOBALS["USER"]->GetID(), $arResult["User"]["ID"], CSocNetUser::IsCurrentUserModuleAdmin());
	$arResult["ALLOW_CREATE_GROUP"] = (CSocNetUser::IsCurrentUserModuleAdmin() || $GLOBALS["APPLICATION"]->GetGroupRight("socialnetwork", false, "Y", "Y", array(SITE_ID, false)) >= "K");

	$arCacheKeys = array();
	$arNavigation = array();

	foreach ($arResult as $key => $value)
		if (substr($key, 0, 7) == "filter_")
			$arCacheKeys[] = $key."_".$value;

	$arNavParams = array('nPageSize' => $arParams['ITEMS_COUNT']);
	$arNavigation = CDBResult::GetNavParams($arNavParams);
	$arCacheKeys = array_merge($arCacheKeys, $arNavigation);

	if (array_key_exists("tags", $arResult) && strlen($arResult["tags"]) > 0)
		$arCacheKeys[] = $arResult["tags"];

	$cacheId = "socnet_user_groups_".SITE_ID.'_'.$arParams["PAGE"]."_".$USER->GetID()."_"."_".$arResult["User"]["ID"]."_".md5(serialize($arCacheKeys))."_".intval(CSocNetUser::IsCurrentUserModuleAdmin());
	if ($arParams["CACHE_TIME"] > 0 && $groupCache->InitCache($arParams["CACHE_TIME"], $cacheId, $cachePath))
	{
		$vars = $groupCache->GetVars();
		$arCacheResult = $vars["arCacheResult"];
	}
	else
	{
		if ($arParams["CACHE_TIME"] > 0 && defined("BX_COMP_MANAGED_CACHE"))
		{
			$GLOBALS["CACHE_MANAGER"]->StartTagCache($cachePath);
			$GLOBALS["CACHE_MANAGER"]->RegisterTag("sonet_user2group_U".$GLOBALS["USER"]->GetID());
			$GLOBALS["CACHE_MANAGER"]->RegisterTag("sonet_group");
		}

		$arGroupID = Array();

		if (
			in_array($arParams["PAGE"], array("groups_list", "groups_subject")) 
			|| $arResult["CurrentUserPerms"] && $arResult["CurrentUserPerms"]["Operations"]["viewgroups"]
		)
		{
			$arNavParams = array("nPageSize" => $arParams["ITEMS_COUNT"], "bDescPageNumbering" => false);

			$arCacheResult["Urls"]["GroupsAdd"] = CComponentEngine::MakePathFromTemplate($arParams["PATH_TO_GROUP_CREATE"], array("user_id" => $arResult["User"]["ID"]));
			$arCacheResult["Urls"]["LogGroups"] = CComponentEngine::MakePathFromTemplate($arParams["PATH_TO_LOG"], array());
			$arCacheResult["Urls"]["LogGroups"] .= ((StrPos($arCacheResult["Urls"]["LogGroups"], "?") !== false) ? "&" : "?")."flt_entity_type=".SONET_ENTITY_GROUP;
			$arCacheResult["CanViewLog"] = ($arResult["User"]["ID"] == $GLOBALS["USER"]->GetID());

			$arCacheResult["Groups"] = false;

			$arGroupFilter = array(
				"=ACTIVE" => "Y"
			);

			if (strlen($arResult["filter_name"]) > 0)
			{
				$arGroupFilter[] = array(
					'LOGIC' => 'OR',
					'%NAME' => $arResult["filter_name"],
					'%DESCRIPTION' => $arResult["filter_name"]
				);
				$bORM = true;
			}
			
			if ($bORM)
			{
				$arGroupFilter["=WorkgroupSite:GROUP.SITE_ID"] = SITE_ID;
			}
			else
			{
				$arGroupFilter["SITE_ID"] = SITE_ID;
			}

			if (!CSocNetUser::IsCurrentUserModuleAdmin())
			{
				if ($bORM)
				{
					$arAvailableGroupID = array();
					$dbGroups = CSocNetUserToGroup::GetList(
						array("ID" => "ASC"),
						array(
							"CHECK_PERMISSIONS" => $GLOBALS["USER"]->GetID()
						),
						false,
						false,
						array("GROUP_ID")
					);

					while ($arGroups = $dbGroups->GetNext())
					{
						if (!in_array($arGroups["GROUP_ID"], $arAvailableGroupID))
						{
							$arAvailableGroupID[] = $arGroups["GROUP_ID"];
						}
					}

					if (empty($arAvailableGroupID))
					{
						$bNoMyGroups = true;
					}
					else
					{
						$arGroupFilter["ID"] = $arAvailableGroupID;
					}
				}
				else
				{
					$arGroupFilter["CHECK_PERMISSIONS"] = $GLOBALS["USER"]->GetID();
				}
			}

			if (
				COption::GetOptionString("socialnetwork", "work_with_closed_groups", "N") != "Y" 
				&& $arResult["filter_tags"] != "Y"
			)
			{
				$arGroupFilter["=CLOSED"] = ($arResult["filter_archive"] == "Y" ? "Y" : "N");
			}

			if (intval($arResult["filter_subject_id"]) > 0)
			{
				if ($bORM)
				{
					$arGroupFilter["WORKGROUP_SUBJECT.ID"] = intval($arResult["filter_subject_id"]);
				}
				else
				{
					$arGroupFilter["SUBJECT_ID"] = intval($arResult["filter_subject_id"]);
				}
			}

			// get my groups for extranet
			if (
				CModule::IncludeModule("extranet") 
				&& CExtranet::IsExtranetSite()
			)
			{
				$arCacheResult["bExtranet"] = true;
				if (!$GLOBALS["USER"]->IsAdmin() && !CSocNetUser::IsCurrentUserModuleAdmin())
				{
					$arGroupFilterMy = array(
						"USER_ID" => $GLOBALS["USER"]->GetID(),
						"<=ROLE" => SONET_ROLES_USER,
						"GROUP_SITE_ID" => SITE_ID,
						"GROUP_ACTIVE" => "Y"
					);

					$dbGroups = CSocNetUserToGroup::GetList(
						array("GROUP_NAME" => "ASC"),
						$arGroupFilterMy,
						false,
						false,
						array("GROUP_ID")
					);

					$arMyGroups = array();
					while ($arGroups = $dbGroups->GetNext())
					{
						$arMyGroups[] = $arGroups["GROUP_ID"];
					}

					if (count($arMyGroups) <= 0)
					{
						$bNoMyGroups = true;
					}
					else
					{
						$arGroupFilter["ID"] = (!empty($arGroupFilter["ID"]) ? array_intersect($arGroupFilter["ID"], $arMyGroups) : $arMyGroups);
					}
				}
			}
			else
			{
				// not extranet
				if ($arResult["filter_my"] == "Y" || $arParams["PAGE"] == "user_groups")
				{
					$arUserGroupFilter["USER_ID"] = $arResult["User"]["ID"];
					$arUserGroupFilter["<=ROLE"] = SONET_ROLES_USER;
				}

				if (CModule::IncludeModule("extranet") && !CExtranet::IsExtranetSite() && $arResult["filter_extranet"] == "Y")
				{
					$arUserGroupFilter["GROUP_SITE_ID"] = CExtranet::GetExtranetSiteID();
					$arUserGroupFilter["<=ROLE"] = SONET_ROLES_USER;
				}

				if (
					!$arResult["CurrentUserPerms"]["IsCurrentUser"] 
					&& !CSocNetUser::IsCurrentUserModuleAdmin()
				)
				{
					$arGroupFilter["=VISIBLE"] = "Y";
				}
			}
			if (
				$arParams["USE_KEYWORDS"] == "Y"
				&& strlen($arResult["~tags"]) > 0 
				&& CModule::IncludeModule("search")
			)
			{
				$arFilter = array(
					"SITE_ID" => SITE_ID,
					"QUERY" => "",
					array(
						"=MODULE_ID" => "socialnetwork",
						"ITEM_ID" => "G%",
					),
					"CHECK_DATES" => "Y",
					"TAGS" => $arResult["~tags"]
				);
				$aSort = array("DATE_CHANGE" => "DESC", "CUSTOM_RANK" => "DESC", "RANK" => "DESC");

				$obSearch = new CSearch();
				$obSearch->Search($arFilter);
				if ($obSearch->errorno == 0)
				{
					$arTagGroups = array();
					while ($arSearch = $obSearch->Fetch())
					{
						if (intval($arSearch["PARAM2"]) > 0)
						{
							$arTagGroups[] = $arSearch["PARAM2"];
						}
					}

					if (
						!empty($arTagGroups)
						&& !$bNoMyGroups
					)
					{
						$arGroupFilter["ID"] = (!empty($arGroupFilter["ID"]) ? array_intersect($arGroupFilter["ID"], $arTagGroups) : $arTagGroups);
					}
				}
			}

			if (!$bNoMyGroups)
			{
				if (!empty($arUserGroupFilter))
				{
					$dbUserGroups = CSocNetUserToGroup::GetList(
						array("GROUP_NAME" => "ASC"),
						$arUserGroupFilter,
						false,
						false,
						array("GROUP_ID")
					);
					if ($dbUserGroups)
					{
						while ($arUserGroups = $dbUserGroups->GetNext())
						{
							if (!isset($arGroupFilter["ID"]))
							{
								$arGroupFilter["ID"] = array();
							}

							if (!in_array($arUserGroups["GROUP_ID"], $arGroupFilter["ID"]))
							{
								$arGroupFilter["ID"][] = $arUserGroups["GROUP_ID"];
							}
						}
					}
				}
			}
		}

		if (($arResult["filter_my"] == "Y"
			|| ($arParams["PAGE"] == "user_groups" && !$GLOBALS["USER"]->IsAdmin() && !CSocNetUser::IsCurrentUserModuleAdmin())
			|| ($arParams["PAGE"] == "user_groups" && ($GLOBALS["USER"]->IsAdmin() || CSocNetUser::IsCurrentUserModuleAdmin()) && (IntVal($GLOBALS["USER"]->GetID()) != $arParams["USER_ID"])) 
			|| $arResult["filter_extranet"] == "Y") 
			&& (
				!array_key_exists("ID", $arGroupFilter) 
				|| !is_array($arGroupFilter["ID"]) 
				|| count($arGroupFilter["ID"]) <= 0
			)
		)
		{
			$bNoMyGroups = true;
		}

		if (!$bNoMyGroups)
		{
			$arCacheResult["Groups"] = array();
			$arCacheResult["Groups"]["List"] = false;

			if ($bORM)
			{
				$arFilterTmp = array();
				$dbGroupTmp = \Bitrix\Socialnetwork\WorkgroupTable::getList(array(
					'order' => array(
						'ID' => 'ASC'
					),
					'filter' => $arGroupFilter,
					'group' => array("ID"),
					'select' => array("ID"),
					'data_doubling' => false
				));

				while ($arGroupTmp = $dbGroupTmp->Fetch())
				{
					$arFilterTmp[] = $arGroupTmp["ID"];
				}

				if (!empty($arFilterTmp))
				{
					$dbGroup = CSocNetGroup::GetList(
						array("DATE_ACTIVITY" => "DESC"),
						array("ID" => $arFilterTmp),
						false,
						$arNavParams,
						array("ID", "NAME", "DESCRIPTION", "IMAGE_ID", "VISIBLE", "OWNER_ID", "INITIATE_PERMS", "OPENED")
					);
				}
			}
			else
			{
				$dbGroup = CSocNetGroup::GetList(
					array("DATE_ACTIVITY" => "DESC"),
					$arGroupFilter,
					false,
					$arNavParams,
					array("ID", "NAME", "DESCRIPTION", "IMAGE_ID", "VISIBLE", "OWNER_ID", "INITIATE_PERMS", "OPENED")
				);
			}

			if ($dbGroup)
			{
				while ($arGroup = $dbGroup->GetNext())
				{
					if ($arCacheResult["Groups"]["List"] == false)
					{
						$arCacheResult["Groups"]["List"] = array();
					}

					$pu = CComponentEngine::MakePathFromTemplate($arParams["PATH_TO_GROUP"], array("group_id" => $arGroup["ID"]));

					if (intval($arGroup["IMAGE_ID"]) <= 0)
					{
						$arGroup["IMAGE_ID"] = COption::GetOptionInt("socialnetwork", "default_group_picture", false, SITE_ID);
					}

					$arImageResized = false;
					$imageFile = CFile::GetFileArray($arGroup["IMAGE_ID"]);
					if ($imageFile !== false)
					{
						$arImageResized = CFile::ResizeImageGet(
							$imageFile,
							array("width" => $arParams["THUMBNAIL_SIZE"], "height" => $arParams["THUMBNAIL_SIZE"]),
							BX_RESIZE_IMAGE_EXACT
						);
					}

					$arImage = CSocNetTools::InitImage($arGroup["IMAGE_ID"], 150, "/bitrix/images/socialnetwork/nopic_group_150.gif", 150, $pu, true);

					if ($arParams["PAGE"] == "group_request_group_search")
						$arCurrentUserPerms4Group = CSocNetUserToGroup::InitUserPerms($arResult["User"]["ID"], array("ID" => $arGroup["ID"], "OWNER_ID" => $arGroup["OWNER_ID"], "INITIATE_PERMS" => $arGroup["INITIATE_PERMS"], "VISIBLE" => $arGroup["VISIBLE"], "OPENED" => $arGroup["OPENED"]), CSocNetUser::IsCurrentUserModuleAdmin());

					$arCacheResult["Groups"]["List"][] = array(
						"GROUP_ID" => $arGroup["ID"],
						"GROUP_NAME" => $arGroup["NAME"],
						"GROUP_DESCRIPTION" => (strlen($arGroup["DESCRIPTION"]) > 50 ? substr($arGroup["DESCRIPTION"], 0, 50)."..." : $arGroup["DESCRIPTION"]),
						"GROUP_PHOTO" => $arGroup["IMAGE_ID"],
						"GROUP_PHOTO_FILE" => $arImage["FILE"],
						"GROUP_PHOTO_IMG" => $arImage["IMG"],
						"GROUP_PHOTO_RESIZED" => $arImageResized,
						"GROUP_URL" => $pu,
						"GROUP_REQUEST_USER_URL" => CComponentEngine::MakePathFromTemplate($arParams["PATH_TO_GROUP_REQUEST_USER"], array("group_id" => $arGroup["ID"], "user_id" => $arResult["UserRequest"]["ID"])),
						"CAN_INVITE2GROUP" => (($arParams["PAGE"] != "user_groups") ? $arCurrentUserPerms4Group && $arCurrentUserPerms4Group["UserCanInitiate"] : false),
						"FULL" => array(
							"DATE_CREATE_FORMATTED" => date($arParams["DATE_TIME_FORMAT"], MakeTimeStamp($arGroup["DATE_CREATE"], CSite::GetDateFormat("FULL"))),
							"DATE_UPDATE_FORMATTED" => date($arParams["DATE_TIME_FORMAT"], MakeTimeStamp($arGroup["DATE_UPDATE"], CSite::GetDateFormat("FULL"))),
							"DATE_ACTIVITY_FORMATTED" => date($arParams["DATE_TIME_FORMAT"], MakeTimeStamp($arGroup["DATE_ACTIVITY"], CSite::GetDateFormat("FULL")))
						)
					);

					$arGroupID[] = $arGroup["ID"];
				}

				if (
					CModule::IncludeModule("extranet") 
					&& !CExtranet::IsExtranetSite()
				)
				{
					$arExtranetGroupID = array();
					$dbGroupTmp = CSocNetGroup::GetList(
						array(),
						array(
							"ID" => $arGroupID,
							"SITE_ID" => CExtranet::GetExtranetSiteID()
						),
						false,
						false,
						array("ID")
					);
					while($arGroupTmp = $dbGroupTmp->Fetch())
					{
						$arExtranetGroupID[] = $arGroupTmp["ID"];
					}

					if (
						count($arExtranetGroupID) > 0 
						&& is_array($arCacheResult["Groups"]["List"])
					)
					{
						foreach($arCacheResult["Groups"]["List"] as $key => $arGroupTmp)
						{
							$arCacheResult["Groups"]["List"][$key]["IS_EXTRANET"] = (in_array($arGroupTmp["GROUP_ID"], $arExtranetGroupID) ? "Y" : "N");
						}
					}
				}

				$arCacheResult["NAV_STRING"] = $dbGroup->GetPageNavStringEx($navComponentObject, GetMessage("SONET_C36_NAV"), "", false);
			}
		}
	
		if ($arParams["CACHE_TIME"] > 0)
		{
			if (defined("BX_COMP_MANAGED_CACHE"))
				$GLOBALS["CACHE_MANAGER"]->EndTagCache();

			$groupCache->StartDataCache($arParams["CACHE_TIME"], $cacheId, $cachePath);
			$groupCache->EndDataCache(array("arCacheResult" => $arCacheResult));
		}
	}

	$arResult = array_merge($arResult, $arCacheResult);

	if ($arParams["SET_TITLE"] == "Y" || $arParams["SET_NAV_CHAIN"] != "N")
	{
		if (strlen($arParams["NAME_TEMPLATE"]) <= 0)
			$arParams["NAME_TEMPLATE"] = CSite::GetNameFormat();

		$arParams["TITLE_NAME_TEMPLATE"] = str_replace(
			array("#NOBR#", "#/NOBR#"),
			array("", ""),
			$arParams["NAME_TEMPLATE"]
		);
		$bUseLogin = $arParams['SHOW_LOGIN'] != "N" ? true : false;

		if ($arParams["PAGE"] == "group_request_group_search")
		{
			$arTmpUser = array(
				"NAME" => $arResult["UserRequest"]["~NAME"],
				"LAST_NAME" => $arResult["UserRequest"]["~LAST_NAME"],
				"SECOND_NAME" => $arResult["UserRequest"]["~SECOND_NAME"],
				"LOGIN" => $arResult["UserRequest"]["~LOGIN"],
			);
			$strTitleFormatted = CUser::FormatName($arParams['TITLE_NAME_TEMPLATE'], $arTmpUser, $bUseLogin);
		}
		elseif ($arParams["PAGE"] == "user_groups")
		{
			$arTmpUser = array(
				"NAME" => $arResult["User"]["~NAME"],
				"LAST_NAME" => $arResult["User"]["~LAST_NAME"],
				"SECOND_NAME" => $arResult["User"]["~SECOND_NAME"],
				"LOGIN" => $arResult["User"]["~LOGIN"],
			);
			$strTitleFormatted = CUser::FormatName($arParams['TITLE_NAME_TEMPLATE'], $arTmpUser, $bUseLogin);
		}

		if ($arParams["SET_TITLE"] == "Y")
		{
			if ($arParams["PAGE"] == "group_request_group_search")
				$APPLICATION->SetTitle($strTitleFormatted.": ".GetMessage("SONET_C36_PAGE_TITLE"));
			elseif ($arParams["PAGE"] == "user_groups")
				$APPLICATION->SetTitle($strTitleFormatted.": ".GetMessage("SONET_C36_PAGE_TITLE1"));
			elseif ($arParams["PAGE"] == "groups_subject" && is_array($arResult["Subject"]))
				$APPLICATION->SetTitle($arResult["Subject"]["NAME"]);
			else
			{
				if ($arResult["filter_my"] == "Y")
					$APPLICATION->SetTitle(GetMessage("SONET_C36_PAGE_TITLE2_1"));
				elseif ($arResult["filter_archive"] == "Y")
					$APPLICATION->SetTitle(GetMessage("SONET_C36_PAGE_TITLE2_2"));
				elseif ($arResult["filter_extranet"] == "Y")
					$APPLICATION->SetTitle(GetMessage("SONET_C36_PAGE_TITLE2_3"));
				else
					$APPLICATION->SetTitle(GetMessage("SONET_C36_PAGE_TITLE2"));
			}
		}

		if ($arParams["SET_NAV_CHAIN"] != "N")
		{
			if ($arParams["PAGE"] == "group_request_group_search")
			{
				$APPLICATION->AddChainItem($strTitleFormatted, CComponentEngine::MakePathFromTemplate($arParams["PATH_TO_USER"], array("user_id" => $arResult["UserRequest"]["ID"])));
				$APPLICATION->AddChainItem(GetMessage("SONET_C36_PAGE_TITLE"));
			}
			elseif ($arParams["PAGE"] == "user_groups")
			{
				$APPLICATION->AddChainItem($strTitleFormatted, CComponentEngine::MakePathFromTemplate($arParams["PATH_TO_USER"], array("user_id" => $arResult["User"]["ID"])));
				$APPLICATION->AddChainItem(GetMessage("SONET_C36_PAGE_TITLE1"));
			}
			else
			{
				if ($arResult["filter_my"] == "Y")
					$APPLICATION->AddChainItem(GetMessage("SONET_C36_PAGE_TITLE2_1"));
				elseif ($arResult["filter_archive"] == "Y")
					$APPLICATION->AddChainItem(GetMessage("SONET_C36_PAGE_TITLE2_2"));
				elseif ($arResult["filter_extranet"] == "Y")
					$APPLICATION->AddChainItem(GetMessage("SONET_C36_PAGE_TITLE2_3"));
				else
					$APPLICATION->AddChainItem(GetMessage("SONET_C36_PAGE_TITLE2"));
			}
		}
	}

	if (CSocNetUser::IsCurrentUserModuleAdmin() && CModule::IncludeModule('intranet'))
	{
		global $INTRANET_TOOLBAR;

		$INTRANET_TOOLBAR->AddButton(array(
			'HREF' => "/bitrix/admin/socnet_subject.php?lang=".LANGUAGE_ID,
			"TEXT" => GetMessage('SONET_C36_EDIT_ENTRIES'),
			'ICON' => 'settings',
			"SORT" => 1000,
		));
	}

}

$this->IncludeComponentTemplate();
?>






