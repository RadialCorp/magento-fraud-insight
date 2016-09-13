/**
 * Copyright (c) 2013-2016 Radial Commerce Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @copyright   Copyright (c) 2013-2016 Radial Commerce Inc. (http://www.radial.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
var RiskInsightApiValidation = new Class.create();
RiskInsightApiValidation.prototype = {
	initialize : function(elemId, ajaxUrl){
		this.elemId = elemId;
		this.ajaxUrl = ajaxUrl;
		Event.observe($(elemId), 'click', this.testApiConnection.bind(this));
	},
	getScopeElement: function(element) {
		if (typeof adminSystemConfig !== 'undefined') {
			return adminSystemConfig.getScopeElement(element);
		}
		return false;
	},
	testApiConnection: function() {
		var elem = $(this.elemId);
		var hostnameEle = $('radial_fraudinsight_risk_insight_hostname');
		var hostScopeEle = hostnameEle && this.getScopeElement(hostnameEle);
		var keyEle = $('radial_fraudinsight_risk_insight_key');
		var keyScopeEle = keyEle && this.getScopeElement(keyEle);
		var storeIdEle = $('radial_fraudinsight_risk_insight_store_id');
		var storeIdScopeEle = storeIdEle && this.getScopeElement(storeIdEle);
		var params = {
			'hostname_use_default': hostScopeEle && hostScopeEle.checked ? 1 : 0,
			'api_key_use_default': keyScopeEle && keyScopeEle.checked ? 1 : 0,
			'store_id_use_default': storeIdScopeEle && storeIdScopeEle.checked ? 1 : 0
		};
		// Need to be able to differentiate between these values being empty and
		// non-existent when handling the request.
		if (hostnameEle) {
			params.hostname = hostnameEle.value;
		}
		if (keyEle) {
			params.api_key = keyEle.value;
		}
		if (storeIdEle) {
			params.store_id = storeIdEle.value;
		}

		new Ajax.Request(this.ajaxUrl, {
			'parameters': params,
			'onSuccess': function(xhrResponse) {
				var response = {};
				try {
					response = xhrResponse.responseText.evalJSON();
				} catch (e) {
					response.success = false;
					response.message = 'Could Not Validate Settings';
				}
				if (response.success) {
					elem.removeClassName('fail').addClassName('success');
				} else {
					elem.removeClassName('success').addClassName('fail');
				}
				$('validation_result').update(response.message);
			}
		});
	}
};
