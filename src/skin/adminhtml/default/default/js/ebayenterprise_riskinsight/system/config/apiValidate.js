/**
 * Copyright (c) 2015 eBay Enterprise, Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the eBay Enterprise
 * Magento Extensions End User License Agreement
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * http://www.ebayenterprise.com/files/pdf/Magento_Connect_Extensions_EULA_050714.pdf
 *
 * @copyright   Copyright (c) 2015 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://www.ebayenterprise.com/files/pdf/Magento_Connect_Extensions_EULA_050714.pdf  eBay Enterprise Magento Extensions End User License Agreement
 *
 */
var RiskInsightApiValidation = new Class.create();
RiskInsightApiValidation.prototype = {
	initialize : function(elemId, ajaxUrl){
		this.elemId = elemId;
		this.ajaxUrl = ajaxUrl;
		Event.observe($(elemId), 'click', this.testApiConnection.bind(this));
	},
	testApiConnection: function() {
		var elem = $(this.elemId);
		var hostnameEle = $('ebayenterprise_riskinsight_risk_insight_hostname');
		var hostScopeEle = hostnameEle && adminSystemConfig.getScopeElement(hostnameEle);
		var keyEle = $('ebayenterprise_riskinsight_risk_insight_key');
		var keyScopeEle = keyEle && adminSystemConfig.getScopeElement(keyEle);
		var storeIdEle = $('ebayenterprise_riskinsight_risk_insight_store_id');
		var storeIdScopeEle = storeIdEle && adminSystemConfig.getScopeElement(storeIdEle);
		var params = {
			'hostname_use_default': hostScopeEle && hostScopeEle.checked ? 1 : 0,
			'key_use_default': keyScopeEle && keyScopeEle.checked ? 1 : 0,
			'store_id_use_default': storeIdScopeEle && storeIdScopeEle.checked ? 1 : 0
		};
		// Need to be able to differentiate between these values being empty and
		// non-existent when handling the request.
		if (hostnameEle) {
			params.hostname = hostnameEle.value;
		}
		if (keyEle) {
			params.key = keyEle.value;
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
