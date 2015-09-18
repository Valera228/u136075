__logOnDateChange = function(sel)
{
	var bShowFrom=false, bShowTo=false, bShowHellip=false, bShowDays=false, bShowBr=false;

	if(sel.value == 'interval')
		bShowBr = bShowFrom = bShowTo = bShowHellip = true;
	else if(sel.value == 'before')
		bShowTo = true;
	else if(sel.value == 'after' || sel.value == 'exact')
		bShowFrom = true;
	else if(sel.value == 'days')
		bShowDays = true;

	BX('flt_date_from_span').style.display = (bShowFrom? '':'none');
	BX('flt_date_to_span').style.display = (bShowTo? '':'none');
	BX('flt_date_hellip_span').style.display = (bShowHellip? '':'none');
	BX('flt_date_day_span').style.display = (bShowDays? 'inline':'none');
}

function onFilterGroupSelect(arGroups)
{
	if (arGroups[0])
	{
		BX('filter-field-user').value = '';
		document.forms["log_filter"]["flt_to_user_id"].value = 0;
		document.forms["log_filter"]["flt_group_id"].value = arGroups[0].id;
		BX.removeClass(BX("filter-field-group").parentNode.parentNode, "webform-field-textbox-empty");
	}
}

function onFilterCreatedBySelect(arUser)
{
	if (arUser.id)
	{
		document.forms["log_filter"]["flt_created_by_id"].value = arUser.id;
		document.forms["log_filter"]["filter-field-created-by"].value = arUser.name;
		BX.removeClass(BX("filter-field-created-by").parentNode.parentNode, "webform-field-textbox-empty");
		if (BX("flt_comments_cont"))
		{
			BX("flt_comments_cont").style.display = "block";
		}
	}
	else if (BX("flt_comments_cont"))
	{
		BX("flt_comments_cont").style.display = "none";
	}

	filterCreatedByPopup.close();
}

function onFilterUserSelect(arUser)
{
	if (arUser.id)
	{
		BX('filter-field-group').value = '';
		document.forms["log_filter"]["flt_group_id"].value = 0;
		document.forms["log_filter"]["flt_to_user_id"].value = arUser.id;
		document.forms["log_filter"]["filter-field-user"].value = arUser.name;
		BX.removeClass(BX("filter-field-user").parentNode.parentNode, "webform-field-textbox-empty");
	}

	filterUserPopup.close();
}

function onFilterDestChangeTab(type)
{
	var type_hide;
	if (type != 'group')
	{
		type = 'user';
		type_hide = 'group';
		if (
			filterGroupsPopup !== undefined 
			&& filterGroupsPopup.popupWindow !== undefined
		)
		{
			filterGroupsPopup.popupWindow.close();
		}
	}
	else
	{
		type_hide = 'user';
		if (
			filterUserPopup !== undefined
		)
		{
			filterUserPopup.close();
		}
	}

	BX.removeClass(BX('filter-dest-' + type + '-tab'), 'webform-field-action-link');
	BX.addClass(BX('filter-dest-' + type_hide + '-tab'), 'webform-field-action-link');

	BX('filter-dest-' + type + '-block').style.display = 'inline-block';
	BX('filter-dest-' + type_hide + '-block').style.display = 'none';

	if (type != 'group')
	{
		BX("filter-field-user").focus();
		__SLFShowUseropup(BX("filter-field-user"));
	}
	else
	{
		BX("filter-field-group").focus();
		__SLFShowGroupsPopup();
	}
}

var filterPopup = false;

function ShowFilterPopup(bindElement)
{
	if (!filterPopup)
	{
		//BX.showWait(bindElement);
		BX.ajax.get(BX.message('sonetLFAjaxPath'), function(data) 
		{
			BX.closeWait(bindElement);

			filterPopup = new BX.PopupWindow(
				'bx_log_filter_popup',
				bindElement,
				{
					closeIcon : false,
					offsetTop: 5,
					autoHide: true,
					zIndex : -100,
					//angle : { offset : 59},
					className : 'sonet-log-filter-popup-window',
					events : {
						onPopupClose: function() {
							if (!BX.hasClass(this.bindElement, "pagetitle-menu-filter-set"))
								BX.removeClass(this.bindElement, "pagetitle-menu-filter-selected")
						},
						onPopupShow: function() { BX.addClass(this.bindElement, "pagetitle-menu-filter-selected")}
					}
				}
			);

			var filter_block = BX.create('DIV', {html: data});
			filterPopup.setContent(filter_block.firstChild);
			filterPopup.show();
			
			BX.bind(BX("filter-field-created-by"), "click", function(e) {
				if(!e) e = window.event;

				__SLFShowCreatedByPopup(this);
				return BX.PreventDefault(e);
			});

			BX.bind(BX.findNextSibling(BX("filter-field-created-by"), {tagName : "a"}), "click", function(e){
				if(!e) e = window.event;

				BX("filter-field-created-by").value = "";
				BX("filter_field_createdby_hidden").value = "0";
				BX.addClass(BX("filter-field-created-by").parentNode.parentNode, "webform-field-textbox-empty");
				if (BX("flt_comments_cont"))
				{
					BX("flt_comments_cont").style.display = "none";
				}
				return BX.PreventDefault(e);
			});

			if (BX("filter-field-group"))
			{
				BX.bind(BX("filter-field-group"), "click", function(e) {
					if(!e) e = window.event;

					__SLFShowGroupsPopup();
					return BX.PreventDefault(e);
				});

				BX.bind(BX.findNextSibling(BX("filter-field-group"), {tagName : "a"}), "click", function(e){
					if(!e) e = window.event;

					filterGroupsPopup.deselect(BX("filter_field_group_hidden").value.value);
					BX("filter_field_group_hidden").value = "0";
					BX.addClass(BX("filter-field-group").parentNode.parentNode, "webform-field-textbox-empty");
					return BX.PreventDefault(e);
				});
			}

			if (BX("filter-field-user"))
			{
				BX.bind(BX("filter-field-user"), "click", function(e) {
					if(!e) e = window.event;

					__SLFShowUseropup(this);
					return BX.PreventDefault(e);
				});

				BX.bind(BX.findNextSibling(BX("filter-field-user"), {tagName : "a"}), "click", function(e){
					if(!e) e = window.event;
					BX("filter-field-user").value = "";
					BX("filter_field_user_hidden").value = "0";
					BX.addClass(BX("filter-field-user").parentNode.parentNode, "webform-field-textbox-empty");
					return BX.PreventDefault(e);
				});
			}
		});
	}
	else
	{
		filterPopup.show();	
	}

}

function __SLFShowCreatedByPopup(obj)
{
	filterCreatedByPopup = BX.PopupWindowManager.create("filter-created-by-popup", obj.parentNode, {
		offsetTop : 1,
		autoHide : true,
		content : BX("FILTER_CREATEDBY_selector_content"),
		zIndex : 1200,
		buttons : [
			new BX.PopupWindowButton({
				text : BX.message("sonetLDialogClose"),
				className : "popup-window-button-accept",
				events : {
					click : function() {
						this.popupWindow.close();
					}
				}
			})
		]
	});

	if (filterCreatedByPopup.popupContainer.style.display != "block")
	{
		filterCreatedByPopup.show();
	}
}

function __SLFShowGroupsPopup()
{
	BX('filter-field-user').value = '';
	BX('filter_field_user_hidden').value = "0";

	filterGroupsPopup.show();
}

function __SLFShowUseropup(obj)
{
	filterUserPopup = BX.PopupWindowManager.create("filter-user-popup", obj.parentNode, {
		offsetTop : 1,
		autoHide : true,
		content : BX("FILTER_USER_selector_content"),
		zIndex : 1200,
		buttons : [
			new BX.PopupWindowButton({
				text : BX.message("sonetLDialogClose"),
				className : "popup-window-button-accept",
				events : {
					click : function() {
						this.popupWindow.close();
					}
				}
			})
		]
	});

	if (filterUserPopup.popupContainer.style.display != "block")
	{
		filterUserPopup.show();
	}
}

function __logOnReload(log_counter)
{
	if (BX("menu-popup-lenta-sort-popup"))
	{
		var arMenuItems = BX.findChildren(BX("menu-popup-lenta-sort-popup"), { className: 'lenta-sort-item' }, true);
		
		if (!BX.hasClass(arMenuItems[0], 'lenta-sort-item-selected'))
		{
			for (var i = 0; i < arMenuItems.length; i++)
			{
				if (i == 0)
					BX.addClass(arMenuItems[i], 'lenta-sort-item-selected');
				else if (i != (arMenuItems.length-1))
					BX.removeClass(arMenuItems[i], 'lenta-sort-item-selected');
			}
		}
	}

	if (BX("lenta-sort-button"))
	{
		var menuButtonText = BX.findChild(BX("lenta-sort-button"), { className: 'lenta-sort-button-text-internal' }, true, false);
		if (menuButtonText)
			menuButtonText.innerHTML = BX.message('sonetLFAllMessages');
	}

	var counter_cont = BX("sonet_log_counter_preset", true);
	if (counter_cont)
	{
		if (parseInt(log_counter) > 0)
		{
			counter_cont.style.display = "inline-block";
			counter_cont.innerHTML = log_counter;
		}
		else
		{
			counter_cont.innerHTML = '';
			counter_cont.style.display = "none";
		}
	}
}
