# eBay Enterprise Risk Insight Extension

The Risk Insight Extension enabled automatic high to low fraud transaction detection.

1. Automated integration and on-boarding
1. Flexible configuration

## How to get started:

1. Install extension from the Magento Connect store
1. Under System Configuration, you'll find eBay Enterprise on the left hand navigation pane. Click to begin Risk Insight installation.
1. Select “Yes” to enable Risk Insight Extension.
1. Insert your store id into the extension.
1. Insert your Hostname into the extension.
1. Insert your API Key into the extension.
1. Insert an API Timeout or leave the default.
1. Select "Yes" or "No" if you wish to log Request/Response Payload to Risk Insight Web service.
1. Select an Order Source Override or leave blank.
1. Under the Response Actions are 4 select box for each possible response returned by the Risk Insight API (HIGH, MEDIUM, LOW, UNKNOWN) and for each response you can choose to Process, Hold for Review or Cancel and order.
1. Place test transaction to confirm installation.

## How to install the Risk Insight Extension on a Vanilla Magento Webstore


Assumption: you are on a Linux system with `Apache`, `PHP`, `MySQL` and `git` installed.


### STEP 1: Clone the `magento-risk-insight` repository from gitlab
- You need `git` install on your environment
- Run command: `git clone https://gitlab.gspt.net/Cheetah/magento-risk-insight.git`


### STEP 2: Use the `MagentoTarToConnect` tool to create Magento Connect Archive of the Risk Insight Extension
- Clone the `MagentoTarToConnect` repository from the public github
- Run command: `git clone https://github.com/astorm/MagentoTarToConnect.git`


### STEP 3: Creating Magento Compatible Tarball of the Risk Insight Extension
- `CD` to where you clone the `magento-risk-insight` repository
- Run command: `php /path/to/MagentoTarToConnect/magento-tar-to-connect.php deploy/extension-config.php`
- The command above will create a `build` directory under the current `magento-risk-insight` directory
- In the `build` directory there will be an `eBay_Enterprise_Risk_Insight_Extension.tar` file


### STEP 4: Installing the Risk Extension in your Vanilla Magento Webstore
- Navigate to this URL in your webstore `http://<webstore-domain>.com/downloader/`
- ![magento-connect-manager-login](docs/static/magento-connect-manager-login.png)
- Enter the same user name and password you use to login into admin of your webstore
- After successfully logged in, make sure under the `Manage Existing Extensions` you see the text `Channel: Magento Community Edition`
- ![magento-connect-manager-dashboard](docs/static/magento-connect-manager-dashboard.png)
- if you do not see the text `Channel: Magento Community Edition`, then, you need to `cd` into where your Magento webstore php files are being hosted in the server if you have access, then you can run the following commands: `./mage && ./mage mage-setup`
- Under the `Direct package file upload` you can browse and upload the `eBay_Enterprise_Risk_Insight_Extension.tar` file, then click the upload button and after successfully uploaded the Risk Insight Extension you can click on the refresh button.
- ![magento-connect-manager-upload-extension](docs/static/magento-connect-manager-upload-extension.png)
- Risk Insight Extension was successfully installed
- ![magento-connect-manager-install-extension](docs/static/magento-connect-manager-install-extension.png)
- Now, you can log out of Magento Connect Manager and go back to the admin `http://<webstore-domain>.com/admin/`, then login into admin and then navigate to `System > Configuration`.
- In the configuration page, you should see the `EBAY ENTERPRISE` tab and under that tab is the `Risk` configurations. Click the `Risk` sub-tab.
- ![risk-insight-configuration-tab](docs/static/risk-insight-configuration-tab.png)
- Enter configuration information for Risk Insight Extension
- ![isk-insight-configuration-settings](docs/static/risk-insight-configuration-settings.png)

---
Copyright &copy; 2015 eBay Enterprise, Inc.
